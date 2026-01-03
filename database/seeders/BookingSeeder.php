<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Event;
use App\Models\EventTicket;
use App\Enums\BookingStatus;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();
        $event = Event::first();
        $ticket = EventTicket::first();

        if (!$user || !$event || !$ticket) {
            $this->command->warn('Cannot seed bookings: Required data missing.');
            return;
        }

        // Booking 1: Confirmed
        Booking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'booking_reference' => 'BK-' . strtoupper(Str::random(8)),
            'total_tickets' => 2,
            'subtotal' => $ticket->price * 2,
            'total_amount' => $ticket->price * 2,
            'status' => BookingStatus::CONFIRMED->value,
        ]);

        // Booking 2: Pending
        Booking::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'booking_reference' => 'BK-' . strtoupper(Str::random(8)),
            'total_tickets' => 1,
            'subtotal' => $ticket->price,
            'total_amount' => $ticket->price,
            'status' => BookingStatus::PENDING->value,
        ]);

        $this->command->info('Bookings seeded successfully');
    }
}
