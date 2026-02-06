<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'trip_id',
        'seat_number',
    ];

    protected $casts = [
        'service_request_id' => 'integer',
        'trip_id' => 'integer',
    ];

    /**
     * Get the service request for this seat.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the trip for this seat.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
