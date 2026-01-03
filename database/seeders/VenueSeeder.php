<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('venues')->insert([
            [
                'name' => 'City Convention Hall',
                'address' => 'New Road, Kathmandu',
                'city' => 'Kathmandu',
                'capacity' => 5000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'National Stadium',
                'address' => 'Dasharath Rangasala, Tripureshwor',
                'city' => 'Kathmandu',
                'capacity' => 25000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pokhara Convention Center',
                'address' => 'Lakeside, Pokhara',
                'city' => 'Pokhara',
                'capacity' => 3000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bhrikutimandap Exhibition Hall',
                'address' => 'Bhrikutimandap, Kathmandu',
                'city' => 'Kathmandu',
                'capacity' => 8000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Venues seeded successfully');
    }
}
