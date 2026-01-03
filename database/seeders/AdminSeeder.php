<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the users table with initial users.
     */
    public function run(): void
    {
        // Admin user (user_id: 1)
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@event.com',
            'password' => 'password123',
            'phone' => '+977-9841234567',
            'user_type' => 'admin',
            'status' => 'active',
        ]);

        // Organizer user 1 (user_id: 2) - for Event Masters Pvt Ltd
        User::create([
            'first_name' => 'Event',
            'last_name' => 'Master',
            'email' => 'organizer1@event.com',
            'password' => 'password123',
            'phone' => '+977-9841234568',
            'user_type' => 'organizer',
            'status' => 'active',
        ]);

        // Organizer user 2 (user_id: 3) - for Tech Events Nepal
        User::create([
            'first_name' => 'Tech',
            'last_name' => 'Events',
            'email' => 'organizer2@event.com',
            'password' => 'password123',
            'phone' => '+977-9841234569',
            'user_type' => 'organizer',
            'status' => 'active',
        ]);

        // Organizer user 3 (user_id: 4) - for Sports Events Ltd
        User::create([
            'first_name' => 'Sports',
            'last_name' => 'Events',
            'email' => 'organizer3@event.com',
            'password' => 'password123',
            'phone' => '+977-9841234570',
            'user_type' => 'organizer',
            'status' => 'active',
        ]);

        // Organizer user 4 (user_id: 5) - for Cultural Arts Society
        User::create([
            'first_name' => 'Cultural',
            'last_name' => 'Arts',
            'email' => 'organizer4@event.com',
            'password' => 'password123',
            'phone' => '+977-9841234571',
            'user_type' => 'organizer',
            'status' => 'active',
        ]);

        // Test user (user_id: 6)
        User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'phone' => '+977-9841234572',
            'user_type' => 'user',
            'status' => 'active',
        ]);

        $this->command->info('Users seeded successfully (6 users created)');
    }
}
