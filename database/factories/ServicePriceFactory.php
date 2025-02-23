<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrice>
 */
class ServicePriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $service = Service::inRandomOrder()->first();

        $currentPrice = $service->price;
        $newPrice = $this->faker->numberBetween(
            (int) ($currentPrice * 0.8),
            (int) ($currentPrice * 1.2)
        );

        $createdAt = $this->faker->dateTimeBetween('-6 months', 'now');

        return [
            'service_id' => $service->id,
            'price' => $newPrice,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
