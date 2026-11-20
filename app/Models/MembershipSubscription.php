<?php

namespace App\Models;

use App\Services\GstService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_id',
        'product_id',
        'variant_id',
        'status',
        'billing_interval_days',
        'billing_amount',
        'next_billing_date',
        'last_billed_at',
        'razorpay_subscription_id',
        'razorpay_plan_id',
        'razorpay_customer_id',
        'payment_failures_count',
        'shipping_address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'next_billing_date' => 'datetime',
            'last_billed_at' => 'datetime',
            'billing_amount' => 'decimal:2',
            'billing_interval_days' => 'integer',
            'payment_failures_count' => 'integer',
            'shipping_address' => 'array',
        ];
    }

    /**
     * Resolve monthly billing details:
     * Auto-debit amount every month = Product Price + Delivery Charge.
     *
     * @param  Product|null  $specificProduct
     * @return array{product: ?Product, product_price: float, delivery_charge: float, total_monthly_charge: float}
     */
    public static function getMonthlyBillingDetails(?Product $specificProduct = null): array
    {
        $product = $specificProduct
            ?? Product::where('is_active', true)->where('is_member_free_eligible', true)->first()
            ?? Product::where('is_active', true)->where('is_gangajal', true)->where('volume_ml', 500)->first()
            ?? Product::where('is_active', true)->where('is_gangajal', true)->first()
            ?? Product::where('is_active', true)->first();

        $productPrice = 0.0;
        if ($product) {
            $productPrice = (float) (
                ($product->member_price !== null && (float) $product->member_price > 0)
                    ? $product->member_price
                    : $product->price
            );
        }

        $deliveryCharge = (float) SiteSetting::get('shipping_charge', 149);
        $totalMonthly = round($productPrice + $deliveryCharge, 2);

        return [
            'product' => $product,
            'product_price' => $productPrice,
            'delivery_charge' => $deliveryCharge,
            'total_monthly_charge' => $totalMonthly,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SubscriptionLog::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
