<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'compare_price',
        'member_price',
        'stock',
        'volume_ml',
        'hsn_code',
        'gst_percentage',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'member_price' => 'decimal:2',
            'gst_percentage' => 'decimal:2',
            'stock' => 'integer',
            'volume_ml' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get taxable amount and GST amount calculated from inclusive selling price.
     */
    public function getTaxBreakupAttribute(): array
    {
        $price = (float) $this->price;
        $gstRate = (float) ($this->gst_percentage ?: 18.00);

        $taxableValue = round($price / (1 + ($gstRate / 100)), 2);
        $gstAmount = round($price - $taxableValue, 2);

        return [
            'price_inclusive' => $price,
            'taxable_value' => $taxableValue,
            'gst_rate' => $gstRate,
            'gst_amount' => $gstAmount,
            'cgst' => round($gstAmount / 2, 2),
            'sgst' => round($gstAmount / 2, 2),
            'igst' => $gstAmount,
        ];
    }
}
