<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateCarMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'private_car_id',
        'file_path',
        'order_column',
    ];

    protected $casts = [
        'order_column' => 'integer',
    ];

    /**
     * Get the private car for this media.
     */
    public function privateCar(): BelongsTo
    {
        return $this->belongsTo(PrivateCar::class);
    }
}