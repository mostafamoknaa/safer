<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'price',
        'seats_count',
        'image',
        'max_speed',
        'acceleration',
        'power',
        'notes_ar',
        'notes_en',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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
     * Get the localized notes attribute.
     */
    public function getNotesAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->notes_ar : $this->notes_en;
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
