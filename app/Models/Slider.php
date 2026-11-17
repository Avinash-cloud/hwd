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
        'media_type',
        'image',
        'tablet_image',
        'mobile_image',
        'right_image',
        'video_url',
        'video_file',
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

    public function getIsVideoAttribute(): bool
    {
        return $this->media_type === 'video' || ! empty($this->video_file) || ! empty($this->video_url);
    }

    /**
     * Resolve desktop image URL.
     */
    public function getImageUrlAttribute(): string
    {
        return $this->resolvePath($this->image, asset('images/haridwar-panorama-clocktower.jpg'));
    }

    /**
     * Resolve tablet image URL (1024x600 fallback to desktop image).
     */
    public function getTabletImageUrlAttribute(): string
    {
        return $this->tablet_image ? $this->resolvePath($this->tablet_image) : $this->image_url;
    }

    /**
     * Resolve mobile image URL (750x1000 fallback to desktop image).
     */
    public function getMobileImageUrlAttribute(): string
    {
        return $this->mobile_image ? $this->resolvePath($this->mobile_image) : $this->image_url;
    }

    /**
     * Resolve right-side showcase image URL.
     */
    public function getRightImageUrlAttribute(): ?string
    {
        $path = $this->attributes['right_image'] ?? null;

        return $path ? $this->resolvePath($path) : null;
    }

    /**
     * Check if slider video is an embeddable iframe (YouTube or Vimeo).
     */
    public function getIsVideoEmbedAttribute(): bool
    {
        if (! $this->video_url) {
            return false;
        }

        return (bool) preg_match('/(youtube\.com|youtu\.be|vimeo\.com)/i', $this->video_url);
    }

    /**
     * Get YouTube/Vimeo embed URL with background autoplay, mute, and loop parameters.
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        // YouTube: https://www.youtube.com/watch?v=XYZ, https://youtu.be/XYZ, etc.
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]+)/i', $url, $matches)) {
            $id = $matches[1];

            return 'https://www.youtube.com/embed/'.$id.'?autoplay=1&mute=1&loop=1&playlist='.$id.'&controls=0&showinfo=0&rel=0&modestbranding=1&enablejsapi=1';
        }

        // Vimeo: https://vimeo.com/123456789
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[3].'?autoplay=1&muted=1&loop=1&background=1';
        }

        return $this->resolved_video_url;
    }

    /**
     * Resolve video URL (either uploaded MP4 or direct video URL).
     */
    public function getResolvedVideoUrlAttribute(): ?string
    {
        if ($this->video_file) {
            return $this->resolvePath($this->video_file);
        }

        return $this->video_url;
    }

    protected function resolvePath(?string $path, ?string $default = null): string
    {
        if (! $path) {
            return $default ?? '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset($path);
    }
}
