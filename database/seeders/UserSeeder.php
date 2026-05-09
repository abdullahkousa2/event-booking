<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Alice Smith',
            'email'    => 'user1@test.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Bob Johnson',
            'email'    => 'user2@test.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        User::create([
            'name'     => 'Carol Williams',
            'email'    => 'user3@test.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);
    }
}
