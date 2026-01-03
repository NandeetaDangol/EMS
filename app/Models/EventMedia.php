<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventMedia extends Model
{
    protected $table = 'event_media';
    // Remove: protected $primaryKey = 'media_id';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'file_url',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
