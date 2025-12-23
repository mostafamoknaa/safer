<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'is_active',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the hotels managed by this user.
     */
    public function managedHotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_managers', 'user_id', 'hotel_id')
            ->withTimestamps();
    }

    /**
     * Check if user manages a specific hotel.
     */
    public function managesHotel($hotelId): bool
    {
        return $this->managedHotels()->where('hotels.id', $hotelId)->exists();
    }

    /**
     * Check if user is a hotel manager.
     */
    public function isHotelManager(): bool
    {
        return $this->managedHotels()->count() > 0;
    }

    /**
     * Get bookings for this user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get service requests for this user.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get event tickets for this user.
     */
    public function eventTickets()
    {
        return $this->hasMany(EventTicket::class);
    }
}

