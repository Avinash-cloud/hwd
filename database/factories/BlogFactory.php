<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'excerpt' => fake()->paragraph(2),
            'content' => fake()->paragraphs(5, true),
            'image' => 'images/har-ki-pauri-temples.jpg',
            'category' => fake()->randomElement(['Spiritual Wisdom', 'Rituals & Traditions', 'Ganga Sanctity', 'Vedic Health']),
            'author_name' => fake()->name(),
            'reading_time_minutes' => fake()->numberBetween(3, 10),
            'is_published' => true,
            'is_featured' => fake()->boolean(20),
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'views_count' => fake()->numberBetween(50, 1500),
        ];
    }
}
