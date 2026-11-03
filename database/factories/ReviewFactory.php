<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'rating' => fake()->numberBetween(4, 5),
            'title' => fake()->sentence(4),
            'comment' => fake()->paragraph(),
            'is_verified_purchase' => true,
            'status' => 'pending',
            'admin_notes' => null,
            'approved_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_at' => null,
            'admin_notes' => 'Rejected by admin moderation.',
        ]);
    }
}
