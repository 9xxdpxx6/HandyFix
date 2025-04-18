<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::inRandomOrder()->first();
        $product = Product::inRandomOrder()->first();

        $orderCreatedAt = $order->created_at;
        $endDate = new \DateTime();
        
        if (!($orderCreatedAt instanceof \DateTime) || $orderCreatedAt > $endDate) {
            $orderCreatedAt = new \DateTime('2024-04-10');
        }
        
        $createdAt = $this->faker->dateTimeBetween($orderCreatedAt, $endDate);

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'quantity' => $this->faker->numberBetween(1, 5),
            'product_name' => $product->name,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, $endDate),
        ];
    }
}
