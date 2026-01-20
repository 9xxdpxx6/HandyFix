<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\LoyaltyLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function customer_belongs_to_user()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $customer->user);
        $this->assertEquals($user->id, $customer->user->id);
    }

    /** @test */
    public function customer_has_many_orders()
    {
        $loyaltyLevel = LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);

        Order::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->assertCount(3, $customer->fresh()->orders);
        $this->assertInstanceOf(Order::class, $customer->fresh()->orders->first());
    }

    /** @test */
    public function customer_has_many_vehicles()
    {
        $loyaltyLevel = LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);

        Vehicle::factory()->count(2)->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertCount(2, $customer->fresh()->vehicles);
        $this->assertInstanceOf(Vehicle::class, $customer->fresh()->vehicles->first());
    }

    /** @test */
    public function customer_belongs_to_loyalty_level()
    {
        $loyaltyLevel = LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create([
            'loyalty_level_id' => $loyaltyLevel->id,
        ]);

        $this->assertInstanceOf(LoyaltyLevel::class, $customer->fresh()->loyaltyLevel);
        $this->assertEquals($loyaltyLevel->id, $customer->fresh()->loyaltyLevel->id);
    }
}
