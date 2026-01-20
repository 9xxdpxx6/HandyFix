<?php

namespace Database\Factories;

use App\Models\VehicleModel;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleModelFactory extends Factory
{
    protected $model = VehicleModel::class;

    public function definition(): array
    {
        $startYear = $this->faker->numberBetween(1990, 2020);
        $endYear = $this->faker->numberBetween($startYear, 2025);

        return [
            'brand_id' => Brand::factory(),
            'name' => $this->faker->words(2, true),
            'generation' => $this->faker->optional()->word,
            'start_year' => $startYear,
            'end_year' => $endYear,
            'is_facelift' => $this->faker->boolean(30),
            'facelift_year' => $this->faker->optional()->numberBetween($startYear, $endYear),
        ];
    }
}
