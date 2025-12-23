<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'guests_count',
        'rooms_count',
        'total_price',
        'price_per_night',
        'nights_count',
        'status',
        'notes',
        'admin_notes',
        'booking_reference',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'price_per_night' => 'decimal:2',
        'guests_count' => 'integer',
        'rooms_count' => 'integer',
        'nights_count' => 'integer',
    ];

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hotel for this booking.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * Get the room for this booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }

    /**
     * Get the payments for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get total paid amount.
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get remaining amount to pay.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total_price - $this->total_paid);
    }

    /**
     * Check if booking is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->total_paid >= $this->total_price;
    }

    /**
     * Generate booking reference.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'BK-' . strtoupper(uniqid());
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateReference();
            }

            // Calculate nights count
            if ($booking->check_in_date && $booking->check_out_date) {
                $booking->nights_count = max(1, $booking->check_in_date->diffInDays($booking->check_out_date));
            }

            // Calculate total price if not set
            if (empty($booking->total_price) && $booking->price_per_night && $booking->nights_count) {
                $booking->total_price = $booking->price_per_night * $booking->nights_count * $booking->rooms_count;
            }
        });

        static::updating(function ($booking) {
            // Recalculate nights count if dates changed
            if ($booking->isDirty(['check_in_date', 'check_out_date'])) {
                $booking->nights_count = max(1, $booking->check_in_date->diffInDays($booking->check_out_date));
            }

            // Recalculate total price if dates or price changed
            if ($booking->isDirty(['check_in_date', 'check_out_date', 'price_per_night', 'rooms_count'])) {
                if ($booking->price_per_night && $booking->nights_count) {
                    $booking->total_price = $booking->price_per_night * $booking->nights_count * $booking->rooms_count;
                }
            }
        });
    }
}
