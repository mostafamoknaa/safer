<?php

namespace App\Traits;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPayments
{
    /**
     * Get all payments for this model.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    /**
     * Get total paid amount.
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get remaining amount to pay.
     */
    public function getRemainingAmountAttribute(): float
    {
        return (float) max(0, $this->total_price - $this->total_paid);
    }

    /**
     * Check if item is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->total_paid >= $this->total_price;
    }
}
