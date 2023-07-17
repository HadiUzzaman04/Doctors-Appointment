<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::insert([
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Dashboard',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-tachometer-alt',
                'url'           => '/',
                'order'         => 1,
                'parent_id'     => NULL,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '1',
                'module_name'   => NULL,
                'divider_title' => 'Menus',
                'icon_class'    => NULL,
                'url'           => NULL,
                'order'         => 2,
                'parent_id'     => NULL,
                'target'        => NULL,
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '1',
                'module_name'   => NULL,
                'divider_title' => 'Access Control',
                'icon_class'    => NULL,
                'url'           => NULL,
                'order'         => 3,
                'parent_id'     => NULL,
                'target'        => NULL,
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Role',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-user-tag',
                'url'           => 'role',
                'order'         => 4,
                'parent_id'     => NULL,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'User',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-users',
                'url'           => 'user',
                'order'         => 5,
                'parent_id'     => NULL,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '1',
                'module_name'   => NULL,
                'divider_title' => 'System',
                'icon_class'    => NULL,
                'url'           => NULL,
                'order'         => 6,
                'parent_id'     => NULL,
                'target'        => NULL,
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Setting',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-cogs',
                'url'           => NULL,
                'order'         => 7,
                'parent_id'     => NULL,
                'target'        => NULL,
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'General Setting',
                'divider_title' => NULL,
                'icon_class'    => NULL,
                'url'           => 'setting',
                'order'         => 1,
                'parent_id'     => 7,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Customer Group',
                'divider_title' => NULL,
                'icon_class'    => NULL,
                'url'           => 'customer-group',
                'order'         => 2,
                'parent_id'     => 7,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Unit',
                'divider_title' => NULL,
                'icon_class'    => NULL,
                'url'           => 'unit',
                'order'         => 3,
                'parent_id'     => 7,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Tax',
                'divider_title' => NULL,
                'icon_class'    => NULL,
                'url'           => 'tax',
                'order'         => 4,
                'parent_id'     => 7,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Menu',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-th-list',
                'url'           => 'menu',
                'order'         => 8,
                'parent_id'     => NULL,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'menu_id'       => 1,
                'type'          => '2',
                'module_name'   => 'Permission',
                'divider_title' => NULL,
                'icon_class'    => 'fas fa-tasks',
                'url'           => 'menu/module/permission',
                'order'         => 9,
                'parent_id'     => NULL,
                'target'        => '_self',
                'created_at'    => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
