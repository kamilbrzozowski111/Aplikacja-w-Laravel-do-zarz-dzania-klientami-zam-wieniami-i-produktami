<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Domyślne konto administratora
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Klienci
        $clients = Client::factory(10)->create();

        // 3. Produkty
        $products = Product::factory(15)->create();

        // 4. Zamówienia z przypisaniem produktów i ilości w tabeli order_product
        foreach ($clients->take(8) as $index => $client) {
            $order = Order::create([
                'order_number' => 'ZAM/' . date('Y') . '/' . sprintf('%04d', $index + 1),
                'client_id' => $client->id,
                'status' => fake()->randomElement(['nowe', 'w realizacji', 'zrealizowane']),
                'total_price' => 0.00,
                'order_date' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'notes' => fake()->optional(0.6)->sentence(),
            ]);

            // Losowe 2-4 unikalne produkty do zamówienia
            $orderProducts = $products->random(rand(2, 4));
            $attachData = [];

            foreach ($orderProducts as $product) {
                $qty = rand(1, 5);
                $attachData[$product->id] = [
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                ];
            }

            $order->products()->attach($attachData);
            $order->recalculateTotalPrice();
        }
    }
}
