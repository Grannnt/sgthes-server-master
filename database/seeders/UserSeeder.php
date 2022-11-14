<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'email'             => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin123'),
                'firstname'         => 'Anna Jane',
                'lastname'          => 'Catubag',
                'gender'            => 'Female',
                'birthdate'         => '1992-11-15',
                'contact_no'        => '09128994430',
                'user_role_id'      => 1,
                'status'            => 1,
                'remember_token'    => Str::random(10),
            ],
        ];

        foreach ($users as $key => $value) {
            User::create($value);
        }
    }
}