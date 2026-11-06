<?php

namespace App\Models;

use App\Services\GstService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'fee_paid',
        'validity_years',
        'starts_at',
        'expires_at',
        'payment_method',
        'payment_id',
        'invoice_number',
        'terms_accepted',
        'terms_accepted_at',
        'terms_ip_address',
        'terms_user_agent',
        'terms_version',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'fee_paid' => 'decimal:2',
            'validity_years' => 'integer',
            'terms_accepted' => 'boolean',
            'terms_accepted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at && $this->expires_at->isFuture();
    }

    /**
     * Get tax breakup for membership fee.
     * Service SAC Code 9995 (Membership organizations services), standard 18% GST inclusive.
     */
    public function getTaxBreakupAttribute(): array
    {
        return GstService::calculate((float) $this->fee_paid, 18.00);
    }

    /**
     * Ensure invoice number is generated if missing.
     */
    public function getResolvedInvoiceNumberAttribute(): string
    {
        if ($this->invoice_number) {
            return $this->invoice_number;
        }

        $generated = 'HB-MEM-'.date('Y', strtotime((string) $this->created_at)).'-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
        $this->updateQuietly(['invoice_number' => $generated]);

        return $generated;
    }
}
