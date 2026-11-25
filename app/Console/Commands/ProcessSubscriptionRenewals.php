<?php

namespace App\Console\Commands;

use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\SubscriptionLog;
use App\Services\RazorpayService;
use App\Services\SubscriptionOrderService;
use Illuminate\Console\Command;

class ProcessSubscriptionRenewals extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:process-renewals {--user= : Specific user ID or email to process} {--force : Process immediately even if next_billing_date is in the future}';

    /**
     * The console command description.
     */
    protected $description = 'Reconciliation safety-net: verify missed webhook payments with Razorpay API and create orders for confirmed charges.';

    public function __construct(
        protected RazorpayService $razorpayService,
        protected SubscriptionOrderService $orderService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Reconciliation: Checking for due subscriptions with no matching order...');

        $query = MembershipSubscription::where('status', 'active')
            ->with(['user', 'membership', 'product', 'variant']);

        if (! $this->option('force')) {
            $query->where('next_billing_date', '<=', now());
        }

        if ($userIdentifier = $this->option('user')) {
            $query->whereHas('user', function ($q) use ($userIdentifier) {
                $q->where('id', $userIdentifier)->orWhere('email', $userIdentifier);
            });
        }

        $dueSubscriptions = $query->get();

        $this->info("Found {$dueSubscriptions->count()} subscription(s) past billing date.");

        $processed = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($dueSubscriptions as $sub) {
            $user = $sub->user;
            $membership = $sub->membership;

            if (! $user) {
                continue;
            }

            // Auto-renew 5-year membership tenure if near expiry
            $this->autoRenewMembershipIfNeeded($sub);

            // Skip if membership is not active
            if (! $membership || ! $membership->isActive()) {
                $this->warn("Skipping sub #{$sub->id}: Membership not active.");
                $sub->update(['status' => 'past_due']);
                $skipped++;

                continue;
            }

            // Determine the billing period to reconcile
            $billingPeriod = now()->format('Y-m');

            // Check if an order already exists for this cycle (webhook may have handled it)
            $existingOrder = Order::where('membership_subscription_id', $sub->id)
                ->where('billing_period', $billingPeriod)
                ->exists();

            if ($existingOrder) {
                $this->info("Sub #{$sub->id}: Order already exists for {$billingPeriod} (webhook handled it). Skipping.");
                $skipped++;

                continue;
            }

            // Offline-prepaid subscriptions do not use Razorpay API; dispatch only within prepaid window
            if ($sub->isOfflinePrepaid() || empty($sub->razorpay_subscription_id)) {
                if ($sub->hasActivePrepaidCoverage()) {
                    $prepaidRef = 'OFF-PREPAID-'.now()->format('Ym').'-'.$sub->id;
                    $order = $this->orderService->createOrderForBillingCycle(
                        subscription: $sub,
                        billingPeriod: $billingPeriod,
                        amountPaid: (float) $sub->billing_amount,
                        paymentId: $prepaidRef,
                        paymentMethod: 'offline_prepaid'
                    );

                    if ($order) {
                        $this->orderService->bookShipmentAndNotify($order, $sub);
                        $processed++;
                        $this->info("Sub #{$sub->id}: Dispatched offline-prepaid order #{$order->order_number}.");
                    } else {
                        $skipped++;
                    }
                } else {
                    $this->info("Sub #{$sub->id}: Offline member outside prepaid coverage — awaiting Razorpay mandate activation.");
                    $skipped++;
                }

                continue;
            }

            // Verify payment status with Razorpay API
            $this->info("Sub #{$sub->id}: No order for {$billingPeriod}. Verifying with Razorpay API...");

            $rzpSub = $this->razorpayService->fetchSubscription($sub->razorpay_subscription_id);

            if (! $rzpSub) {
                $this->error("Sub #{$sub->id}: Could not fetch Razorpay subscription status.");
                $this->orderService->alertAdmin(
                    'Razorpay API Unreachable During Reconciliation',
                    $sub,
                    null,
                    ['razorpay_sub_id' => $sub->razorpay_subscription_id]
                );
                $failed++;

                continue;
            }

            // If subscription is halted/cancelled on Razorpay, update locally and alert
            if (in_array($rzpSub['status'] ?? '', ['halted', 'cancelled'])) {
                $sub->update(['status' => $rzpSub['status'] === 'halted' ? 'past_due' : 'cancelled']);
                $this->warn("Sub #{$sub->id}: Razorpay status is '{$rzpSub['status']}'. Updated locally.");
                $this->orderService->alertAdmin(
                    "Subscription {$rzpSub['status']} on Razorpay",
                    $sub,
                    null,
                    ['razorpay_status' => $rzpSub['status']]
                );
                $failed++;

                continue;
            }

            // Fetch invoices to find the latest paid one for this cycle
            $invoices = $this->razorpayService->fetchSubscriptionInvoices($sub->razorpay_subscription_id);

            $latestPaidInvoice = collect($invoices)
                ->filter(fn (array $inv): bool => ($inv['status'] ?? '') === 'paid')
                ->sortByDesc('paid_at')
                ->first();

            if (! $latestPaidInvoice) {
                $this->warn("Sub #{$sub->id}: No paid invoice found on Razorpay. Payment may still be pending.");
                $this->orderService->alertAdmin(
                    'No Paid Invoice Found — Payment Pending or Failed',
                    $sub,
                    null,
                    [
                        'razorpay_sub_id' => $sub->razorpay_subscription_id,
                        'billing_period' => $billingPeriod,
                        'next_billing_date' => $sub->next_billing_date?->toDateString(),
                    ]
                );
                $failed++;

                continue;
            }

            $paymentId = $latestPaidInvoice['payment_id'] ?? null;
            $amountPaid = isset($latestPaidInvoice['amount_paid']) ? ((float) $latestPaidInvoice['amount_paid'] / 100) : 0;

            if (! $paymentId || $amountPaid <= 0) {
                $this->warn("Sub #{$sub->id}: Paid invoice found but no valid payment_id or amount.");
                $this->orderService->alertAdmin(
                    'Invoice Paid But Missing Payment Details',
                    $sub,
                    null,
                    ['invoice' => $latestPaidInvoice]
                );
                $failed++;

                continue;
            }

            // Check if this specific payment_id was already used in a previous order
            $paymentAlreadyUsed = Order::where('payment_id', $paymentId)->exists();
            if ($paymentAlreadyUsed) {
                $this->info("Sub #{$sub->id}: Payment {$paymentId} already linked to an existing order. Skipping.");
                // Advance the billing date so we don't re-check every day
                $sub->update([
                    'last_billed_at' => now(),
                    'next_billing_date' => now()->addDays($sub->billing_interval_days ?: 30),
                ]);
                $skipped++;

                continue;
            }

            // Payment verified — create the order with the REAL Razorpay payment ID
            $this->info("Sub #{$sub->id}: Payment verified (₹{$amountPaid}, {$paymentId}). Creating order...");

            $order = $this->orderService->createOrderForBillingCycle(
                subscription: $sub,
                billingPeriod: $billingPeriod,
                amountPaid: $amountPaid,
                paymentId: $paymentId,
                paymentMethod: 'razorpay_recurring'
            );

            if ($order) {
                $this->orderService->bookShipmentAndNotify($order, $sub);
                $this->info("Order #{$order->order_number} created and shipment booked for sub #{$sub->id}.");
                $processed++;
            } else {
                $this->info("Sub #{$sub->id}: Order not created (likely dedup race condition). Skipping.");
                $skipped++;
            }
        }

        $this->info("Reconciliation complete: {$processed} order(s) created, {$skipped} skipped, {$failed} failed.");

        return Command::SUCCESS;
    }

    /**
     * Auto-renew 5-year membership tenure if expired or within 30 days of expiry.
     */
    protected function autoRenewMembershipIfNeeded(MembershipSubscription $sub): void
    {
        $membership = $sub->membership;

        if (! $membership || ! $membership->expires_at) {
            return;
        }

        if ($membership->expires_at->lte(now()->addDays(30))) {
            $oldExpiry = $membership->expires_at->copy();
            $newExpiry = $membership->expires_at->copy()->addYears(5);
            $membership->update([
                'expires_at' => $newExpiry,
                'status' => 'active',
            ]);

            $this->info("Membership #{$membership->id} automatically renewed for 5 years until {$newExpiry->toDateString()}");

            SubscriptionLog::create([
                'subscription_id' => $sub->id,
                'event' => 'membership_auto_renewed',
                'amount' => 0.00,
                'details' => [
                    'previous_expiry' => $oldExpiry->toIso8601String(),
                    'new_expiry' => $newExpiry->toIso8601String(),
                    'note' => '5-Year sacred devotee membership automatically renewed via recurring subscription mandate.',
                ],
            ]);
        }
    }
}
