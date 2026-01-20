<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\User;
use App\Models\Order;
use App\Models\Qualification;
use App\Models\Specialization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function employee_belongs_to_user()
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $employee->user);
        $this->assertEquals($user->id, $employee->user->id);
    }

    /** @test */
    public function employee_has_many_orders_as_manager()
    {
        $qualification = \App\Models\Qualification::factory()->create();
        $specialization = \App\Models\Specialization::factory()->create();
        $employee = Employee::factory()->create([
            'qualification_id' => $qualification->id,
            'specialization_id' => $specialization->id,
        ]);
        
        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = \App\Models\Customer::factory()->create(['loyalty_level_id' => $loyaltyLevel->id]);
        $status = \App\Models\Status::factory()->create();
        $vehicle = \App\Models\Vehicle::factory()->create(['customer_id' => $customer->id]);

        Order::factory()->count(2)->create([
            'manager_id' => $employee->id,
            'customer_id' => $customer->id,
            'status_id' => $status->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->assertCount(2, $employee->fresh()->orders);
        $this->assertInstanceOf(Order::class, $employee->fresh()->orders->first());
    }

    /** @test */
    public function employee_belongs_to_qualification()
    {
        $qualification = Qualification::factory()->create();
        $specialization = \App\Models\Specialization::factory()->create();
        $employee = Employee::factory()->create([
            'qualification_id' => $qualification->id,
            'specialization_id' => $specialization->id,
        ]);

        $this->assertInstanceOf(Qualification::class, $employee->fresh()->qualification);
        $this->assertEquals($qualification->id, $employee->fresh()->qualification->id);
    }
}
