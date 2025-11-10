<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@mayra.com')->exists()) {
            User::create([
                'name' => 'Admin Mayra',
                'email' => 'admin@mayra.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // Create test user if not exists
        if (!User::where('email', 'user@mayra.com')->exists()) {
            User::create([
                'name' => 'Test User',
                'email' => 'user@mayra.com',
                'password' => Hash::make('user1234'),
                'role' => 'user',
            ]);
        }
    }
}
