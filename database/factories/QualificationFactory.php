<?php

namespace Database\Factories;

use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;

class QualificationFactory extends Factory
{
    protected $model = Qualification::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'min_seniority' => $this->faker->numberBetween(0, 10),
            'code' => $this->faker->unique()->bothify('???'),
            'description' => $this->faker->optional()->sentence,
        ];
    }
}
