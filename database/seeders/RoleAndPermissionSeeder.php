<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions list
        $permissions = [
            // User Management
            'create users',
            'update users',
            'delete users',
            'assign roles',
            'view users',

            // Inventory
            'view inventory',
            'add inventory',
            'update inventory',
            'delete inventory',
            'restock inventory',

            // Orders
            'create order',
            'view orders',
            'update order',
            'cancel order',
            'assign delivery',

            // Reports
            'view sales report',
            'export sales data',
            'view inventory stats',
            'view customer orders',

            // System
            'manage system settings',
            'manage roles & permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, "guard_name" => "api"]);
        }


        $roles = [
            'super-admin' => $permissions,
            "admin" => [
                'create users',
                'update users',
                "delete users",
                'view users',
                'view inventory',
                'add inventory',
                'update inventory',
                'view orders',
                'update order',
                'view sales report',
                'export sales data',
                'manage roles & permissions',
            ],
            'inventory-manager' => [
                'view inventory',
                'add inventory',
                'update inventory',
                'delete inventory',
                'restock inventory',
            ],
            'order-manager' => [
                'create order',
                'view orders',
                'update order',
                'cancel order',
                'assign delivery',
            ],
            'sales-agent' => [
                'create order',
                'view orders',
                'view customer orders',
            ],
            'analyst' => [
                'view sales report',
                'export sales data',
                'view inventory stats',
            ],
            'customer' => [
                'create order',
                'view orders',
            ],
        ];

        foreach ($roles as $role => $permissions) {
           $role = Role::firstOrCreate(["name"=> $role, "guard_name"=>"api"]);
           $role->syncPermissions($permissions);
        }


        $this->command->info('Roles and permissions seeded successfully.');
    }

}
