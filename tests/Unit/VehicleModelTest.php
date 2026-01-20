<?php

namespace Tests\Unit;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\VehicleModel;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vehicle_belongs_to_customer()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertInstanceOf(Customer::class, $vehicle->customer);
        $this->assertEquals($customer->id, $vehicle->customer->id);
    }

    /** @test */
    public function vehicle_has_many_orders()
    {
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->id]);
        $status = \App\Models\Status::factory()->create();

        Order::factory()->count(3)->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'status_id' => $status->id,
        ]);

        $this->assertCount(3, $vehicle->fresh()->orders);
        $this->assertInstanceOf(Order::class, $vehicle->fresh()->orders->first());
    }
}
