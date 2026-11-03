<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'batch_id',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'coupon_discount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_id',
        'order_status',
        'inventory_deducted',
        'tracking_number',
        'courier_name',
        'qr_token',
        'shipping_address',
        'billing_address',
        'customer_notes',
        'admin_notes',
        'dispatched_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'inventory_deducted' => 'boolean',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'dispatched_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Determine if order contains a free monthly member product.
     */
    public function hasFreeMonthlyItem(): bool
    {
        if ($this->relationLoaded('items')) {
            return $this->items->contains('is_free_monthly_bottle', true);
        }

        return $this->items()->where('is_free_monthly_bottle', true)->exists();
    }

    /**
     * Get the verification URL for the order's QR code.
     */
    public function getVerificationUrlAttribute(): string
    {
        return url('/verify/'.$this->qr_token);
    }
}
