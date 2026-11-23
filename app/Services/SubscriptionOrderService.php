<?php

namespace App\Services;

use App\Mail\AdminSubscriptionAlertMail;
use App\Models\Batch;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SubscriptionLog;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SubscriptionOrderService
{
    public function __construct(
        protected NimbusPostService $nimbusPostService,
        protected OrderMailService $orderMailService
    ) {}

    /**
     * Create a subscription order for a specific billing cycle with dedup guard.
     *
     * Returns the created Order, or null if one already exists for this cycle.
     *
     * @param  array{product_price: float, delivery_charge: float}  $priceBreakdown
     */
    public function createOrderForBillingCycle(
        MembershipSubscription $subscription,
        string $billingPeriod,
        float $amountPaid,
        string $paymentId,
        string $paymentMethod = 'razorpay_subscription',
        array $priceBreakdown = []
    ): ?Order {
        $user = $subscription->user;
        if (! $user) {
            Log::warning("SubscriptionOrderService: No user for subscription #{$subscription->id}");

            return null;
        }

        // Dedup check: skip if order already exists for this subscription + billing period
        $existingOrder = Order::where('membership_subscription_id', $subscription->id)
            ->where('billing_period', $billingPeriod)
            ->first();

        if ($existingOrder) {
            Log::info("SubscriptionOrderService: Order #{$existingOrder->order_number} already exists for sub #{$subscription->id} period {$billingPeriod}, skipping.");

            return null;
        }

        $product = $subscription->product
            ?? Product::where('is_active', true)->where('is_gangajal', true)->where('volume_ml', 500)->first()
            ?? Product::where('is_active', true)->first();
        $variant = $subscription->variant;

        $billing = MembershipSubscription::getMonthlyBillingDetails($product);
        $productPrice = $priceBreakdown['product_price'] ?? $billing['product_price'];
        $deliveryCharge = $priceBreakdown['delivery_charge'] ?? $billing['delivery_charge'];

        // If actual amount paid differs from calculated breakdown, reconcile
        if ($amountPaid > 0 && $amountPaid != round($productPrice + $deliveryCharge, 2)) {
            if ($amountPaid >= $deliveryCharge) {
                $productPrice = round($amountPaid - $deliveryCharge, 2);
            } else {
                $deliveryCharge = $amountPaid;
                $productPrice = 0.00;
            }
        }

        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();
        $orderNumber = 'HB-SUB-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        $gstRate = (float) ($variant?->gst_percentage ?? $product?->gst_percentage ?? 18.00);
        $productTax = GstService::calculate($productPrice, $gstRate);
        $shippingTax = GstService::calculate($deliveryCharge, 18.00);

        $totalTaxable = round($productTax['taxable'] + $shippingTax['taxable'], 2);
        $totalGst = round($productTax['gst'] + $shippingTax['gst'], 2);
        $halfGst = round($totalGst / 2, 2);

        try {
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'batch_id' => $batch?->id,
                'membership_subscription_id' => $subscription->id,
                'billing_period' => $billingPeriod,
                'subtotal' => $productPrice,
                'total_taxable_amount' => $totalTaxable,
                'total_gst_amount' => $totalGst,
                'cgst_amount' => $halfGst,
                'sgst_amount' => $halfGst,
                'igst_amount' => $totalGst,
                'discount_amount' => 0.00,
                'shipping_amount' => $deliveryCharge,
                'total_amount' => $amountPaid,
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'payment_id' => $paymentId,
                'order_status' => 'confirmed',
                'inventory_deducted' => false,
                'qr_token' => $qrToken,
                'shipping_address' => $subscription->shipping_address ?? [
                    'recipient_name' => $user->name,
                    'phone' => $user->phone ?? '9999999999',
                    'email' => $user->email,
                    'address_line1' => 'Member Subscription Delivery Address',
                    'city' => 'Haridwar',
                    'state' => 'Uttarakhand',
                    'postal_code' => '249401',
                    'country' => 'India',
                ],
                'customer_notes' => "Automated monthly member product delivery: Product (₹{$productPrice}) + Delivery (₹{$deliveryCharge}) for {$billingPeriod}.",
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

            // Advance subscription billing cycle
            $subscription->update([
                'status' => 'active',
                'last_billed_at' => now(),
                'next_billing_date' => now()->addDays($subscription->billing_interval_days ?: 30),
                'payment_failures_count' => 0,
            ]);

            SubscriptionLog::create([
                'subscription_id' => $subscription->id,
                'event' => 'payment_success',
                'amount' => $amountPaid,
                'order_id' => $order->id,
                'payment_id' => $paymentId,
                'details' => [
                    'order_number' => $orderNumber,
                    'billing_period' => $billingPeriod,
                    'source' => $paymentMethod,
                ],
            ]);

            Log::info("SubscriptionOrderService: Order #{$orderNumber} created for sub #{$subscription->id} period {$billingPeriod}");

            return $order;
        } catch (UniqueConstraintViolationException) {
            // Race condition: the other path already created the order
            Log::info("SubscriptionOrderService: Race-condition dedup caught for sub #{$subscription->id} period {$billingPeriod}");

            return null;
        }
    }

    /**
     * Book NimbusPost shipment and send customer confirmation email.
     * Alerts admin on any failure.
     */
    public function bookShipmentAndNotify(Order $order, ?MembershipSubscription $subscription = null): void
    {
        // Book NimbusPost shipment
        if (config('services.nimbuspost.auto_create_shipment', true)) {
            try {
                $result = $this->nimbusPostService->createShipment($order);
                if (! $result['success']) {
                    $this->alertAdmin(
                        'NimbusPost Shipment Booking Failed',
                        $subscription,
                        $order,
                        ['error' => $result['error'] ?? 'Unknown NimbusPost error', 'order_number' => $order->order_number]
                    );
                }
            } catch (\Throwable $e) {
                Log::error("SubscriptionOrderService: NimbusPost exception for order #{$order->order_number}: ".$e->getMessage());
                $this->alertAdmin(
                    'NimbusPost Shipment Exception',
                    $subscription,
                    $order,
                    ['exception' => $e->getMessage()]
                );
            }
        }

        // Send customer confirmation email
        try {
            $sent = $this->orderMailService->sendOrderPlaced($order);
            if (! $sent) {
                $this->alertAdmin(
                    'Customer Confirmation Email Not Sent',
                    $subscription,
                    $order,
                    ['reason' => 'No valid recipient email or send returned false']
                );
            }
        } catch (\Throwable $e) {
            Log::error("SubscriptionOrderService: Email exception for order #{$order->order_number}: ".$e->getMessage());
            $this->alertAdmin(
                'Customer Confirmation Email Failed',
                $subscription,
                $order,
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Send an alert email to the admin about a subscription issue.
     *
     * @param  array<string, mixed>  $details
     */
    public function alertAdmin(
        string $subject,
        ?MembershipSubscription $subscription = null,
        ?Order $order = null,
        array $details = []
    ): void {
        $adminEmail = config('services.admin.email');

        if (! $adminEmail) {
            Log::warning("SubscriptionOrderService: No admin email configured — alert not sent: {$subject}");

            return;
        }

        try {
            Mail::to($adminEmail)->send(new AdminSubscriptionAlertMail(
                alertSubject: $subject,
                subscription: $subscription,
                order: $order,
                details: $details
            ));
            Log::info("SubscriptionOrderService: Admin alert sent — {$subject}");
        } catch (\Throwable $e) {
            Log::error("SubscriptionOrderService: Failed to send admin alert '{$subject}': ".$e->getMessage());
        }
    }
}
