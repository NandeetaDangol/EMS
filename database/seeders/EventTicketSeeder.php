<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventTicketSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $eventTickets = [
            // Event 1: Summer Music Festival
            [
                'event_id' => 1,
                'ticket_type' => 'General Admission',
                'price' => 50.00,
                'quantity_total' => 1000,
                'quantity_sold' => 350,
                'quantity_available' => 650,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(6), 
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 1,
                'ticket_type' => 'VIP Pass',
                'price' => 150.00,
                'quantity_total' => 200,
                'quantity_sold' => 120,
                'quantity_available' => 80,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(6),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 2,
                'ticket_type' => 'Full Conference Pass',
                'price' => 299.00,
                'quantity_total' => 500,
                'quantity_sold' => 280,
                'quantity_available' => 220,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(7),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 2,
                'ticket_type' => 'Single Day Pass',
                'price' => 120.00,
                'quantity_total' => 300,
                'quantity_sold' => 95,
                'quantity_available' => 205,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(7),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 3,
                'ticket_type' => 'Full Marathon',
                'price' => 80.00,
                'quantity_total' => 300,
                'quantity_sold' => 215,
                'quantity_available' => 85,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(8),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 3,
                'ticket_type' => 'Half Marathon',
                'price' => 50.00,
                'quantity_total' => 500,
                'quantity_sold' => 380,
                'quantity_available' => 120,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(8),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 4,
                'ticket_type' => 'Orchestra Seating',
                'price' => 85.00,
                'quantity_total' => 100,
                'quantity_sold' => 72,
                'quantity_available' => 28,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(9),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'event_id' => 4,
                'ticket_type' => 'Mezzanine Seating',
                'price' => 60.00,
                'quantity_total' => 150,
                'quantity_sold' => 98,
                'quantity_available' => 52,
                'is_active' => true,
                'sale_end' => $now->copy()->addMonths(9),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('event_tickets')->insert($eventTickets);

        $this->command->info('Event tickets seeded successfully');
    }
}
