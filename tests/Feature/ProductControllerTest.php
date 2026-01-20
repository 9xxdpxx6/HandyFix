<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем роли и разрешения
        $role = Role::firstOrCreate(['name' => 'manager']);
        Permission::firstOrCreate(['name' => 'read.products']);
        Permission::firstOrCreate(['name' => 'create.products']);
        Permission::firstOrCreate(['name' => 'update.products']);
        Permission::firstOrCreate(['name' => 'delete.products']);
        
        $role->givePermissionTo(['read.products', 'create.products', 'update.products', 'delete.products']);
    }

    /** @test */
    public function authenticated_user_can_view_products_index()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        $category = Category::create(['name' => 'Test Category']);
        $brand = Brand::create(['name' => 'Test Brand']);
        
        Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.products.index');
    }

    /** @test */
    public function authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        Storage::fake('public');
        
        $category = Category::create(['name' => 'Test Category']);
        $brand = Brand::create(['name' => 'Test Brand']);
        
        $image = UploadedFile::fake()->image('product.jpg');

        $productData = [
            'name' => 'Test Product',
            'sku' => 'SKU-001',
            'description' => 'Test description',
            'price' => 1000.50,
            'quantity' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'image' => $image,
        ];

        $response = $this->actingAs($user)->post(route('dashboard.products.store'), $productData);

        $response->assertRedirect(route('dashboard.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'SKU-001',
        ]);
    }

    /** @test */
    public function authenticated_user_can_update_product()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        $category = Category::create(['name' => 'Test Category']);
        $brand = Brand::create(['name' => 'Test Brand']);
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Old Name',
        ]);

        $updateData = [
            'name' => 'Updated Product Name',
            'sku' => $product->sku,
            'price' => 2000,
            'quantity' => 20,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ];

        $response = $this->actingAs($user)->put(route('dashboard.products.update', $product), $updateData);

        $response->assertRedirect(route('dashboard.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
        ]);
    }

    /** @test */
    public function authenticated_user_can_delete_product()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        $category = Category::create(['name' => 'Test Category']);
        $brand = Brand::create(['name' => 'Test Brand']);
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $response = $this->actingAs($user)->delete(route('dashboard.products.destroy', $product));

        $response->assertRedirect(route('dashboard.products.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
