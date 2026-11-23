<?php

namespace App\Http\Controllers;

use App\Models\MembershipSubscription;
use App\Models\SubscriptionLog;
use App\Services\RazorpayService;
use App\Services\SubscriptionOrderService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpayService,
        protected SubscriptionOrderService $orderService
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

        // Auto-renew 5-year membership tenure if expired or within 30 days of expiry
        $this->autoRenewMembershipIfNeeded($subscription);

        // Determine billing period from payment timestamp
        $paymentTimestamp = $payment['created_at'] ?? now()->timestamp;
        $billingPeriod = Carbon::createFromTimestamp($paymentTimestamp)->format('Y-m');

        $amountPaid = isset($payment['amount']) ? ((float) $payment['amount'] / 100) : (float) $subscription->billing_amount;
        $paymentId = $payment['id'] ?? null;

        if (! $paymentId || $amountPaid <= 0) {
            Log::warning("Webhook subscription.charged with no valid payment for sub: {$subId}");
            $this->orderService->alertAdmin(
                'Webhook Charged Event With Invalid Payment',
                $subscription,
                null,
                ['razorpay_sub_id' => $subId, 'payment' => $payment]
            );

            return;
        }

        $order = $this->orderService->createOrderForBillingCycle(
            subscription: $subscription,
            billingPeriod: $billingPeriod,
            amountPaid: $amountPaid,
            paymentId: $paymentId,
            paymentMethod: 'razorpay_subscription'
        );

        if ($order) {
            $this->orderService->bookShipmentAndNotify($order, $subscription);
        }
    }

    /**
     * Handle subscription cancellation event.
     */
    protected function handleSubscriptionCancelled(array $payload): void
    {
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? null;

        if (! $subId) {
            return;
        }

        $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)->first();

        if ($subscription) {
            $subscription->update(['status' => 'cancelled']);

            SubscriptionLog::create([
                'subscription_id' => $subscription->id,
                'event' => 'cancelled',
                'details' => ['reason' => 'Webhook subscription.cancelled received'],
            ]);

            $this->orderService->alertAdmin(
                'Subscription Cancelled',
                $subscription,
                null,
                ['razorpay_sub_id' => $subId, 'reason' => 'Razorpay subscription.cancelled webhook received']
            );
        }
    }

    /**
     * Handle subscription halted event.
     */
    protected function handleSubscriptionHalted(array $payload): void
    {
        $subscriptionEntity = $payload['subscription']['entity'] ?? [];
        $subId = $subscriptionEntity['id'] ?? null;

        if (! $subId) {
            return;
        }

        $subscription = MembershipSubscription::where('razorpay_subscription_id', $subId)->first();

        if ($subscription) {
            $subscription->update(['status' => 'past_due']);

            SubscriptionLog::create([
                'subscription_id' => $subscription->id,
                'event' => 'halted',
                'details' => ['reason' => 'Webhook subscription.halted received'],
            ]);

            $this->orderService->alertAdmin(
                'Subscription Halted — Payments Exhausted',
                $subscription,
                null,
                ['razorpay_sub_id' => $subId, 'reason' => 'Razorpay subscription.halted webhook received — retries exhausted']
            );
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
                'details' => [
                    'error_code' => $payment['error_code'] ?? null,
                    'error_description' => $payment['error_description'] ?? null,
                    'failure_count' => $subscription->payment_failures_count,
                ],
            ]);

            $this->orderService->alertAdmin(
                "Payment Failed (attempt #{$subscription->payment_failures_count})",
                $subscription,
                null,
                [
                    'error_code' => $payment['error_code'] ?? 'unknown',
                    'error_description' => $payment['error_description'] ?? 'No description',
                    'failure_count' => $subscription->payment_failures_count,
                    'new_status' => $status,
                ]
            );
        }
    }

    /**
     * Auto-renew 5-year membership tenure if expired or within 30 days of expiry.
     */
    protected function autoRenewMembershipIfNeeded(MembershipSubscription $subscription): void
    {
        $membership = $subscription->membership;

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
}
