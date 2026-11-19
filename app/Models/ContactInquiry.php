<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'amount',
        'payment_method',
        'payment_status',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'message',
        'status',
        'admin_notes',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'replied_at' => 'datetime',
        ];
    }
}
