<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание ролей
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Создание разрешений для клиентов
        $createCustomerPermission = Permission::firstOrCreate(['name' => 'create customers']);
        $viewCustomerPermission = Permission::firstOrCreate(['name' => 'view customers']);
        $editCustomerPermission = Permission::firstOrCreate(['name' => 'edit customers']);
        $deleteCustomerPermission = Permission::firstOrCreate(['name' => 'delete customers']);

        // Создание разрешений для заказов
        $createOrderPermission = Permission::firstOrCreate(['name' => 'create orders']);
        $viewOrderPermission = Permission::firstOrCreate(['name' => 'view orders']);
        $editOrderPermission = Permission::firstOrCreate(['name' => 'edit orders']);
        $deleteOrderPermission = Permission::firstOrCreate(['name' => 'delete orders']);

        // Создание разрешений для Бухгалтерии
//        $createAccountingPermission = Permission::firstOrCreate(['name' => 'create orders']);
//        $viewAccountingPermission = Permission::firstOrCreate(['name' => 'view orders']);
//        $editAccountingPermission = Permission::firstOrCreate(['name' => 'edit orders']);
//        $deleteAccountingPermission = Permission::firstOrCreate(['name' => 'delete orders']);

        // Назначение разрешений ролям
        $adminRole->givePermissionTo([
            $createCustomerPermission,
            $viewCustomerPermission,
            $editCustomerPermission,
            $deleteCustomerPermission,
            $createOrderPermission,
            $viewOrderPermission,
            $editOrderPermission,
            $deleteOrderPermission,
        ]);

        // Назначение только прав на просмотр для роли пользователя
        $userRole->givePermissionTo([
            $viewCustomerPermission,
            $viewOrderPermission,
        ]);
    }
}
