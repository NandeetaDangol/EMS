<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    protected $table = 'event_tickets';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'event_id',
        'ticket_type',
        'price',
        'quantity_total',
        'quantity_sold',
        'quantity_available',
        'sale_end',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_total' => 'integer',
        'quantity_sold' => 'integer',
        'quantity_available' => 'integer',
        'is_active' => 'boolean',
        'sale_end' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class, 'ticket_id', 'ticket_id');
    }
}
