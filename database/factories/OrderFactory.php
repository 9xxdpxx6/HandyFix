<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Status;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
        $customerIds = Customer::pluck('id')->toArray();
        $vehicleIds = Vehicle::pluck('id')->toArray();
        $managerIds = Employee::pluck('id')->toArray();
        $statusIds = Status::pluck('id')->toArray();

        return [
            'customer_id' => $this->faker->randomElement($customerIds),
            'vehicle_id' => $this->faker->randomElement($vehicleIds),
            'manager_id' => $this->faker->optional(0.5)->randomElement($managerIds),
            'total' => $this->faker->randomFloat(2, 100, 10000),
            'comment' => $this->faker->optional(0.7)->realText,
            'note' => $this->faker->optional(0.6)->realText,
            'status_id' => $this->faker->randomElement($statusIds),
            'completed_at' => $this->faker->optional(0.4)->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
