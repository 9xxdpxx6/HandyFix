<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'description' => $this->faker->optional()->paragraph,
            'price' => $this->faker->randomFloat(2, 100, 10000),
            'quantity' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->optional()->imageUrl(),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
        ];
    }
}
