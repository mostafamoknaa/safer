<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'location_ar',
        'location_en',
        'location_url',
        'event_date',
        'description_ar',
        'description_en',
        'price',
        'available_tickets',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'decimal:2',
        'available_tickets' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get tickets for this event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class);
    }

    /**
     * Get confirmed tickets count.
     */
    public function getSoldTicketsCountAttribute(): int
    {
        return (int) $this->tickets()
            ->where('status', 'confirmed')
            ->sum('tickets_count');
    }

    /**
     * Get remaining tickets count.
     */
    public function getRemainingTicketsAttribute(): int
    {
        return max(0, $this->available_tickets - $this->sold_tickets_count);
    }

    /**
     * Get localized name attribute.
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get localized location attribute.
     */
    public function getLocationAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->location_ar : $this->location_en;
    }

    /**
     * Get localized description attribute.
     */
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }
}
