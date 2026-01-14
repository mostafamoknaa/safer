<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PrivateCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'car_model',
        'price_per_day',
        'price_per_hour',
        'seats_count',
        'max_speed',
        'acceleration',
        'power',
        'fuel_type',
        'transmission',
        'notes_ar',
        'notes_en',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'price_per_hour' => 'decimal:2',
        'seats_count' => 'integer',
        'max_speed' => 'integer',
        'acceleration' => 'decimal:2',
        'power' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get service requests for this car.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get the localized name attribute.
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get the user that owns the private car.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the localized notes attribute.
     */
    public function getNotesAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->notes_ar : $this->notes_en;
    }

    /**
     * Get media for this private car.
     */
    public function media(): HasMany
    {
        return $this->hasMany(PrivateCarMedia::class);
    }
}
