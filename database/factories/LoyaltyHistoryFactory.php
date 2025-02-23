<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class LoyaltyHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();

        $pointsChange = $this->faker->numberBetween(0, 5000);

        $description = $this->faker->randomElement([
                'Начисление баллов за заказ',
                'Бонус за отзыв',
                'Программа лояльности',
            ]);

        $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');

        return [
            'customer_id' => $customer->id,
            'points_change' => $pointsChange,
            'description' => $description,
            'created_at' => $createdAt,
        ];
    }
}
