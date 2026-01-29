<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_commission',
        'apartment_commission',
        'car_hour_commission',
        'car_day_commission',
        'bus_commission',
        'activity_commission',
    ];

    protected $casts = [
        'hotel_commission' => 'decimal:2',
        'apartment_commission' => 'decimal:2',
        'car_hour_commission' => 'decimal:2',
        'car_day_commission' => 'decimal:2',
        'bus_commission' => 'decimal:2',
        'activity_commission' => 'decimal:2',
    ];
}
