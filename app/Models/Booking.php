<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'event_id',
        'booking_reference',
        'total_tickets',
        'subtotal',
        'total_amount',
        'status',
        'booking_date',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'booking_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class, 'booking_id');
    }
}
