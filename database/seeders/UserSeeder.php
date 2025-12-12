<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Members
        User::create([
            'name' => 'Azhari Member',
            'email' => 'member1@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        User::create([
            'name' => 'John Member',
            'email' => 'member2@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);
    }
}
