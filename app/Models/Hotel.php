<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'province_id',
        'type',
        'website_url',
        'about_info_ar',
        'about_info_en',
        'services',
        'rate',
        'is_active',
        'lat',
        'lang',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'services' => 'array',
        'rate' => 'decimal:1',
        'lat' => 'float',
        'lang' => 'float',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(HotelMedia::class)->whereNull('room_id')->orderBy('order_column');
    }

    /**
     * Get the managers of this hotel.
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'hotel_managers', 'hotel_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get conversations for this hotel.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get bookings for this hotel.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
