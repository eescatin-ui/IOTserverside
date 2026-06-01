<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin account for the web dashboard
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'api_token' => Str::random(60),
        ]);

        // Create a normal mobile user account
        User::factory()->create([
            'name' => 'Mobile User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);
    }
}
