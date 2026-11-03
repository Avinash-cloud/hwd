<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get all memberships for the user.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get current active membership for the user.
     */
    public function activeMembership(): HasOne
    {
        return $this->hasOne(Membership::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latestOfMany();
    }

    /**
     * Determine if the user has an active 5-year membership.
     */
    public function hasActiveMembership(): bool
    {
        return $this->activeMembership()->exists();
    }

    /**
     * Determine if the user has already claimed their 1 free monthly member product this calendar month.
     */
    public function hasClaimedMonthlyFreeProduct(?CarbonInterface $date = null): bool
    {
        $target = $date ?? now();
        $start = $target->copy()->startOfMonth();
        $end = $target->copy()->endOfMonth();

        return $this->orders()
            ->where('order_status', '!=', 'cancelled')
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('items', function ($query): void {
                $query->where('is_free_monthly_bottle', true);
            })
            ->exists();
    }

    /**
     * Get user addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get default shipping address.
     */
    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)
            ->where('is_default', true)
            ->latestOfMany();
    }

    /**
     * Get user orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    /**
     * Get user reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Check if user is a verified purchaser of a product.
     */
    public function hasPurchasedProduct(Product|int $product): bool
    {
        $productId = $product instanceof Product ? $product->id : (int) $product;

        return $this->orders()
            ->where('order_status', '!=', 'cancelled')
            ->whereHas('items', function ($query) use ($productId): void {
                $query->where('product_id', $productId);
            })
            ->exists();
    }
}
