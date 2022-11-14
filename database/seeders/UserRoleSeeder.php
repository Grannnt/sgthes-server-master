<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user_roles = [
            [
                'user_role'     => 'Super Admin',
                'created_by'    => 1
            ],
            [
                'user_role'     => 'Teacher',
                'created_by'    => 1
            ]
        ];

        foreach ($user_roles as $key => $value) {
            UserRole::create($value);
        }
    }
}