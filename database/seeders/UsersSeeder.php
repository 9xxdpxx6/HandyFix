<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание пользователей
        $admin = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'qwe@qwe.qwe',
            'phone' => '79186290062',
            'password' => bcrypt('qwe'),
            'email_verified_at' => now(),
        ]);

        $user = User::firstOrCreate([
            'name' => 'Default User',
            'email' => 'asd@asd.asd',
            'phone' => '1111222233',
            'password' => bcrypt('qwe'),
            'email_verified_at' => now(),
        ]);

        // Присвоение ролей
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        $admin->assignRole($adminRole);
//        $user->assignRole($userRole);
    }
}
