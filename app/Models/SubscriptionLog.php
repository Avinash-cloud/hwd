<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'subscription_id',
        'event',
        'amount',
        'order_id',
        'payment_id',
        'details',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'details' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(MembershipSubscription::class, 'subscription_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
