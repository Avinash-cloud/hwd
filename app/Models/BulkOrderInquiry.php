<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkOrderInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'name',
        'email',
        'phone',
        'quantity',
        'message',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    /**
     * Get the associated product if it exists.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
