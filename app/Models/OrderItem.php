<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'hsn_code',
        'gst_percentage',
        'taxable_amount',
        'gst_amount',
        'unit_price',
        'quantity',
        'total_price',
        'is_free_monthly_bottle',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'taxable_amount' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'gst_percentage' => 'decimal:2',
            'quantity' => 'integer',
            'is_free_monthly_bottle' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
