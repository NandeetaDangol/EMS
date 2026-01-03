<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venue_seats', function (Blueprint $table) {
            $table->id();

            // Foreign Key - venue reference
            $table->string('section', 50);
            $table->string('row', 20);
            $table->string('seat_number', 10);

            $table->enum('seat_type', ['regular', 'vip', 'premium', 'accessible'])
                ->default('regular');
            $table->timestamps();

            $table->foreignId('venue_id')
                ->constrained('venues')
                ->cascadeOnDelete();

            $table->unique(['venue_id', 'section', 'row', 'seat_number'], 'unique_seat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_seats');
    }
};
