<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Organizer extends Model
{
    protected $table = 'organizers';

    protected $fillable = [
        'user_id',
        'organization_name',
        'description',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'approval_status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
}
