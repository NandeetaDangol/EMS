<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventMediaSeeder extends Seeder
{
    public function run(): void
    {
        $eventMedia = [
            ['event_id' => 1, 'file_url' => 'media/events/concert-banner.jpg', 'uploaded_at' => Carbon::now()],
            ['event_id' => 1, 'file_url' => 'media/events/concert-gallery.jpg', 'uploaded_at' => Carbon::now()],
            ['event_id' => 2, 'file_url' => 'media/events/conference-banner.jpg', 'uploaded_at' => Carbon::now()],
            ['event_id' => 2, 'file_url' => 'media/events/conference-speakers.jpg', 'uploaded_at' => Carbon::now()],
            ['event_id' => 3, 'file_url' => 'media/events/sports-banner.jpg', 'uploaded_at' => Carbon::now()],
            ['event_id' => 4, 'file_url' => 'media/events/theater-banner.jpg', 'uploaded_at' => Carbon::now()],
        ];

        DB::table('event_media')->insert($eventMedia);

        $this->command->info('Event media seeded successfully');
    }
}
