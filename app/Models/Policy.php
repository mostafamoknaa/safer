<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'body_ar',
        'body_en',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

