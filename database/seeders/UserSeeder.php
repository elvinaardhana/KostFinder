<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // //Create a new user
        // $user = new \App\Models\User();
        // $user->name = 'Admin';
        // $user->phone = '0895387470121';
        // $user->email = 'admin@gmail.com';
        // $user->password = bcrypt('admin1234');
        // $user->save();

        //Create multiple users
        $user = [
            [
                'name' => 'Admin',
                'phone' => '089649914677',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'User',
                'phone' => '089538127494',
                'email' => 'user@gmail.com',
                'password' => bcrypt('123456'),
            ],
        ];

        //Insert the user into the database
        DB::table('users')->insert($user);
    }
}
