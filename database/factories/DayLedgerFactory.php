<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DayLedger>
 */
class DayLedgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'balance' => $this->faker->randomFloat(2, 10, 1000),
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'user_id' => 1,
        ];
    }
}
