<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('Starting database seeding');
        $this->command->newLine();

        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,
            VenueSeeder::class,
            VenueSeatSeeder::class,
            OrganizerSeeder::class,
            EventSeeder::class,
            EventMediaSeeder::class,
            EventTicketSeeder::class,
            BookingSeeder::class,
            BookingTicketSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('Database seeding completed successfully!');
        $this->command->newLine();

        // Display login credentials
        $this->command->info('Login Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@event.com', 'password123'],
                ['Organizer 1', 'organizer1@event.com', 'password123'],
                ['Organizer 2', 'organizer2@event.com', 'password123'],
                ['Organizer 3', 'organizer3@event.com', 'password123'],
                ['Organizer 4', 'organizer4@event.com', 'password123'],
                ['Test User', 'test@example.com', 'password123'],
            ]
        );
    }
}
