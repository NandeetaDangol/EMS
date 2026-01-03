<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BookingTicketStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('attendee_name', 255);
            $table->string('attendee_email', 255);
            $table->string('attendee_phone', 20)->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->enum('status', BookingTicketStatus::values())
                ->default(BookingTicketStatus::ACTIVE->value);

            $table->timestamps();

            // Foreign Keys
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('ticket_id')
                ->constrained('event_tickets')
                ->restrictOnDelete();

            $table->foreignId('seat_id')
                ->nullable()
                ->constrained('venue_seats')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_tickets');
    }
};
