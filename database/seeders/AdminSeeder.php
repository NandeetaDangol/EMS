<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@event.com',
            'password' => bcrypt('password123'),
            'user_type' => 'admin',
            'status' => 'active',
        ]);
    }
}
