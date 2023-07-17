<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            [
                'module_id' => 1, 
                'name' => 'Dashboard Access', 
                'slug' => 'dashboard-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role Access', 
                'slug' => 'role-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role Add', 
                'slug' => 'role-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role Edit', 
                'slug' => 'role-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role View', 
                'slug' => 'role-view', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role Delete', 
                'slug' => 'role-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 4, 
                'name' => 'Role Bulk Delete', 
                'slug' => 'role-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User Access', 
                'slug' => 'user-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User Add', 
                'slug' => 'user-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User Edit', 
                'slug' => 'user-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User View', 
                'slug' => 'user-view', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User Delete', 
                'slug' => 'user-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 5, 
                'name' => 'User Bulk Delete', 
                'slug' => 'user-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 8, 
                'name' => 'General Setting Access', 
                'slug' => 'general-setting-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 9, 
                'name' => 'Customer Group Access', 
                'slug' => 'customer-group-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 9, 
                'name' => 'Customer Group Add', 
                'slug' => 'customer-group-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 9, 
                'name' => 'Customer Group Edit', 
                'slug' => 'customer-group-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 9, 
                'name' => 'Customer Group Delete', 
                'slug' => 'customer-group-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 9, 
                'name' => 'Customer Group Bulk Delete', 
                'slug' => 'customer-group-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 10, 
                'name' => 'Unit Access', 
                'slug' => 'unit-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 10, 
                'name' => 'Unit Add', 
                'slug' => 'unit-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 10, 
                'name' => 'Unit Edit', 
                'slug' => 'unit-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 10, 
                'name' => 'Unit Delete', 
                'slug' => 'unit-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 10, 
                'name' => 'Unit Bulk Delete', 
                'slug' => 'unit-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 11, 
                'name' => 'Tax Access', 
                'slug' => 'tax-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 11, 
                'name' => 'Tax Add', 
                'slug' => 'tax-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 11, 
                'name' => 'Tax Edit', 
                'slug' => 'tax-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 11, 
                'name' => 'Tax Delete', 
                'slug' => 'tax-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 11, 
                'name' => 'Tax Bulk Delete', 
                'slug' => 'tax-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Access', 
                'slug' => 'menu-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Add', 
                'slug' => 'menu-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Edit', 
                'slug' => 'menu-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Delete', 
                'slug' => 'menu-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Bulk Delete', 
                'slug' => 'menu-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Builder Access', 
                'slug' => 'menu-builder-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Module Add', 
                'slug' => 'menu-module-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Module Edit', 
                'slug' => 'menu-module-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 12, 
                'name' => 'Menu Module Delete', 
                'slug' => 'menu-module-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 13, 
                'name' => 'Permission Access', 
                'slug' => 'permission-access', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 13, 
                'name' => 'Permission Add', 
                'slug' => 'permission-add', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 13, 
                'name' => 'Permission Edit', 
                'slug' => 'permission-edit', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 13, 
                'name' => 'Permission Delete', 
                'slug' => 'permission-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'module_id' => 13, 
                'name' => 'Permission Bulk Delete', 
                'slug' => 'permission-bulk-delete', 
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
