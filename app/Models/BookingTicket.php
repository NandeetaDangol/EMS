<?php

namespace App\Models;

use App\Enums\BookingTicketStatus;
use Illuminate\Database\Eloquent\Model;

class BookingTicket extends Model
{
    protected $table = 'booking_tickets';

    protected $fillable = [
        'booking_id',
        'ticket_id',
        'seat_id',
        'attendee_name',
        'attendee_email',
        'attendee_phone',
        'unit_price',
        'status',
    ];

    protected $casts = [
        'status' => BookingTicketStatus::class,
        'unit_price' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function ticket()
    {
        // Fixed: remove second parameter (ticket_id)
        return $this->belongsTo(EventTicket::class, 'ticket_id');
    }

    public function seat()
    {
        // Fixed: remove second parameter (seat_id)
        return $this->belongsTo(VenueSeat::class, 'seat_id');
    }
}
