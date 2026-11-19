<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class WaterCollectionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity_litres',
        'unit',
        'container_type',
        'price',
        'original_price',
        'description',
        'badge',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'quantity_litres' => 'decimal:2',
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public static function hasTable(): bool
    {
        static $has = null;
        if ($has === null) {
            try {
                $has = Schema::hasTable('water_collection_packages');
            } catch (\Throwable) {
                $has = false;
            }
        }

        return $has;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('quantity_litres');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(WaterCollectionLead::class, 'package_id');
    }

    /**
     * Seeds initial Kumbh water packages if none exist yet.
     */
    public static function seedDefaultPackagesIfEmpty(): void
    {
        if (! static::hasTable() || static::count() > 0) {
            return;
        }

        $defaults = [
            [
                'name' => '1 Litre Sacred Kumbh Ganga Jal',
                'quantity_litres' => 1.00,
                'unit' => 'Litre',
                'container_type' => 'Auspicious Sealed Bottle',
                'price' => 151.00,
                'original_price' => 250.00,
                'description' => 'Direct holy Haridwar Kumbh collection with sacred Bilva Patra & Gangaji Prasad.',
                'badge' => 'Devotee Favorite',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => '2 Litres Sacred Family Snan Pack',
                'quantity_litres' => 2.00,
                'unit' => 'Litres',
                'container_type' => 'Auspicious Sealed Twin Canister',
                'price' => 251.00,
                'original_price' => 450.00,
                'description' => 'Ideal for household morning snan, Shiva abhishek, and auspicious family rituals.',
                'badge' => 'Best Value',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => '5 Litres Maha Kumbh Pavitra Canister',
                'quantity_litres' => 5.00,
                'unit' => 'Litres',
                'container_type' => 'Heavy-Duty Food Grade Canister',
                'price' => 501.00,
                'original_price' => 850.00,
                'description' => 'Collected during prime auspicious Kumbh snan muhurat. Sealed with Vedic certificate.',
                'badge' => 'Most Popular',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => '10 Litres Maha Kumbh Akhand Canister',
                'quantity_litres' => 10.00,
                'unit' => 'Litres',
                'container_type' => 'Auspicious Heavy Canister with Sacred Kalash Emblem',
                'price' => 951.00,
                'original_price' => 1500.00,
                'description' => 'For temples, extended families, Havans, and lifelong sacred home storage.',
                'badge' => 'Vedic Sacred Choice',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $item) {
            static::create($item);
        }
    }
}
