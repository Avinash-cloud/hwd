<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'min_spend' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Check if coupon is currently valid for a given order subtotal.
     *
     * @return array{valid: bool, message: string}
     */
    public function validateForSubtotal(float $subtotal): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is not active.'];
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'This coupon is not yet valid.'];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        if ($this->min_spend !== null && $subtotal < (float) $this->min_spend) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount of ₹'.number_format((float) $this->min_spend, 2).' required for this coupon.',
            ];
        }

        return ['valid' => true, 'message' => 'Coupon is valid.'];
    }

    /**
     * Calculate discount amount for a given subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * (float) $this->discount_value) / 100;
        } else {
            $discount = (float) $this->discount_value;
        }

        if ($this->max_discount !== null && $discount > (float) $this->max_discount) {
            $discount = (float) $this->max_discount;
        }

        return min($discount, $subtotal);
    }
}
