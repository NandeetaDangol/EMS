<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\BookingStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('booking_id');

            // Foreign Keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');

            $table->string('booking_reference', 255)->unique();
            $table->unsignedInteger('total_tickets');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total_amount', 10, 2);

            $table->enum('status', BookingStatus::values())
                ->default(BookingStatus::PENDING->value);

            $table->timestamp('booking_date')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
