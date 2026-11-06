<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'google_id',
        'avatar',
        'phone',
        'password',
        'is_admin',
        'is_active',
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
            'is_active' => 'boolean',
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
     * User administration roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * User recurring subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class)->latest();
    }

    /**
     * Determine if user has a specific role.
     */
    public function hasRole(string|array $roles): bool
    {
        if (! $this->is_admin) {
            return false;
        }

        $roleList = is_array($roles) ? $roles : [$roles];

        // If user is super_admin or no roles table seeded, default admin has root access
        if ($this->roles()->whereIn('slug', $roleList)->exists()) {
            return true;
        }

        if ($this->roles()->where('slug', 'super_admin')->exists() || $this->roles()->count() === 0) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user has a specific admin permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (! $this->is_admin || ! $this->is_active) {
            return false;
        }

        // Default admin with no explicit roles assigned has full access
        if ($this->roles()->count() === 0 || $this->roles()->where('slug', 'super_admin')->exists()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permission): void {
            $query->where('slug', $permission);
        })->exists();
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
