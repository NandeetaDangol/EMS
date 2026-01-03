<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('organizers')->insert([
            [
                'user_id' => 2,  // Event Master (organizer1@event.com)
                'organization_name' => 'Event Masters Pvt Ltd',
                'description' => 'Professional event management company',
                'approval_status' => 'approved',
                'approved_by' => 1,  // Approved by admin
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'organization_name' => 'Tech Events Nepal',
                'description' => 'Technology conference organizers',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'organization_name' => 'Sports Events Ltd',
                'description' => 'Sports event management',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'organization_name' => 'Cultural Arts Society',
                'description' => 'Theater and cultural events',
                'approval_status' => 'approved',
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('Organizers seeded successfully');
    }
}
