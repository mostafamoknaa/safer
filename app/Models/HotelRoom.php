<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'type',
        'price_per_night',
        'cleaning_fee',
        'service_fee',
        'beds_count',
        'bathrooms_count',
        'rooms_count',
        'is_active',
        'checkin_time',
        'checkout_time',
        'blocked_slots',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'blocked_slots' => 'array',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(HotelMedia::class, 'room_id')->orderBy('order_column');
    }

    /**
     * Get bookings for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}
