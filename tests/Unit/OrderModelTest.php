<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Status;
use App\Models\Purchase;
use App\Models\ServiceEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_belongs_to_customer()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    /** @test */
    public function order_belongs_to_vehicle()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status_id' => $status->id,
        ]);

        $this->assertInstanceOf(Vehicle::class, $order->vehicle);
        $this->assertEquals($vehicle->id, $order->vehicle->id);
    }

    /** @test */
    public function order_belongs_to_status()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->assertInstanceOf(Status::class, $order->status);
        $this->assertEquals($status->id, $order->status->id);
    }

    /** @test */
    public function order_has_many_purchases()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        Purchase::factory()->count(3)->create([
            'order_id' => $order->id,
        ]);

        $this->assertCount(3, $order->fresh()->purchases);
        $this->assertInstanceOf(Purchase::class, $order->fresh()->purchases->first());
    }

    /** @test */
    public function order_has_many_service_entries()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);
        
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        ServiceEntry::factory()->count(2)->create([
            'order_id' => $order->id,
        ]);

        $this->assertCount(2, $order->fresh()->serviceEntries);
        $this->assertInstanceOf(ServiceEntry::class, $order->fresh()->serviceEntries->first());
    }
}
