<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

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
        'address',
        'city',
        'state',
        'pincode',
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

    public static function hasTable(): bool
    {
        static $has = null;
        if ($has === null) {
            try {
                $has = Schema::hasTable('bulk_order_inquiries');
            } catch (\Throwable) {
                $has = false;
            }
        }

        return $has;
    }

    /**
     * Get the associated product if it exists.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
