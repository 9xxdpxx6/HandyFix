<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::get()->random()->id,
            'name' => $this->faker->word,
            'material_price' => $this->faker->randomFloat(2, 1, 100),
            'service_price' => $this->faker->randomFloat(2, 1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
