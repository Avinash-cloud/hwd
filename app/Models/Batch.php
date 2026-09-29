<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'sourcing_ghat',
        'collection_date',
        'packaging_date',
        'description',
        'purity_notes',
        'lab_certificate_path',
        'video_url',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'collection_date' => 'date',
            'packaging_date' => 'date',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
