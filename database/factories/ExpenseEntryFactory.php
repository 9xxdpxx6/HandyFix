<?php

namespace Database\Factories;

use App\Models\DayLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseEntry>
 */
class ExpenseEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day_ledger_id' => DayLedger::inRandomOrder()->value('id') ?? DayLedger::factory(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 50, 5000),
        ];
    }
}
