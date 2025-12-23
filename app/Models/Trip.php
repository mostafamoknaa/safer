<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_location_ar',
        'departure_location_en',
        'arrival_location_ar',
        'arrival_location_en',
        'bus_id',
        'price',
        'trip_date',
        'trip_time',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bus for this trip.
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get service requests for this trip.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get booked seats for this trip.
     */
    public function bookedSeats(): HasMany
    {
        return $this->hasMany(BusSeat::class);
    }

    /**
     * Get available seats count for this trip.
     */
    public function getAvailableSeatsCountAttribute(): int
    {
        $bookedSeats = $this->bookedSeats()->distinct('seat_number')->count();
        return max(0, $this->bus->total_seats - $bookedSeats);
    }

    /**
     * Get booked seat numbers for this trip.
     */
    public function getBookedSeatNumbersAttribute(): array
    {
        return $this->bookedSeats()->pluck('seat_number')->toArray();
    }

    /**
     * Get localized departure location.
     */
    public function getDepartureLocationAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->departure_location_ar : $this->departure_location_en;
    }

    /**
     * Get localized arrival location.
     */
    public function getArrivalLocationAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->arrival_location_ar : $this->arrival_location_en;
    }
}
