<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    /** @test */
    public function product_belongs_to_brand()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create([
            'brand_id' => $brand->id,
        ]);

        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertEquals($brand->id, $product->brand->id);
    }

    /** @test */
    public function product_has_image_url_attribute()
    {
        $product = Product::factory()->create([
            'image' => 'products/test-image.jpg',
        ]);

        $this->assertStringContainsString('products/test-image.jpg', $product->image_url);
    }

    /** @test */
    public function product_returns_default_image_when_no_image_set()
    {
        $product = Product::factory()->create([
            'image' => null,
        ]);

        $this->assertStringContainsString('default-product.jpg', $product->image_url);
    }
}
