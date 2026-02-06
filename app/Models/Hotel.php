<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'province_id',
        'country',
        'type',
        'website_url',
        'about_info_ar',
        'about_info_en',
        'services',
        'rate',
        'is_active',
        'lat',
        'lang',
        'price',
        'phone',
        'phone_2',
        'description_ar',
        'description_en',
        'user_id',
        'identity_images',
        'lease_agreement',
        'schedule_type',
        'hourly_price',
        'booking_settings',
        'week_schedule',
        'blocked_dates',
        'cancellation_policy',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'services' => 'array',
        'rate' => 'decimal:1',
        'lat' => 'float',
        'lang' => 'float',
        'price' => 'decimal:2',
        'hourly_price' => 'decimal:2',
        'identity_images' => 'array',
        'lease_agreement' => 'array',
        'booking_settings' => 'array',
        'week_schedule' => 'array',
        'blocked_dates' => 'array',
        'cancellation_policy' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(HotelMedia::class)->whereNull('room_id')->orderBy('order_column');
    }

    /**
     * Get the managers of this hotel.
     */
    public function managers()
    {
        return $this->belongsToMany(User::class, 'hotel_managers', 'hotel_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get conversations for this hotel.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Get bookings for this hotel.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get reviews for this hotel.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating from reviews.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Get total reviews count.
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
  public function favorites()
    {
        return $this->hasMany(Favorite::class, 'favoritable_id');
    }

    /**
     * Services Mutator
     * Automatically converts string inputs from admin to object format
     */
    public function setServicesAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['services'] = json_encode([]);
            return;
        }

        $transformed = [];
        $services = is_array($value) ? $value : json_decode($value, true);

        if ($services) {
            foreach ($services as $s) {
                // If it's already an object-like array, keep it but ensure id/image
                if (is_array($s) && (isset($s['id']) || isset($s['image']))) {
                    $transformed[] = $s;
                    continue;
                }

                // Look up in database
                $model = \App\Models\Service::where('name_en', $s)
                    ->orWhere('name_ar', $s)
                    ->orWhere('id', $s)
                    ->first();

                if ($model) {
                    $transformed[] = [
                        'id' => $model->id,
                        'image' => $model->image,
                        'name_ar' => $model->name_ar,
                        'name_en' => $model->name_en,
                    ];
                } else {
                    // Custom service
                    $transformed[] = ['name_en' => $s, 'name_ar' => $s];
                }
            }
        }

        $this->attributes['services'] = json_encode($transformed);
    }

    /**
     * Services Accessor
     * Returns simple strings for admin panel to maintain compatibility
     */
    public function getServicesAttribute($value)
    {
        $services = json_decode($value, true) ?: [];

        // If we are in admin context, return simple slugs/names
        if (request()->is('admin/*') || request()->is('*/admin/*')) {
            return collect($services)->map(function ($s) {
                if (is_array($s) && isset($s['id'])) {
                    $model = \App\Models\Service::find($s['id']);
                    return $model ? $model->name_en : null;
                }
                return is_array($s) ? ($s['name_en'] ?? ($s['name_ar'] ?? null)) : $s;
            })->filter()->values()->toArray();
        }

        return $services;
    }
  
    public function icalUrls(): HasMany
    {
        return $this->hasMany(IcalUrl::class);
    }

}
