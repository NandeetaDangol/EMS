<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\EventStatus;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $events = [
            [
                'title' => 'Summer Music Festival 2025',
                'description' => 'Live music festival with top artists.',
                'start_datetime' => Carbon::parse('2025-07-15 18:00:00'),
                'end_datetime' => Carbon::parse('2025-07-15 23:00:00'),
                'booking_start' => Carbon::parse('2025-05-01 00:00:00'),
                'booking_end' => Carbon::parse('2025-07-14 23:59:59'),
                'banner_image' => 'banners/music-festival.jpg',
                'event_type' => 'concert',
                'organizer_id' => 1,
                'category_id' => 1,
                'venue_id' => 1,
                'status' => EventStatus::PUBLISHED->value,
                'custom_fields' => json_encode(['age_restriction' => '18+']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Tech Innovation Conference 2025',
                'description' => 'Technology conference with industry leaders.',
                'start_datetime' => Carbon::parse('2025-08-20 09:00:00'),
                'end_datetime' => Carbon::parse('2025-08-22 18:00:00'),
                'booking_start' => Carbon::parse('2025-06-01 00:00:00'),
                'booking_end' => Carbon::parse('2025-08-19 23:59:59'),
                'banner_image' => 'banners/tech-conference.jpg',
                'event_type' => 'conference',
                'organizer_id' => 2,
                'category_id' => 2,
                'venue_id' => 2,
                'status' => EventStatus::PUBLISHED->value,
                'custom_fields' => json_encode(['wifi_available' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'City Marathon 2025',
                'description' => 'Annual marathon with multiple race categories.',
                'start_datetime' => Carbon::parse('2025-09-10 06:00:00'),
                'end_datetime' => Carbon::parse('2025-09-10 14:00:00'),
                'booking_start' => Carbon::parse('2025-07-01 00:00:00'),
                'booking_end' => Carbon::parse('2025-09-05 23:59:59'),
                'banner_image' => 'banners/marathon.jpg',
                'event_type' => 'sports',
                'organizer_id' => 3,
                'category_id' => 3,
                'venue_id' => 3,
                'status' => EventStatus::PUBLISHED->value,
                'custom_fields' => json_encode(['medal_included' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Hamlet - Live Theater',
                'description' => 'Classic Shakespeare performance.',
                'start_datetime' => Carbon::parse('2025-10-05 19:30:00'),
                'end_datetime' => Carbon::parse('2025-10-05 22:00:00'),
                'booking_start' => Carbon::parse('2025-08-01 00:00:00'),
                'booking_end' => Carbon::parse('2025-10-05 17:00:00'),
                'banner_image' => 'banners/hamlet.jpg',
                'event_type' => 'theater',
                'organizer_id' => 4,
                'category_id' => 4,
                'venue_id' => 4,
                'status' => EventStatus::PUBLISHED->value,
                'custom_fields' => json_encode(['duration' => '150 minutes']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('events')->insert($events);

        $this->command->info('Events seeded successfully');
    }
}
