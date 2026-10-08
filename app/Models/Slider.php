<?php

namespace App\Models;

use Database\Factories\SliderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    /** @use HasFactory<SliderFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'button_text',
        'button_link',
        'secondary_button_text',
        'secondary_button_link',
        'badge_color',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get resolved image URL whether it is a local storage path, asset, or full URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/haridwar-panorama-clocktower.jpg');
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
}
