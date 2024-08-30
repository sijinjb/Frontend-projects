<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed a user with the role "super"
        DB::table('users')->insert([
            'name' => 'Super User',
            'email' => 'super@ccmaster.backoffice',
            'password' => Hash::make('super@$1'), // Replace with your desired password
            'role' => 'super',
            'active' => 'active', // Set the user as active
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed a user with the role "admin"
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@ccmaster.backoffice',
            'password' => Hash::make('admin@$2'), // Replace with your desired password
            'role' => 'admin',
            'active' => 'active', // Set the user as active
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
