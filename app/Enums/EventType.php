<?php

namespace App\Enums;

enum EventType: string
{
    case CONCERT = 'concert';
    case CONFERENCE = 'conference';
    case SPORTS = 'sports';
    case THEATER = 'theater';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, EventType::cases());
    }
}
