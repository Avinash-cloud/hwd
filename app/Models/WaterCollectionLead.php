<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WaterCollectionLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_number',
        'name',
        'mobile',
        'email',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_pincode',
        'hotel_or_dharamshala',
        'pickup_room_or_landmark',
        'package_id',
        'package_name',
        'water_quantity',
        'quantity_count',
        'unit_price',
        'total_price',
        'payment_method',
        'payment_status',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'pickup_date',
        'notes',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'quantity_count' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public static function hasTable(): bool
    {
        static $has = null;
        if ($has === null) {
            try {
                $has = Schema::hasTable('water_collection_leads');
            } catch (\Throwable) {
                $has = false;
            }
        }

        return $has;
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(WaterCollectionPackage::class, 'package_id');
    }

    public static function generateLeadNumber(): string
    {
        do {
            $number = 'KMB-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('lead_number', $number)->exists());

        return $number;
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'contacted' => 'bg-blue-100 text-blue-800 border-blue-300',
            'scheduled' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'collected' => 'bg-purple-100 text-purple-800 border-purple-300',
            'dispatched' => 'bg-orange-100 text-orange-800 border-orange-300',
            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getPaymentStatusBadgeClassesAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'failed' => 'bg-rose-100 text-rose-800 border-rose-300',
            'cod' => 'bg-purple-100 text-purple-800 border-purple-300',
            default => 'bg-amber-100 text-amber-800 border-amber-300',
        };
    }
}
