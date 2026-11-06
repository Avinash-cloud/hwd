<?php

namespace App\Models;

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
