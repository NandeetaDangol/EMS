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
            $table->bigIncrements('booking_ticket_id');

            // Foreign Keys
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('seat_id')->nullable();

            $table->string('attendee_name', 255);
            $table->string('attendee_email', 255);
            $table->string('attendee_phone', 20)->nullable();
            $table->decimal('unit_price', 10, 2);

            $table->enum('status', BookingTicketStatus::values())
                ->default(BookingTicketStatus::ACTIVE->value);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('booking_id')
                ->references('booking_id')
                ->on('bookings')
                ->onDelete('cascade');

            $table->foreign('ticket_id')
                ->references('ticket_id')
                ->on('event_tickets')
                ->onDelete('restrict');

            $table->foreign('seat_id')
                ->references('seat_id')
                ->on('venue_seats')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_tickets');
    }
};
