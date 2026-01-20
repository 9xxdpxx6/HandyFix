<?php

namespace Database\Factories;

use App\Models\LoyaltyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoyaltyLevelFactory extends Factory
{
    protected $model = LoyaltyLevel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'min_points' => $this->faker->numberBetween(0, 100000),
            'discount' => $this->faker->optional()->randomFloat(2, 0, 50),
        ];
    }
}
