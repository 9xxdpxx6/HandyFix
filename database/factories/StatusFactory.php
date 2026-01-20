<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    protected $model = Status::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'color' => $this->faker->hexColor,
            'is_closing' => $this->faker->boolean(20),
        ];
    }
}
