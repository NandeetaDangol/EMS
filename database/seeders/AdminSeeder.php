<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'email' => 'admin@event.com',
            'password' => 'password123',
            'user_type' => 'admin',
            'status' => 'active',
        ]);
    }
}
