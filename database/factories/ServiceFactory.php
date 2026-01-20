<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'service_type_id' => ServiceType::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->paragraph,
            'price' => $this->faker->randomFloat(2, 500, 5000),
        ];
    }
}
