<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingTicket;
use App\Models\Booking;
use App\Enums\BookingTicketStatus;

class BookingTicketSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::with('event.eventTickets')->limit(2)->get();

        if ($bookings->isEmpty()) {
            $this->command->warn('Cannot seed booking tickets: No bookings found.');
            return;
        }

        // Booking 1: 2 tickets
        $booking1 = $bookings[0];
        $ticket1 = $booking1->event->eventTickets->first();

        BookingTicket::create([
            'booking_id' => $booking1->id,
            'ticket_id' => $ticket1->id,
            'seat_id' => null,
            'attendee_name' => 'John Doe',
            'attendee_email' => 'john.doe@example.com',
            'attendee_phone' => '9841111111',
            'unit_price' => $ticket1->price,
            'status' => BookingTicketStatus::ACTIVE->value,
        ]);

        BookingTicket::create([
            'booking_id' => $booking1->id,
            'ticket_id' => $ticket1->id,
            'seat_id' => null,
            'attendee_name' => 'Jane Smith',
            'attendee_email' => 'jane.smith@example.com',
            'attendee_phone' => '9841111112',
            'unit_price' => $ticket1->price,
            'status' => BookingTicketStatus::ACTIVE->value,
        ]);

        // Booking 2: 1 ticket
        if (isset($bookings[1])) {
            $booking2 = $bookings[1];
            $ticket2 = $booking2->event->eventTickets->first();

            BookingTicket::create([
                'booking_id' => $booking2->id,
                'ticket_id' => $ticket2->id,
                'seat_id' => null,
                'attendee_name' => 'Michael Johnson',
                'attendee_email' => 'michael.j@example.com',
                'attendee_phone' => null,
                'unit_price' => $ticket2->price,
                'status' => BookingTicketStatus::ACTIVE->value,
            ]);
        }

        $this->command->info('Booking tickets seeded successfully');
    }
}
