<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();

        $currentPrice = $product->price;
        $newPrice = $this->faker->numberBetween(
            (int) ($currentPrice * 0.8),
            (int) ($currentPrice * 1.2)
        );

        $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');

        return [
            'product_id' => $product->id,
            'price' => $newPrice,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
