<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'sku' => 'SKU-' . strtoupper(fake()->bothify('??###')),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 10, 500),
            'stock' => fake()->numberBetween(5, 100),
        ];
    }
}
