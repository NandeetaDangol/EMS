<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EventStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // primary key

            // Event Info
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');
            $table->string('banner_image')->nullable();

            // Custom Fields
            $table->json('custom_fields')->nullable();

            $table->timestamps();

            $table->enum('event_type', [
                'concert',
                'conference',
                'sports',
                'theater',
                'other'
            ])->default('other');

            // Foreign Keys
            $table->foreignId('organizer_id')
                ->constrained('organizers')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

             $table->foreignId('venue_id')
                ->constrained('venues')
                ->restrictOnDelete();

            $table->enum('status', EventStatus::values())
                ->default(EventStatus::DRAFT->value);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
