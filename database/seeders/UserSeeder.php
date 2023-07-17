<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'role_id'    => 1,
                'name'       => 'Super Admin',
                'username'   => 'SuperAdmin',
                'email'      => 'superadmin@mail.com',
                'phone'      => '01516722237',
                'gender'     => 1,
                'password'   => Hash::make('SuperAdmin@100%'),
                'deletable'  => 1,
                'created_by' => 'Hadiuzzaman',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'role_id'    => 2,
                'name'       => 'Admin',
                'username'   => 'Admin',
                'email'      => 'admin@mail.com',
                'phone'      => '017',
                'gender'     => 1,
                'password'   => Hash::make('Admin@100%'),
                'deletable'  => 1,
                'created_by' => 'Hadiuzzaman',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
