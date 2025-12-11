<?php

namespace App\Enums;

enum SeatType: string
{
    case REGULAR = 'regular';
    case VIP = 'vip';
    case PREMIUM = 'premium';
    case ACCESSIBLE = 'accessible';

    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, SeatType::cases());
    }
}
