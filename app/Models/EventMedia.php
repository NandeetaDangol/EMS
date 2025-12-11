<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    protected $table = 'event_media';
    protected $primaryKey = 'media_id';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'file_url',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
