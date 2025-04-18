<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'users',
            'loyalty',
            'customers',
            'specializations',
            'qualifications',
            'employees',
            'statuses',
            'orders',
            'permissions',
            'service-types',
            'services',
            'brands',
            'vehicles',
            'models',
            'categories',
            'products',
            'statistics',
            'roles',
            'icons',
        ];

        $actions = ['create', 'read', 'update', 'delete'];

        // Создаем разрешения для каждого модуля
        foreach ($modules as $module) {
            if ($module === 'statistics') {
                Permission::create(['name' => "read.{$module}"]);
            } else {
                foreach ($actions as $action) {
                    Permission::create(['name' => "{$action}.{$module}"]);
                }
            }
        }

        $roles = [
            'client',
            'junior-manager',
            'manager',
            'senior-manager',
            'junior-mechanic',
            'mechanic',
            'senior-mechanic',
            'junior-accountant',
            'accountant',
            'senior-accountant',
            'moderator',
            'admin',
        ];

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName]);
        }

        // Назначаем разрешения ролям
        $permissionsByRole = [
            'client' => [
                'create.users',
                'read.users',
                'update.users',
                'create.customers',
                'read.customers',
                'update.customers',
                'create.orders',
                'read.orders',
                'create.vehicles',
                'update.vehicles',
                'read.brands',
                'read.vehicles',
                'read.models',
                'read.categories',
                'read.loyalty',
            ],
            'junior-mechanic' => [
                'read.orders',
                'update.orders',
                'read.vehicles',
                'read.services',
                'read.models',
            ],
            'mechanic' => [
                'read.orders',
                'update.orders',
                'read.brands',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
                'create.services',
                'read.services',
                'update.services',
                'create.models',
                'read.models',
                'update.models',
            ],
            'senior-mechanic' => [
                'read.orders',
                'update.orders',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
                'create.services',
                'read.services',
                'update.services',
                'delete.services',
                'create.models',
                'read.models',
                'update.models',
                'delete.models',
            ],
            'junior-manager' => [
                'create.orders',
                'read.orders',
                'update.orders',
                'read.customers',
                'read.products',
                'read.models',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
            ],
            'manager' => [
                'create.orders',
                'read.orders',
                'update.orders',
                'create.customers',
                'read.customers',
                'update.customers',
                'read.products',
                'update.products',
                'read.employees',
                'create.models',
                'read.models',
                'update.models',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
            ],
            'senior-manager' => [
                'create.orders',
                'read.orders',
                'update.orders',
                'delete.orders',
                'create.customers',
                'read.customers',
                'update.customers',
                'create.products',
                'read.products',
                'update.products',
                'delete.products',
                'read.employees',
                'create.models',
                'read.models',
                'update.models',
                'delete.models',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
                'delete.vehicles',
            ],
            'junior-accountant' => [
                'read.loyalty',
                'read.statistics',
            ],
            'accountant' => [
                'read.orders',
                'read.loyalty',
                'update.loyalty',
                'read.statistics',
                'read.models',
            ],
            'senior-accountant' => [
                'read.orders',
                'read.loyalty',
                'update.loyalty',
                'delete.loyalty',
                'read.statistics',
                'read.models',
            ],
            'moderator' => [
                'create.users',
                'read.users',
                'update.users',
                'create.loyalty',
                'read.loyalty',
                'update.loyalty',
                'delete.loyalty',
                'create.customers',
                'read.customers',
                'update.customers',
                'create.specializations',
                'read.specializations',
                'update.specializations',
                'delete.specializations',
                'create.qualifications',
                'read.qualifications',
                'update.qualifications',
                'delete.qualifications',
                'create.employees',
                'read.employees',
                'update.employees',
                'create.statuses',
                'read.statuses',
                'update.statuses',
                'delete.statuses',
                'create.orders',
                'read.orders',
                'update.orders',
                'delete.orders',
                'create.service-types',
                'read.service-types',
                'update.service-types',
                'delete.service-types',
                'create.services',
                'read.services',
                'update.services',
                'delete.services',
                'create.brands',
                'read.brands',
                'update.brands',
                'delete.brands',
                'create.vehicles',
                'read.vehicles',
                'update.vehicles',
                'delete.vehicles',
                'create.models',
                'read.models',
                'update.models',
                'delete.models',
                'create.categories',
                'read.categories',
                'update.categories',
                'delete.categories',
                'create.products',
                'read.products',
                'update.products',
                'delete.products',
                'read.statistics',
            ],
            'admin' => array_keys(Permission::all()->pluck('name')->toArray()),
        ];

        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo($permissions);
        }
    }
}