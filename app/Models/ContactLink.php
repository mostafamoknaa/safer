<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title_ar',
        'title_en',
        'url',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

