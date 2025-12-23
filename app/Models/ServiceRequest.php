<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_type',
        'trip_id',
        'bus_id',
        'departure_location_ar',
        'departure_location_en',
        'arrival_location_ar',
        'arrival_location_en',
        'passengers_count',
        'trip_date',
        'private_car_id',
        'duration_hours',
        'start_date',
        'total_price',
        'status',
        'notes',
        'request_reference',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'start_date' => 'date',
        'total_price' => 'decimal:2',
        'passengers_count' => 'integer',
        'duration_hours' => 'integer',
    ];

    /**
     * Get the user who made the request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trip (for bus service).
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the bus (for bus service).
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Get the private car (for private car service).
     */
    public function privateCar(): BelongsTo
    {
        return $this->belongsTo(PrivateCar::class);
    }

    /**
     * Get booked seats for this request (for bus service).
     */
    public function bookedSeats(): HasMany
    {
        return $this->hasMany(BusSeat::class);
    }

    /**
     * Generate request reference.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'SR-' . strtoupper(uniqid());
        } while (self::where('request_reference', $reference)->exists());

        return $reference;
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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (empty($request->request_reference)) {
                $request->request_reference = self::generateReference();
            }
        });
    }
}
