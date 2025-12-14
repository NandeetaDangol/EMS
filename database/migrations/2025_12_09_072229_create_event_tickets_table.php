<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();

            // Foreign key (event → events.id)
            $table->foreignId('event_id')
                ->constrained('events') // references events.id
                ->cascadeOnDelete();

            // Ticket info
            $table->string('ticket_type', 255);
            $table->decimal('price', 10, 2);

            // Quantity management
            $table->unsignedInteger('quantity_total');
            $table->unsignedInteger('quantity_sold')->default(0);
            $table->unsignedInteger('quantity_available');

            // Ticket status
            $table->boolean('is_active')->default(true);

            // Optional
            $table->dateTime('sale_end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
