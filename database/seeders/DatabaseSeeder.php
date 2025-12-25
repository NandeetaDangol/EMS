<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed admin user
        $this->call([
            AdminSeeder::class,
        ]);

        // Create test user
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'user',
            'status' => 'active',
        ]);

        // Create test organizer 
        User::factory()->create([
            'first_name' => 'Event',
            'last_name' => 'Organizer',
            'email' => 'organizer@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'organizer',
            'status' => 'active',
        ]);
    }
}
