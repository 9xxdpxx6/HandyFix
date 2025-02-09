<?php

namespace Database\Factories;

use App\Models\DayLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialEntry>
 */
class MaterialEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $incoming = $this->faker->randomFloat(2, 10, 1000);
        $issuance = $this->faker->randomFloat(2, 10, $incoming); // выдачи не могут быть больше поступлений
        $waste = $this->faker->randomFloat(2, 0, min(10, $incoming - $issuance)); // потери не могут быть больше оставшегося баланса

        $balance = $incoming - $issuance - $waste;

        return [
            'day_ledger_id' => DayLedger::inRandomOrder()->value('id') ?? DayLedger::factory(),
            'description' => $this->faker->sentence(),
            'incoming' => $incoming,
            'issuance' => $issuance,
            'waste' => $waste,
            'balance' => $balance,
        ];
    }
}
