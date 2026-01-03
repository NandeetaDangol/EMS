<?php

namespace App\Models;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'organizer_id',
        'category_id',
        'venue_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'booking_start',
        'booking_end',
        'event_type',
        'status',
        'banner_image',
        'custom_fields',
    ];

    protected $casts = [
        'status' => EventStatus::class,
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'booking_start' => 'datetime',
        'booking_end' => 'datetime',
        'custom_fields' => 'array',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function eventMedia()
    {
        return $this->hasMany(EventMedia::class, 'event_id');
    }

    public function eventTickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'event_id');
    }
}
