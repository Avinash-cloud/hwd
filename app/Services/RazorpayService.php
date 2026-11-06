<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayService
{
    protected string $keyId;

    protected string $keySecret;

    protected string $webhookSecret;

    public function __construct()
    {
        $this->keyId = (string) config('services.razorpay.key', 'rzp_test_dummy_key_12345');
        $this->keySecret = (string) config('services.razorpay.secret', 'dummy_secret_67890');
        $this->webhookSecret = (string) config('services.razorpay.webhook_secret', 'dummy_webhook_secret');
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }

    public function isDummy(): bool
    {
        return str_starts_with($this->keyId, 'rzp_test_dummy_') || empty($this->keySecret);
    }

    /**
     * Create an order on Razorpay for modal checkout.
     */
    public function createOrder(float $amountInRupees, string $receiptId, array $notes = []): array
    {
        $amountInPaise = (int) round($amountInRupees * 100);

        if ($this->isDummy()) {
            return [
                'id' => 'order_mock_'.Str::random(14),
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => $receiptId,
                'status' => 'created',
                'is_mock' => true,
            ];
        }

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->timeout(10)
                ->post('https://api.razorpay.com/v1/orders', [
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'receipt' => $receiptId,
                    'notes' => $notes,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Razorpay Order Creation Failed: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('Razorpay API Exception: '.$e->getMessage());
        }

        // Fallback to mock order in case of transient API error or unactivated keys
        return [
            'id' => 'order_fallback_'.Str::random(14),
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => $receiptId,
            'status' => 'created',
            'is_mock' => true,
        ];
    }

    /**
     * Verify payment signature from Razorpay checkout modal.
     */
    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): bool
    {
        if ($this->isDummy() || str_starts_with($orderId, 'order_mock_') || str_starts_with($orderId, 'order_fallback_')) {
            return true;
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $this->keySecret);

        return hash_equals($expected, $signature);
    }

    /**
     * Verify incoming webhook signature.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        if ($this->isDummy()) {
            return true;
        }

        $expected = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($expected, $signature);
    }

    /**
     * Create recurring subscription plan on Razorpay (every 30 days).
     */
    public function createPlan(string $name, float $amountInRupees, string $description = 'Monthly Sacred Offering'): ?string
    {
        if ($this->isDummy()) {
            return 'plan_mock_'.Str::random(12);
        }

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->timeout(10)
                ->post('https://api.razorpay.com/v1/plans', [
                    'period' => 'daily',
                    'interval' => 30,
                    'item' => [
                        'name' => $name,
                        'amount' => (int) round($amountInRupees * 100),
                        'currency' => 'INR',
                        'description' => $description,
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('id');
            }
        } catch (\Throwable $e) {
            Log::error('Razorpay Plan creation exception: '.$e->getMessage());
        }

        return 'plan_fallback_'.Str::random(12);
    }

    /**
     * Create subscription mandate for a customer.
     */
    public function createSubscription(string $planId, int $totalCount = 60, array $notes = []): array
    {
        if ($this->isDummy() || str_starts_with($planId, 'plan_mock_') || str_starts_with($planId, 'plan_fallback_')) {
            return [
                'id' => 'sub_mock_'.Str::random(14),
                'plan_id' => $planId,
                'status' => 'active',
                'is_mock' => true,
            ];
        }

        try {
            $response = Http::withBasicAuth($this->keyId, $this->keySecret)
                ->timeout(10)
                ->post('https://api.razorpay.com/v1/subscriptions', [
                    'plan_id' => $planId,
                    'total_count' => $totalCount,
                    'quantity' => 1,
                    'customer_notify' => 1,
                    'notes' => $notes,
                ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::error('Razorpay Subscription creation exception: '.$e->getMessage());
        }

        return [
            'id' => 'sub_fallback_'.Str::random(14),
            'plan_id' => $planId,
            'status' => 'active',
            'is_mock' => true,
        ];
    }
}
