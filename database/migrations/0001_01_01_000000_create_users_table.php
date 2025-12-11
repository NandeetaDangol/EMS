<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');

            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);

            $table->string('first_name', 255);
            $table->string('last_name', 255);

            $table->string('phone', 20)->nullable();
            $table->string('profile_image', 255)->nullable();

            // User types: user, organizer, admin
            $table->enum('user_type', ['user', 'organizer', 'admin'])->default('user');
            $table->json('permissions')->nullable();

            $table->enum('status', ['active', 'suspended', 'deleted'])->default('active');
            $table->timestamp('last_login')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
