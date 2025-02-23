<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Запуск сидера.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'qwe@qwe.qwe'],
            [
                'name' => 'Admin',
                'phone' => '79186290062',
                'password' => bcrypt('qwe'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::findByName('admin');

        if (!$admin->hasRole($adminRole)) {
            $admin->assignRole($adminRole);
        }
    }
}
