<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'customer_id' => Customer::get()->random()->id,
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'info' => $this->faker->paragraph,
            'status_id' => Status::get()->random()->id,
            'date' => $this->faker->dateTime(),
            'created_at' => $this->faker->date(),
            'updated_at' => now(),
        ];
    }
}
