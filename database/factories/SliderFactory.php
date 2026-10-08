<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'subtitle' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'image' => 'images/haridwar-panorama-clocktower.jpg',
            'button_text' => 'Explore Now',
            'button_link' => '/products',
            'secondary_button_text' => 'Join Membership',
            'secondary_button_link' => '/membership/join',
            'badge_color' => 'amber',
            'sort_order' => 1,
            'is_active' => true,
        ];
    }
}
