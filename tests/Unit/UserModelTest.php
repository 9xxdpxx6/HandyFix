<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_have_customer_relationship()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
        ]);

        $loyaltyLevel = \App\Models\LoyaltyLevel::factory()->create();
        $customer = Customer::factory()->create([
            'user_id' => $user->id,
            'loyalty_points' => 100,
            'loyalty_level_id' => $loyaltyLevel->id,
        ]);

        $this->assertInstanceOf(Customer::class, $user->fresh()->customer);
        $this->assertEquals($user->id, $user->fresh()->customer->user_id);
    }

    /** @test */
    public function user_can_have_employee_relationship()
    {
        $user = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'phone' => '+1234567891',
            'password' => bcrypt('password'),
        ]);

        $employee = Employee::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Employee::class, $user->employee);
        $this->assertEquals($user->id, $user->employee->user_id);
    }

    /** @test */
    public function user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintext',
        ]);

        $this->assertNotEquals('plaintext', $user->password);
        $this->assertTrue(password_verify('plaintext', $user->password));
    }
}
