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
        // Распределяем даты создания с 10 апреля 2024 до текущего дня
        $createdAt = $this->faker->dateTimeBetween('2024-04-10', 'now');
        
        // Дата обновления должна быть между датой создания и текущей датой
        $updateEndDate = new \DateTime();
        $updatedAt = $createdAt < $updateEndDate 
            ? $this->faker->dateTimeBetween($createdAt, $updateEndDate)
            : $createdAt;

        return [
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'manager_id' => $this->faker->optional(0.5)->randomElement([Employee::factory(), null]),
            'total' => 0,
            'comment' => $this->faker->optional(0.7)->realText,
            'note' => $this->faker->optional(0.6)->realText,
            'status_id' => Status::factory(),
            'completed_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
