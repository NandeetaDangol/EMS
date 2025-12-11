<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    protected $table = 'organizers';
    protected $primaryKey = 'organizer_id';

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
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id', 'organizer_id');
    }
}
