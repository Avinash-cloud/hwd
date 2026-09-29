<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'batch_id',
        'name',
        'slug',
        'short_description',
        'description',
        'price',
        'member_price',
        'is_gangajal',
        'volume_ml',
        'stock',
        'sku',
        'image',
        'gallery',
        'video_url',
        'purity_details',
        'specifications',
        'benefits',
        'is_active',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'member_price' => 'decimal:2',
            'is_gangajal' => 'boolean',
            'volume_ml' => 'integer',
            'stock' => 'integer',
            'gallery' => 'array',
            'specifications' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get effective price for active member vs non-member.
     */
    public function getPriceForUser(?User $user = null): float
    {
        if ($user && $user->hasActiveMembership()) {
            return (float) ($this->member_price ?? $this->price);
        }

        return (float) $this->price;
    }
}
