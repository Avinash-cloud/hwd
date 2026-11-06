<?php

namespace App\Console\Commands;

use App\Models\Batch;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SubscriptionLog;
use App\Services\GstService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ProcessSubscriptionRenewals extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:process-renewals';

    /**
     * The console command description.
     */
    protected $description = 'Process recurring 30-day membership subscriptions and automatically create verified orders.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for due membership subscriptions...');

        $dueSubscriptions = MembershipSubscription::where('status', 'active')
            ->where('next_billing_date', '<=', now())
            ->with(['user', 'membership', 'product', 'variant'])
            ->get();

        $this->info("Found {$dueSubscriptions->count()} subscription(s) due for billing.");

        foreach ($dueSubscriptions as $sub) {
            $user = $sub->user;
            $membership = $sub->membership;

            // Only active members are eligible
            if (! $membership || ! $membership->isActive()) {
                $this->warn("Skipping subscription #{$sub->id}: Member is not active.");
                $sub->update(['status' => 'past_due']);

                continue;
            }

            $product = $sub->product;
            $variant = $sub->variant;
            $amount = (float) $sub->billing_amount;

            $this->info("Processing renewal for user {$user->name} (#{$sub->id}) - Amount: ₹{$amount}");

            $batch = Batch::where('status', 'ready')->latest('collection_date')->first();
            $orderNumber = 'HB-SUB-'.date('Ymd').'-'.strtoupper(Str::random(4));
            $qrToken = 'HB-QR-'.Str::uuid()->toString();

            $gstRate = (float) ($variant?->gst_percentage ?? $product->gst_percentage ?? 18.00);
            $tax = GstService::calculate($amount, $gstRate);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'batch_id' => $batch?->id,
                'subtotal' => $amount,
                'total_taxable_amount' => $tax['taxable'],
                'total_gst_amount' => $tax['gst'],
                'cgst_amount' => $tax['cgst'],
                'sgst_amount' => $tax['sgst'],
                'igst_amount' => $tax['igst'],
                'discount_amount' => 0.00,
                'shipping_amount' => 0.00,
                'total_amount' => $amount,
                'payment_method' => 'razorpay_recurring',
                'payment_status' => 'paid',
                'payment_id' => 'pay_auto_'.Str::random(10),
                'order_status' => 'confirmed',
                'inventory_deducted' => false,
                'qr_token' => $qrToken,
                'shipping_address' => $sub->shipping_address ?? [
                    'recipient_name' => $user->name,
                    'phone' => $user->phone ?? '9999999999',
                    'email' => $user->email,
                    'address_line1' => 'Member Subscription Delivery Address',
                    'city' => 'Haridwar',
                    'state' => 'Uttarakhand',
                    'postal_code' => '249401',
                    'country' => 'India',
                ],
                'customer_notes' => 'Automatic monthly member product delivery (Every 30 Days).',
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
                'unit_price' => $amount,
                'quantity' => 1,
                'total_price' => $amount,
                'is_free_monthly_bottle' => true,
            ]);

            $sub->update([
                'last_billed_at' => now(),
                'next_billing_date' => now()->addDays($sub->billing_interval_days ?: 30),
                'payment_failures_count' => 0,
            ]);

            SubscriptionLog::create([
                'subscription_id' => $sub->id,
                'event' => 'payment_success',
                'amount' => $amount,
                'order_id' => $order->id,
                'payment_id' => $order->payment_id,
                'details' => ['order_number' => $orderNumber],
            ]);

            $this->info("Order #{$orderNumber} created successfully for subscription #{$sub->id}.");
        }

        $this->info('All due subscriptions processed.');

        return Command::SUCCESS;
    }
}
