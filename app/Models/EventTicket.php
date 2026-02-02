<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasPayments;

class EventTicket extends Model
{
    use HasFactory, HasPayments;

    protected $fillable = [
        'user_id',
        'event_id',
        'tickets_count',
        'total_price',
        'status',
        'cancellation_reason',
        'cancelled_at',
        'notes',
        'ticket_reference',
    ];

    protected $casts = [
        'tickets_count' => 'integer',
        'total_price' => 'decimal:2',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user who bought the tickets.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for these tickets.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Generate ticket reference.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'ET-' . strtoupper(uniqid());
        } while (self::where('ticket_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_reference)) {
                $ticket->ticket_reference = self::generateReference();
            }
        });
    }
}
