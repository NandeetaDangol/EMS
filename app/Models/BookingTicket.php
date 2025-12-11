<?php

namespace App\Models;

use App\Enums\BookingTicketStatus;
use Illuminate\Database\Eloquent\Model;

class BookingTicket extends Model
{
    protected $table = 'booking_tickets';
    protected $primaryKey = 'booking_ticket_id';

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

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function ticket()
    {
        return $this->belongsTo(EventTicket::class, 'ticket_id', 'ticket_id');
    }

    public function seat()
    {
        return $this->belongsTo(VenueSeat::class, 'seat_id', 'seat_id');
    }
}