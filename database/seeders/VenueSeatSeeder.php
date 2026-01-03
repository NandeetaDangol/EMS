<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\SeatType;
use Carbon\Carbon;

class VenueSeatSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = Carbon::now();

        $venueSeats = [
            // Venue 1: Concert Hall - Section A (VIP Seats)
            ['venue_id' => 1, 'section' => 'A', 'row' => '1', 'seat_number' => '1', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'A', 'row' => '1', 'seat_number' => '2', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'A', 'row' => '1', 'seat_number' => '3', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 1: Concert Hall - Section B (Premium  Seats)
            ['venue_id' => 1, 'section' => 'B', 'row' => '1', 'seat_number' => '1', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'B', 'row' => '1', 'seat_number' => '2', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'B', 'row' => '1', 'seat_number' => '3', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 1: Concert Hall - Section C (Regular Seats)
            ['venue_id' => 1, 'section' => 'C', 'row' => '1', 'seat_number' => '1', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'C', 'row' => '1', 'seat_number' => '2', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 1, 'section' => 'C', 'row' => '1', 'seat_number' => '3', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 2: Conference Center - Main Section
            ['venue_id' => 2, 'section' => 'Main', 'row' => 'A', 'seat_number' => '1', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 2, 'section' => 'Main', 'row' => 'A', 'seat_number' => '2', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 2, 'section' => 'Main', 'row' => 'A', 'seat_number' => '3', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 2: Conference Center - Balcony Section
            ['venue_id' => 2, 'section' => 'Balcony', 'row' => 'A', 'seat_number' => '1', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 2, 'section' => 'Balcony', 'row' => 'A', 'seat_number' => '2', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 2, 'section' => 'Balcony', 'row' => 'A', 'seat_number' => '3', 'seat_type' => SeatType::PREMIUM->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 3: Sports Stadium - North Section
            ['venue_id' => 3, 'section' => 'North', 'row' => '1', 'seat_number' => '1', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 3, 'section' => 'North', 'row' => '1', 'seat_number' => '2', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 3, 'section' => 'North', 'row' => '1', 'seat_number' => '3', 'seat_type' => SeatType::REGULAR->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Venue 3: Sports Stadium - South Section (VIP)
            ['venue_id' => 3, 'section' => 'South', 'row' => '1', 'seat_number' => '1', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 3, 'section' => 'South', 'row' => '1', 'seat_number' => '2', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['venue_id' => 3, 'section' => 'South', 'row' => '1', 'seat_number' => '3', 'seat_type' => SeatType::VIP->value, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('venue_seats')->insert($venueSeats);
        $this->command->info('Venue seats seeded successfully');
    }
}
