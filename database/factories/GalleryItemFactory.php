<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    protected $model = GalleryItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'caption' => fake()->paragraph(),
            'media_type' => 'image',
            'image' => 'images/haridwar-panorama-clocktower.jpg',
            'video_url' => null,
            'category' => fake()->randomElement(['Ganga Aarti', 'Ganga Sourcing', 'Temple Darshan', 'Sanctified Bottling']),
            'location' => 'Haridwar, Uttarakhand',
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
