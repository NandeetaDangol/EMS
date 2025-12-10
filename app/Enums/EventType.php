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
        return array_column(EventType::cases(), 'value');
    }
}
