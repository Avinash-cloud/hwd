<?php

namespace App\Services;

use App\Mail\OfflineMembershipWelcomeMail;
use App\Models\Address;
use App\Models\Category;
use App\Models\Membership;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\SubscriptionLog;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OfflineMembershipOnboardingService
{
    public function __construct(
        protected SubscriptionOrderService $subscriptionOrderService
    ) {}

    /**
     * Enroll an existing offline member cleanly without charging online registration fees again.
     *
     * @param array{
     *     name: string,
     *     email: string,
     *     phone: string,
     *     address_line1: string,
     *     address_line2?: ?string,
     *     city: string,
     *     state: string,
     *     postal_code: string,
     *     fee_paid?: float|int|string|null,
     *     validity_years?: int|string|null,
     *     starts_at?: string|null,
     *     payment_method?: string|null,
     *     payment_id?: ?string,
     *     invoice_number?: ?string,
     *     prepaid_months?: int|string|null,
     *     prepaid_until?: string|null,
     *     dispatch_current_month?: bool,
     *     send_welcome_email?: bool,
     *     notes?: ?string
     * } $data
     * @return array{user: User, membership: Membership, subscription: ?MembershipSubscription, order: ?Order, is_new_user: bool}
     */
    public function enroll(array $data): array
    {
        $normalizedEmail = strtolower(trim($data['email']));
        $trimmedPhone = trim($data['phone']);
        $startsAt = ! empty($data['starts_at'])
            ? Carbon::parse($data['starts_at'])
            : now();
        $validityYears = (int) ($data['validity_years'] ?? SiteSetting::get('membership_validity_years', 5));
        $expiresAt = $startsAt->copy()->addYears($validityYears);

        if ($expiresAt->isPast()) {
            throw new InvalidArgumentException("Computed expiry date ({$expiresAt->toDateString()}) is already in the past.");
        }

        $feePaid = isset($data['fee_paid']) && $data['fee_paid'] !== ''
            ? (float) $data['fee_paid']
            : (float) SiteSetting::get('membership_fee', 500);

        $prepaidMonths = (int) ($data['prepaid_months'] ?? 0);
        $prepaidUntil = null;
        if (! empty($data['prepaid_until'])) {
            $prepaidUntil = Carbon::parse($data['prepaid_until'])->endOfDay();
        } elseif ($prepaidMonths > 0) {
            $prepaidUntil = $startsAt->copy()->addMonths($prepaidMonths)->endOfDay();
        }

        $dispatchCurrentMonth = (bool) ($data['dispatch_current_month'] ?? false);
        $sendWelcomeEmail = (bool) ($data['send_welcome_email'] ?? true);

        $result = DB::transaction(function () use (
            $data,
            $normalizedEmail,
            $trimmedPhone,
            $startsAt,
            $validityYears,
            $expiresAt,
            $feePaid,
            $prepaidUntil,
            $dispatchCurrentMonth
        ): array {
            $user = User::where('email', $normalizedEmail)->first();
            $isNewUser = false;

            if ($user && $user->hasActiveMembership()) {
                throw new InvalidArgumentException("User '{$normalizedEmail}' already has an active membership.");
            }

            if (! $user) {
                $user = User::create([
                    'name' => trim($data['name']),
                    'email' => $normalizedEmail,
                    'phone' => $trimmedPhone,
                    'password' => Hash::make(Str::random(24)),
                    'is_admin' => false,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                $isNewUser = true;
            } elseif (empty($user->phone) && $trimmedPhone !== '') {
                $user->update(['phone' => $trimmedPhone]);
            }

            $shippingAddressArray = [
                'recipient_name' => trim($data['name']),
                'phone' => $trimmedPhone,
                'email' => $normalizedEmail,
                'address_line1' => trim($data['address_line1']),
                'address_line2' => isset($data['address_line2']) && trim((string) $data['address_line2']) !== ''
                    ? trim((string) $data['address_line2'])
                    : null,
                'city' => trim($data['city']),
                'state' => trim($data['state']),
                'postal_code' => trim($data['postal_code']),
                'country' => 'India',
            ];

            Address::updateOrCreate(
                ['user_id' => $user->id, 'is_default' => true],
                [
                    'type' => 'shipping',
                    'recipient_name' => $shippingAddressArray['recipient_name'],
                    'phone' => $shippingAddressArray['phone'],
                    'address_line1' => $shippingAddressArray['address_line1'],
                    'address_line2' => $shippingAddressArray['address_line2'],
                    'city' => $shippingAddressArray['city'],
                    'state' => $shippingAddressArray['state'],
                    'postal_code' => $shippingAddressArray['postal_code'],
                    'country' => 'India',
                ]
            );

            $invoiceNumber = ! empty($data['invoice_number'])
                ? trim((string) $data['invoice_number'])
                : 'HB-MEM-'.$startsAt->format('Ymd').'-'.strtoupper(Str::random(4));

            if (Membership::where('invoice_number', $invoiceNumber)->exists()) {
                throw new InvalidArgumentException("Invoice number '{$invoiceNumber}' already exists.");
            }

            $paymentMethod = ! empty($data['payment_method']) ? strtolower(trim((string) $data['payment_method'])) : 'offline';
            $paymentId = ! empty($data['payment_id'])
                ? trim((string) $data['payment_id'])
                : 'OFF-'.$startsAt->format('Ymd').'-'.strtoupper(Str::random(5));

            $membership = Membership::create([
                'user_id' => $user->id,
                'status' => 'active',
                'fee_paid' => $feePaid,
                'validity_years' => $validityYears,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'payment_method' => $paymentMethod,
                'payment_id' => $paymentId,
                'invoice_number' => $invoiceNumber,
                'terms_accepted' => true,
                'terms_accepted_at' => $startsAt,
                'terms_version' => 'offline-import-1.0',
            ]);

            $subscription = null;
            $initialOrder = null;

            if ($prepaidUntil !== null || $dispatchCurrentMonth) {
                $subscription = $this->ensureOfflineSubscription($user, $membership, $shippingAddressArray, $prepaidUntil, $data['notes'] ?? null);

                if ($dispatchCurrentMonth) {
                    $initialOrder = $this->dispatchMonthlyOrderForMembership($membership, $paymentMethod, $paymentId);
                }
            }

            return [
                'user' => $user,
                'membership' => $membership,
                'subscription' => $subscription,
                'order' => $initialOrder,
                'is_new_user' => $isNewUser,
            ];
        });

        if ($sendWelcomeEmail) {
            $this->sendWelcomeNotification(
                user: $result['user'],
                membership: $result['membership'],
                isNewUser: $result['is_new_user'],
                prepaidUntil: $prepaidUntil,
                hasRazorpayMandate: false
            );
        }

        return $result;
    }

    /**
     * Dispatch a monthly Gangajal order and book NimbusPost shipment for an active offline/prepaid member.
     */
    public function dispatchMonthlyOrderForMembership(
        Membership $membership,
        string $paymentMethod = 'offline_prepaid',
        ?string $paymentReference = null
    ): ?Order {
        $membership->loadMissing(['user', 'activeSubscription']);
        $user = $membership->user;

        if (! $user || ! $membership->isActive()) {
            throw new InvalidArgumentException('Monthly dispatch requires an active membership.');
        }

        $subscription = $membership->activeSubscription;
        if (! $subscription) {
            $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();
            $shippingAddressArray = [
                'recipient_name' => $defaultAddress?->recipient_name ?? $user->name,
                'phone' => $defaultAddress?->phone ?? ($user->phone ?? '9999999999'),
                'email' => $user->email,
                'address_line1' => $defaultAddress?->address_line1 ?? 'Address on file',
                'address_line2' => $defaultAddress?->address_line2,
                'city' => $defaultAddress?->city ?? 'Haridwar',
                'state' => $defaultAddress?->state ?? 'Uttarakhand',
                'postal_code' => $defaultAddress?->postal_code ?? '249401',
                'country' => 'India',
            ];

            $subscription = $this->ensureOfflineSubscription($user, $membership, $shippingAddressArray, now()->addDays(30));
        }

        $billingPeriod = now()->format('Y-m');
        $billingAmount = (float) $subscription->billing_amount;
        $refId = $paymentReference ?: ('OFF-DISP-'.date('Ymd').'-'.strtoupper(Str::random(4)));

        $order = $this->subscriptionOrderService->createOrderForBillingCycle(
            subscription: $subscription,
            billingPeriod: $billingPeriod,
            amountPaid: $billingAmount,
            paymentId: $refId,
            paymentMethod: $paymentMethod
        );

        if ($order) {
            $this->subscriptionOrderService->bookShipmentAndNotify($order, $subscription);
        }

        return $order;
    }

    /**
     * Ensure an offline/prepaid subscription tracking record exists for monthly dispatches.
     *
     * @param  array<string, mixed>  $shippingAddress
     */
    protected function ensureOfflineSubscription(
        User $user,
        Membership $membership,
        array $shippingAddress,
        ?CarbonInterface $prepaidUntil = null,
        ?string $notes = null
    ): MembershipSubscription {
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $product = $billing['product'];
        $monthlyDebit = $billing['total_monthly_charge'];

        if (! $product) {
            $category = Category::firstOrCreate(['slug' => 'gangajal'], ['name' => 'Sacred Gangajal']);
            $product = Product::create([
                'category_id' => $category->id,
                'name' => 'Haridwar Sacred Gangajal 500ml',
                'slug' => 'haridwar-gangajal-500ml-'.Str::random(6),
                'price' => $billing['product_price'] ?: 50.00,
                'volume_ml' => 500,
                'is_gangajal' => true,
                'is_active' => true,
                'stock' => 999,
            ]);
            $monthlyDebit = round((float) $product->price + $billing['delivery_charge'], 2);
        }

        $subscription = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => $product->id,
            'status' => 'active',
            'billing_mode' => 'offline_prepaid',
            'billing_interval_days' => 30,
            'billing_amount' => $monthlyDebit,
            'next_billing_date' => now(),
            'last_billed_at' => $membership->starts_at,
            'prepaid_until' => $prepaidUntil,
            'razorpay_subscription_id' => null,
            'shipping_address' => $shippingAddress,
            'notes' => $notes ?: 'Offline pre-website member onboarded via Admin.',
        ]);

        SubscriptionLog::create([
            'subscription_id' => $subscription->id,
            'event' => 'offline_membership_imported',
            'amount' => (float) $membership->fee_paid,
            'payment_id' => $membership->payment_id,
            'details' => [
                'invoice_number' => $membership->invoice_number,
                'prepaid_until' => $prepaidUntil?->toDateString(),
                'note' => 'Offline membership onboarded with offline_prepaid tracking.',
            ],
        ]);

        return $subscription;
    }

    /**
     * Send welcome email (with password setup URL for new users) safely.
     */
    protected function sendWelcomeNotification(
        User $user,
        Membership $membership,
        bool $isNewUser,
        ?CarbonInterface $prepaidUntil,
        bool $hasRazorpayMandate
    ): void {
        try {
            $setPasswordUrl = null;
            if ($isNewUser) {
                $token = Password::broker()->createToken($user);
                $setPasswordUrl = route('password.reset', [
                    'token' => $token,
                    'email' => $user->email,
                ]);
            }

            Mail::to($user->email)->send(new OfflineMembershipWelcomeMail(
                membership: $membership->loadMissing('user'),
                setPasswordUrl: $setPasswordUrl,
                prepaidUntil: $prepaidUntil,
                hasRazorpayMandate: $hasRazorpayMandate
            ));
        } catch (\Throwable $e) {
            Log::warning("OfflineMembershipOnboardingService: Failed to send welcome email to {$user->email}: ".$e->getMessage());
        }
    }
}
