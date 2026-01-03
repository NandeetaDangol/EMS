<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'profile_image',
        'user_type',
        'permissions',
        'status',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'   => 'hashed',
        'last_login' => 'datetime',
        'status' => 'string', // Optional but recommended
        'permissions' => 'array',
    ];

    public function organizer()
    {
        return $this->hasOne(Organizer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /* ================= ROLE HELPERS ================= */

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isOrganizer(): bool
    {
        return $this->user_type === 'organizer';
    }

    public function isUser(): bool
    {
        return $this->user_type === 'user';
    }
}
