<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Status;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем роли и разрешения
        $role = Role::firstOrCreate(['name' => 'manager']);
        Permission::firstOrCreate(['name' => 'read.orders']);
        Permission::firstOrCreate(['name' => 'create.orders']);
        Permission::firstOrCreate(['name' => 'update.orders']);
        Permission::firstOrCreate(['name' => 'delete.orders']);
        
        $role->givePermissionTo(['read.orders', 'create.orders', 'update.orders', 'delete.orders']);
    }

    /** @test */
    public function authenticated_user_can_view_orders_index()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        $status = Status::create(['name' => 'Новый', 'color' => '#000000', 'is_closing' => false]);
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        Order::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status_id' => $status->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.orders.index');
    }

    /** @test */
    public function authenticated_user_can_create_order()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        
        $status = Status::create(['name' => 'Новый', 'color' => '#000000', 'is_closing' => false]);
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $category = Category::create(['name' => 'Test Category']);
        $brand = Brand::create(['name' => 'Test Brand']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
        
        $serviceType = ServiceType::create(['name' => 'Test Service Type']);
        $service = Service::create([
            'service_type_id' => $serviceType->id,
            'name' => 'Test Service',
            'price' => 1000,
        ]);

        $orderData = [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status_id' => $status->id,
            'total' => 5000,
            'comment' => 'Test comment',
            'products' => [
                json_encode([
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => 2,
                ])
            ],
            'services' => [
                json_encode([
                    'id' => $service->id,
                    'name' => $service->name,
                    'code' => 'S001',
                    'price' => $service->price,
                    'quantity' => 1,
                ])
            ],
        ];

        $response = $this->actingAs($user)->post(route('dashboard.orders.store'), $orderData);

        $response->assertRedirect(route('dashboard.orders.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status_id' => $status->id,
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_orders()
    {
        $response = $this->get(route('dashboard.orders.index'));

        $response->assertRedirect(route('login'));
    }
}
