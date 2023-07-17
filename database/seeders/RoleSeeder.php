<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['role_name' => 'SuperAdmin','deletable'=>1,'created_by'=>'Mohammad Arman','created_at'=>date('Y-m-d H:i:s')],
            ['role_name' => 'Admin','deletable'=>1,'created_by'=>'Mohammad Arman','created_at'=>date('Y-m-d H:i:s')],
        ]);
    }
}
