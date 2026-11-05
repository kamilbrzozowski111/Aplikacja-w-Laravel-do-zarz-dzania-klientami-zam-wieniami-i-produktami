<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ZAM/' . date('Y') . '/' . strtoupper(fake()->bothify('####')),
            'client_id' => \App\Models\Client::factory(),
            'status' => fake()->randomElement(['nowe', 'w realizacji', 'zrealizowane']),
            'total_price' => 0.00,
            'order_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}
