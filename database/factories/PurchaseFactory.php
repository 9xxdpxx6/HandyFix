<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'product_name' => $this->faker->words(3, true),
        ];
    }
}
