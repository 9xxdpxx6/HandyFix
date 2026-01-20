<?php

namespace Database\Factories;

use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceTypeFactory extends Factory
{
    protected $model = ServiceType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'icon' => $this->faker->optional()->word,
            'description' => $this->faker->optional()->sentence,
        ];
    }
}
