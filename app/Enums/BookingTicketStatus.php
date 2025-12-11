<?php

namespace App\Enums;

enum BookingTicketStatus: string
{
    case ACTIVE = 'active';
    case USED = 'used';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public static function values(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, BookingTicketStatus::cases());
    }
}
