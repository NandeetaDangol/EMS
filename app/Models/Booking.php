<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'booking_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_id',
        'booking_reference',
        'total_tickets',
        'subtotal',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'booking_date' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class, 'booking_id', 'booking_id');
    }
}
