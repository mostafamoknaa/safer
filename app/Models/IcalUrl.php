<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IcalUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'hotel_room_id',
        'url',
        'name',
        'last_sync_at',
        'sync_status',
        'sync_message',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelRoom(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
