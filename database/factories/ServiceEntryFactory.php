<?php

namespace Database\Factories;

use App\Models\DayLedger;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceEntry>
 */
class ServiceEntryFactory extends Factory
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
            'service_type_id' => ServiceType::inRandomOrder()->value('id') ?? 1,
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
