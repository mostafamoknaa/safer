<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'total_seats',
        'type',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'total_seats' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get trips for this bus.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get the user that owns the bus.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reserved seats for this bus.
     */
    public function reservedSeats(): HasMany
    {
        return $this->hasMany(BusSeat::class, 'trip_id', 'id')
            ->join('trips', 'bus_seats.trip_id', '=', 'trips.id')
            ->where('trips.bus_id', $this->id);
    }

    /**
     * Get the localized name attribute.
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }
}
