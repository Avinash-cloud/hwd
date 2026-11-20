<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SubscriptionLog;
use App\Services\GstService;
use App\Services\NimbusPostService;
use App\Services\OrderMailService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayWebhookController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpayService,
        protected NimbusPostService $nimbusPostService,
        protected OrderMailService $orderMailService
    ) {}

    /**
     * Handle incoming webhooks from Razorpay.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = (string) $request->header('X-Razorpay-Signature', '');

        if (! $this->razorpayService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Razorpay Webhook Signature Mismatch');

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->input('event');
        $payloadData = $request->input('payload', []);

        Log::info('Razorpay Webhook Received: '.$event);

        switch ($event) {
            case 'subscription.charged':
            case 'invoice.paid':
                $this->handleSubscriptionCharged($payloadData);
                break;

            case 'subscription.cancelled':
                $this->handleSubscriptionCancelled($payloadData);
                break;

            case 'subscription.halted':
                $this->handleSubscriptionHalted($payloadData);
                break;

            case 'payment.failed':
                $this->handlePaymentFailed($payloadData);
                break;

            default:
                break;
        }

        return response()->json(['status' => 'handled']);
    }

    /**
     * Automatic recurring order creation for subscribed members upon successful charge.
     * Also handles automatic 5-year membership tenure renewal.
     */
    protected function handleSubscriptionCharged(array $payload): void
    {
        $payment = $payload['payment']['entity'] ?? [];
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? $payment['subscription_id'] ?? null;

        if (! $subId) {
            return;
        }

        /** @var MembershipSubscription|null $subscription */
        $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)
            ->with(['user', 'membership', 'product', 'variant'])
            ->first();

        if (! $subscription || ! $subscription->user) {
            Log::info('No local subscription found for Razorpay Sub: '.$subId);

            return;
        }

        $user = $subscription->user;
        $membership = $subscription->membership;

        // Auto-renew 5-year membership tenure if expired or within 30 days of expiry
        if ($membership) {
            if ($membership->expires_at && $membership->expires_at->lte(now()->addDays(30))) {
                $oldExpiry = $membership->expires_at->copy();
                $newExpiry = $membership->expires_at->copy()->addYears(5);
                $membership->update([
                    'expires_at' => $newExpiry,
                    'status' => 'active',
                ]);

                Log::info("Membership #{$membership->id} auto-renewed for 5 years until {$newExpiry->toDateString()}");

                SubscriptionLog::create([
                    'subscription_id' => $subscription->id,
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

        /** @var Product|null $product */
        $product = $subscription->product ?? Product::where('is_gangajal', true)->where('volume_ml', 500)->first() ?? Product::first();
        $variant = $subscription->variant;

        $amountPaid = isset($payment['amount']) ? ((float) $payment['amount'] / 100) : (float) $subscription->billing_amount;
        $paymentId = $payment['id'] ?? ('pay_sub_'.Str::random(10));

        $billing = MembershipSubscription::getMonthlyBillingDetails($product);
        $productPrice = $billing['product_price'];
        $deliveryCharge = $billing['delivery_charge'];

        if ($amountPaid > 0) {
            if ($amountPaid >= $deliveryCharge) {
                $productPrice = round($amountPaid - $deliveryCharge, 2);
            } else {
                $deliveryCharge = $amountPaid;
                $productPrice = 0.00;
            }
        }

        // Create Automated Order for this monthly cycle
        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();
        $orderNumber = 'HB-SUB-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        $gstRate = (float) ($variant?->gst_percentage ?? $product?->gst_percentage ?? 18.00);
        $productTax = GstService::calculate($productPrice, $gstRate);
        $shippingTax = GstService::calculate($deliveryCharge, 18.00);

        $totalTaxable = round($productTax['taxable'] + $shippingTax['taxable'], 2);
        $totalGst = round($productTax['gst'] + $shippingTax['gst'], 2);
        $halfGst = round($totalGst / 2, 2);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'batch_id' => $batch?->id,
            'subtotal' => $productPrice,
            'total_taxable_amount' => $totalTaxable,
            'total_gst_amount' => $totalGst,
            'cgst_amount' => $halfGst,
            'sgst_amount' => $halfGst,
            'igst_amount' => $totalGst,
            'discount_amount' => 0.00,
            'shipping_amount' => $deliveryCharge,
            'total_amount' => $amountPaid,
            'payment_method' => 'razorpay_subscription',
            'payment_status' => 'paid',
            'payment_id' => $paymentId,
            'order_status' => 'confirmed',
            'inventory_deducted' => false,
            'qr_token' => $qrToken,
            'shipping_address' => $subscription->shipping_address ?? [
                'recipient_name' => $user->name,
                'phone' => $user->phone ?? '9999999999',
                'email' => $user->email,
                'address_line1' => 'Member Delivery Address on file',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
                'country' => 'India',
            ],
            'customer_notes' => "Automated monthly 500ml Gangajal auto-debit subscription delivery (Plan: {$subId}, Product: ₹{$productPrice} + Delivery: ₹{$deliveryCharge}).",
        ]);

        if ($product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'product_name' => $product->name,
                'variant_name' => $variant?->name,
                'hsn_code' => $variant?->hsn_code ?? $product->hsn_code ?? '22019010',
                'gst_percentage' => $gstRate,
                'taxable_amount' => $productTax['taxable'],
                'gst_amount' => $productTax['gst'],
                'unit_price' => $productPrice,
                'quantity' => 1,
                'total_price' => $productPrice,
                'is_free_monthly_bottle' => false,
            ]);
        }

        // Update Subscription status & next billing date (+30 days)
        $subscription->update([
            'status' => 'active',
            'last_billed_at' => now(),
            'next_billing_date' => now()->addDays($subscription->billing_interval_days ?: 30),
            'payment_failures_count' => 0,
        ]);

        // Log successful recurring event
        SubscriptionLog::create([
            'subscription_id' => $subscription->id,
            'event' => 'payment_success',
            'amount' => $amountPaid,
            'order_id' => $order->id,
            'payment_id' => $paymentId,
            'details' => ['order_number' => $orderNumber],
        ]);

        // Auto-book shipment with NimbusPost for recurring membership delivery
        if (config('services.nimbuspost.auto_create_shipment', true)) {
            $this->nimbusPostService->createShipment($order);
        }

        // Send order placed confirmation email to subscriber
        $this->orderMailService->sendOrderPlaced($order);
    }

    /**
     * Handle subscription cancellation event.
     */
    protected function handleSubscriptionCancelled(array $payload): void
    {
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? null;

        if ($subId) {
            $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)->first();
            if ($subscription) {
                $subscription->update(['status' => 'cancelled']);
                SubscriptionLog::create([
                    'subscription_id' => $subscription->id,
                    'event' => 'cancelled',
                    'details' => ['reason' => 'Webhook subscription.cancelled received'],
                ]);
            }
        }
    }

    /**
     * Handle subscription halted event.
     */
    protected function handleSubscriptionHalted(array $payload): void
    {
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? null;

        if ($subId) {
            $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)->first();
            if ($subscription) {
                $subscription->update(['status' => 'past_due']);
                SubscriptionLog::create([
                    'subscription_id' => $subscription->id,
                    'event' => 'halted',
                    'details' => ['reason' => 'Webhook subscription.halted received'],
                ]);
            }
        }
    }

    /**
     * Handle failed recurring payments.
     */
    protected function handlePaymentFailed(array $payload): void
    {
        $payment = $payload['payment']['entity'] ?? [];
        $subId = $payment['subscription_id'] ?? null;

        if (! $subId) {
            return;
        }

        $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)->first();
        if ($subscription) {
            $subscription->increment('payment_failures_count');
            $status = $subscription->payment_failures_count >= 3 ? 'past_due' : 'active';
            $subscription->update(['status' => $status]);

            SubscriptionLog::create([
                'subscription_id' => $subscription->id,
                'event' => 'payment_failed',
                'amount' => isset($payment['amount']) ? ((float) $payment['amount'] / 100) : 0,
                'payment_id' => $payment['id'] ?? null,
                'details' => ['error_code' => $payment['error_code'] ?? null, 'error_description' => $payment['error_description'] ?? null],
            ]);
        }
    }
}
