<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Service;
use App\Models\Employee;

class ServiceEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Получаем случайный заказ, услугу и механика из базы данных
        $order = Order::inRandomOrder()->first();
        $service = Service::inRandomOrder()->first();
        $mechanic = Employee::inRandomOrder()->first();

        // Дата создания записи должна быть после даты создания заказа
        // но до текущей даты
        $orderCreatedAt = $order->created_at;
        $endDate = new \DateTime();
        
        // Проверяем, что дата заказа не позже текущей даты
        $startDate = $orderCreatedAt instanceof \DateTime && $orderCreatedAt < $endDate 
            ? $orderCreatedAt
            : new \DateTime('2024-04-10');
        
        $createdAt = $this->faker->dateTimeBetween($startDate, $endDate);

        return [
            'order_id' => $order->id,
            'service_id' => $service->id,
            'mechanic_id' => $mechanic->id,
            'price' => $service->price,
            'quantity' => $this->faker->numberBetween(1, 3),
            'service_name' => $service->name,
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, $endDate),
        ];
    }
}
