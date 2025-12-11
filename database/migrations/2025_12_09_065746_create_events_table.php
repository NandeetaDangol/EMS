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
            $table->bigIncrements('event_id');

            // Foreign Keys
            $table->unsignedBigInteger('organizer_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('venue_id');

            // Event Info
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');

            $table->enum('event_type', [
                'concert',
                'conference',
                'sports',
                'theater',
                'other'
            ])->default('other');

            $table->enum('status', EventStatus::values())
                ->default(EventStatus::DRAFT->value);

            $table->string('banner_image')->nullable();

            // Custom Fields
            $table->json('custom_fields')->nullable();

            $table->timestamps();

            // Foreign key 
            $table->foreign('organizer_id')
                ->references('organizer_id')
                ->on('organizers')
                ->onDelete('restrict');

            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories')
                ->onDelete('restrict');

            $table->foreign('venue_id')
                ->references('venue_id')
                ->on('venues')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
