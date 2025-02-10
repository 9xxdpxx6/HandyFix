<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\DayLedger;
use App\Models\ExpenseEntry;
use App\Models\MaterialEntry;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\ServiceEntry;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(RolesAndPermissionsSeeder::class);
       $this->call(UsersSeeder::class);
       $this->call(StatusesSeeder::class);
        $this->call(ServiceTypesSeeder::class);


        Customer::factory(10)->create();
        Order::factory(10)->create();
        Purchase::factory(40)->create();
        DayLedger::factory(40)->create();
        MaterialEntry::factory(40)->create();
        ServiceEntry::factory(40)->create();
        ExpenseEntry::factory(40)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
