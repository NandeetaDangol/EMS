<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_media', function (Blueprint $table) {
            $table->bigIncrements('media_id');

            $table->unsignedBigInteger('event_id');

            $table->string('file_url', 255);
            $table->timestamp('uploaded_at')->useCurrent();

            // Foreign key 
            $table->foreign('event_id')
                ->references('event_id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_media');
    }
};
