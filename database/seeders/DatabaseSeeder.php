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
//        $this->call(RolesAndPermissionsSeeder::class);
//        $this->call(UsersSeeder::class);
//        $this->call(StatusesSeeder::class);
        $this->call(ServiceTypesSeeder::class);


//         Customer::factory(100)->create();
//         Order::factory(100)->create();
//         Purchase::factory(400)->create();
//         DayLedger::factory(400)->create();
//         MaterialEntry::factory(400)->create();
//         ServiceEntry::factory(400)->create();
//         ExpenseEntry::factory(400)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
