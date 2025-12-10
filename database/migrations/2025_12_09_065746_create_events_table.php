<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {

            // Primary Key
            $table->bigIncrements('event_id');

            // Foreign Keys
            $table->unsignedBigInteger('organizer_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('venue_id');

            // Event Info
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('terms_conditions')->nullable();

            // Date & Time
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('booking_start');
            $table->dateTime('booking_end');

            // Enums
            $table->enum('event_type', [
                'concert',
                'conference',
                'sports',
                'theater',
                'other'
            ])->default('other');

            $table->enum('status', [
                'draft',
                'published',
                'unpublished',
                'cancelled',
                'completed'
            ])->default('draft');

            // Media
            $table->string('banner_image')->nullable();

            // Capacity
            $table->unsignedInteger('total_capacity');
            $table->unsignedInteger('available_capacity');

            // Custom Fields
            $table->json('custom_fields')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('organizer_id');
            $table->index(['status', 'start_datetime']);
            $table->index('category_id');

            // Foreign Key Constraints
            $table->foreign('organizer_id')
                ->references('organizer_id')
                ->on('organizers')
                ->onDelete('cascade');

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
