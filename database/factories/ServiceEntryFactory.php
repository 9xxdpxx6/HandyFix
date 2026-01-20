<?php

namespace Database\Factories;

use App\Models\ServiceEntry;
use App\Models\Order;
use App\Models\Service;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceEntryFactory extends Factory
{
    protected $model = ServiceEntry::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'service_id' => Service::factory(),
            'mechanic_id' => Employee::factory(),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'quantity' => $this->faker->numberBetween(1, 5),
            'service_name' => $this->faker->words(3, true),
        ];
    }
}
