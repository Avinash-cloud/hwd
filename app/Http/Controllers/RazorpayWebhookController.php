<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SubscriptionLog;
use App\Services\GstService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayWebhookController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpayService
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
                $this->handleSubscriptionCharged($payloadData);
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
     */
    protected function handleSubscriptionCharged(array $payload): void
    {
        $payment = $payload['payment']['entity'] ?? [];
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? null;

        if (! $subId) {
            return;
        }

        /** @var MembershipSubscription|null $subscription */
        $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)
            ->with(['user', 'product', 'variant'])
            ->first();

        if (! $subscription || ! $subscription->user) {
            Log::info('No local subscription found for Razorpay Sub: '.$subId);

            return;
        }

        $user = $subscription->user;
        $product = $subscription->product;
        $variant = $subscription->variant;

        $amountPaid = isset($payment['amount']) ? ((float) $payment['amount'] / 100) : (float) $subscription->billing_amount;
        $paymentId = $payment['id'] ?? ('pay_sub_'.Str::random(10));

        // Create Automated Order
        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();
        $orderNumber = 'HB-SUB-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        $gstRate = (float) ($variant?->gst_percentage ?? $product->gst_percentage ?? 18.00);
        $tax = GstService::calculate($amountPaid, $gstRate);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'batch_id' => $batch?->id,
            'subtotal' => $amountPaid,
            'total_taxable_amount' => $tax['taxable'],
            'total_gst_amount' => $tax['gst'],
            'cgst_amount' => $tax['cgst'],
            'sgst_amount' => $tax['sgst'],
            'igst_amount' => $tax['igst'],
            'discount_amount' => 0.00,
            'shipping_amount' => 0.00,
            'total_amount' => $amountPaid,
            'payment_method' => 'razorpay',
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
            'customer_notes' => 'Automated monthly member subscription fulfillment (Plan: '.$subId.').',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'product_name' => $product->name,
            'variant_name' => $variant?->name,
            'hsn_code' => $variant?->hsn_code ?? $product->hsn_code ?? '22019010',
            'gst_percentage' => $gstRate,
            'taxable_amount' => $tax['taxable'],
            'gst_amount' => $tax['gst'],
            'unit_price' => $amountPaid,
            'quantity' => 1,
            'total_price' => $amountPaid,
            'is_free_monthly_bottle' => true,
        ]);

        // Update Subscription status
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
