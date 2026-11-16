<?php

namespace App\Models;

use App\Services\GstService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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
        'compare_price',
        'member_price',
        'rating',
        'reviews_count',
        'badge',
        'is_gangajal',
        'is_member_free_eligible',
        'volume_ml',
        'stock',
        'sku',
        'hsn_code',
        'gst_percentage',
        'image',
        'gallery',
        'video_url',
        'purity_details',
        'specifications',
        'benefits',
        'is_active',
        'is_featured',
        'is_bestseller',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'member_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'reviews_count' => 'integer',
            'is_gangajal' => 'boolean',
            'is_member_free_eligible' => 'boolean',
            'volume_ml' => 'integer',
            'stock' => 'integer',
            'gallery' => 'array',
            'specifications' => 'array',
            'gst_percentage' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
        ];
    }

    public static function hasCategoryProductTable(): bool
    {
        static $hasTable = null;
        if ($hasTable === null) {
            try {
                $hasTable = Schema::hasTable('category_product');
            } catch (\Throwable) {
                $hasTable = false;
            }
        }

        return $hasTable;
    }

    protected static function booted(): void
    {
        static::saved(function (Product $product): void {
            if (static::hasCategoryProductTable() && $product->category_id && ! $product->categories()->where('categories.id', $product->category_id)->exists()) {
                $product->categories()->attach($product->category_id);
            }
        });
    }

    /**
     * Get discount percentage if compare_price is set and higher than price.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }

        return null;
    }

    /**
     * Get resolved image URL whether it is a local storage path, asset, or full URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/ganga-holy-waters.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset($this->image);
    }

    /**
     * Get resolved gallery URLs.
     *
     * @return array<int, string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        if (empty($this->gallery) || ! is_array($this->gallery)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($item) {
            if (! is_string($item) || trim($item) === '') {
                return null;
            }

            $item = trim($item);

            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                return $item;
            }

            if (Storage::disk('public')->exists($item)) {
                return Storage::disk('public')->url($item);
            }

            if (file_exists(public_path($item))) {
                return asset($item);
            }

            return asset($item);
        }, $this->gallery)));
    }

    /**
     * Get resolved video URL (direct link or storage URL).
     */
    public function getResolvedVideoUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (Storage::disk('public')->exists($url)) {
            return Storage::disk('public')->url($url);
        }

        if (file_exists(public_path($url))) {
            return asset($url);
        }

        return asset($url);
    }

    /**
     * Get YouTube/Vimeo embed URL or direct video URL.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        // YouTube: https://www.youtube.com/watch?v=XYZ, https://youtu.be/XYZ, etc.
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]+)/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1].'?autoplay=1&rel=0';
        }

        // Vimeo: https://vimeo.com/123456789
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[3].'?autoplay=1';
        }

        return $this->resolved_video_url;
    }

    /**
     * Check if product video is an embeddable iframe (YouTube or Vimeo).
     */
    public function getIsVideoEmbedAttribute(): bool
    {
        if (! $this->video_url) {
            return false;
        }

        return (bool) preg_match('/(youtube\.com|youtu\.be|vimeo\.com)/i', $this->video_url);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * All categories assigned to this product (multi-category).
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * Get array of all category IDs (combining pivot and primary category_id).
     *
     * @return array<int, int>
     */
    public function getCategoryIdsAttribute(): array
    {
        if (! static::hasCategoryProductTable()) {
            return $this->category_id ? [(int) $this->category_id] : [];
        }

        $ids = $this->relationLoaded('categories')
            ? $this->categories->pluck('id')->all()
            : $this->categories()->pluck('categories.id')->all();

        if ($this->category_id && ! in_array($this->category_id, $ids)) {
            $ids[] = (int) $this->category_id;
        }

        return array_values(array_unique($ids));
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    /**
     * Check if product is currently in stock.
     */
    public function isInStock(): bool
    {
        return (int) $this->stock > 0;
    }

    /**
     * Check if a given user can review this product (must be a verified purchaser).
     */
    public function canUserReview(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $user->hasPurchasedProduct($this);
    }

    /**
     * Retrieve the user's review for this product if one exists.
     */
    public function getUserReview(?User $user): ?Review
    {
        if (! $user) {
            return null;
        }

        return $this->reviews()->where('user_id', $user->id)->first();
    }

    /**
     * Recalculate average rating and reviews count from approved reviews.
     */
    public function recalculateRating(): void
    {
        $approved = $this->reviews()->where('status', 'approved');
        $count = $approved->count();

        if ($count > 0) {
            $avg = round($approved->avg('rating'), 1);
            $this->update([
                'rating' => $avg,
                'reviews_count' => $count,
            ]);
        }
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

    /**
     * Product size/option variants.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    /**
     * Active variants for customer facing pages.
     */
    public function activeVariants(): HasMany
    {
        return $this->variants()->where('is_active', true);
    }

    /**
     * Check if product has size/option variants.
     */
    public function getHasVariantsAttribute(): bool
    {
        return $this->variants()->exists();
    }

    /**
     * Tax breakup from inclusive selling price.
     */
    public function getTaxBreakupAttribute(): array
    {
        return GstService::calculate((float) $this->price, (float) ($this->gst_percentage ?: 18.00));
    }

    /**
     * Active variant context for listing display.
     */
    public ?ProductVariant $active_variant = null;

    /**
     * Clone product instance bound to a specific variant.
     */
    public function withVariant(ProductVariant $variant): static
    {
        $clone = clone $this;
        $clone->active_variant = $variant;

        return $clone;
    }

    /**
     * Display name (includes variant name if not already in product title).
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->active_variant) {
            $vName = trim($this->active_variant->name);
            if (! str_contains(strtolower($this->name), strtolower($vName))) {
                return "{$this->name} ({$vName})";
            }
        }

        return $this->name;
    }

    /**
     * Display price taking active variant into account.
     */
    public function getDisplayPriceAttribute(): float
    {
        if ($this->active_variant) {
            return (float) $this->active_variant->price;
        }

        return (float) $this->price;
    }

    /**
     * Display compare price taking active variant into account.
     */
    public function getDisplayComparePriceAttribute(): ?float
    {
        if ($this->active_variant) {
            return $this->active_variant->compare_price ? (float) $this->active_variant->compare_price : null;
        }

        return $this->compare_price ? (float) $this->compare_price : null;
    }

    /**
     * Display discount percentage taking active variant into account.
     */
    public function getDisplayDiscountAttribute(): ?int
    {
        $price = $this->display_price;
        $compare = $this->display_compare_price;

        if ($compare && $compare > $price) {
            return (int) round((($compare - $price) / $compare) * 100);
        }

        return null;
    }

    /**
     * Display stock taking active variant into account.
     */
    public function getDisplayStockAttribute(): int
    {
        if ($this->active_variant) {
            return (int) $this->active_variant->stock;
        }

        return (int) $this->stock;
    }

    /**
     * Display URL linking to single product page with active variant pre-selected.
     */
    public function getDisplayUrlAttribute(): string
    {
        $url = route('products.show', $this->slug);
        if ($this->active_variant) {
            $url .= '?variant='.$this->active_variant->id;
        }

        return $url;
    }

    /**
     * Check if item is in stock for display.
     */
    public function isDisplayInStock(): bool
    {
        if ($this->active_variant) {
            return $this->active_variant->is_active && $this->active_variant->stock > 0;
        }

        return $this->isInStock();
    }

    /**
     * Expand products that have active variants into distinct listings for archive & homepage streams.
     * Products without active variants are left as-is.
     *
     * @return Collection<int, static>
     */
    public static function expandVariantsForListing(iterable $products): Collection
    {
        $expanded = collect();

        foreach ($products as $product) {
            $variants = $product->relationLoaded('activeVariants')
                ? $product->activeVariants
                : ($product->relationLoaded('variants')
                    ? $product->variants->where('is_active', true)
                    : $product->activeVariants()->get());

            if ($variants && $variants->count() > 0) {
                foreach ($variants as $variant) {
                    $expanded->push($product->withVariant($variant));
                }
            } else {
                $expanded->push($product);
            }
        }

        return $expanded;
    }
}
