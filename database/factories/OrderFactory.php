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

        // Распределяем даты создания с 10 апреля 2024 до текущего дня
        $createdAt = $this->faker->dateTimeBetween('2024-04-10', 'now');
        
        // Выбираем случайный статус
        $statusId = $this->faker->randomElement($statusIds);
        
        // Проверяем, является ли статус закрывающим
        $isClosingStatus = Status::find($statusId)->is_closing ?? false;
        
        // Максимальная дата для завершения заказа
        $endDate = min(new \DateTime(), new \DateTime('+10 days'));
        
        // Если статус закрывающий и дата окончания после даты создания, генерируем дату завершения
        $completedAt = null;
        if ($isClosingStatus && $createdAt < $endDate) {
            $completedAt = $this->faker->dateTimeBetween($createdAt, $endDate);
        }
        
        // Дата обновления должна быть между датой создания и текущей датой
        $updateEndDate = new \DateTime();
        $updatedAt = $createdAt < $updateEndDate 
            ? $this->faker->dateTimeBetween($createdAt, $updateEndDate)
            : $createdAt;

        return [
            'customer_id' => $this->faker->randomElement($customerIds),
            'vehicle_id' => $this->faker->randomElement($vehicleIds),
            'manager_id' => $this->faker->optional(0.5)->randomElement($managerIds),
            'total' => 0,
            'comment' => $this->faker->optional(0.7)->realText,
            'note' => $this->faker->optional(0.6)->realText,
            'status_id' => $statusId,
            'completed_at' => $completedAt,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
