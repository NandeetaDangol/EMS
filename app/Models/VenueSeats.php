<?php

namespace App\Models;

use App\Enums\SeatType;
use Illuminate\Database\Eloquent\Model;

class VenueSeat extends Model
{
    protected $table = 'venue_seats';
    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'venue_id',
        'section',
        'row',
        'seat_number',
        'seat_type',
    ];

    protected $casts = [
        'seat_type' => SeatType::class,
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class, 'seat_id', 'seat_id');
    }
}
