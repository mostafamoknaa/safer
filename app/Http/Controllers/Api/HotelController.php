<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\Province;
use App\Models\Booking;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Get all provinces.
     */
    public function getProvinces(): JsonResponse
    {
        $provinces = Province::where('is_active', true)
            ->orderBy('name_ar')
            ->get()
            ->map(function ($province) {
                return [
                    'id' => $province->id,
                    'name' => app()->getLocale() === 'ar' ? $province->name_ar : $province->name_en,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $provinces,
        ]);
    }

    /**
     * Get available hotels with filters.
     */
    public function getHotels(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        }
        $query = Hotel::with([
                'media',
                'province',
                'rooms' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->where('is_active', true);

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $hotels = $query->orderBy('name_ar')->get()
            ->map(function ($hotel) use ($favoriteIds) {

                $minPrice = $hotel->rooms->min('price_per_night');
                $maxPrice = $hotel->rooms->max('price_per_night');

                return [
                    // 🔹 all hotel columns
                    ...$hotel->toArray(),

                    // 🔹 localized fields
                    'is_favorite' => in_array($hotel->id, $favoriteIds),
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'about_info' => app()->getLocale() === 'ar'
                        ? $hotel->about_info_ar
                        : $hotel->about_info_en,

                    // 🔹 extra computed data
                    'rooms_count' => $hotel->rooms->count(),
                    'price' => ($minPrice !== null && $maxPrice !== null)
                        ? [
                            'min' => (float) $minPrice,
                            'max' => (float) $maxPrice,
                        ]
                        : null,
                ];
            });


        return response()->json([
            'success' => true,
            'data' => $hotels,
        ]);
    }


    /**
     * Add rating to a hotel.
     */
    public function addRating(Request $request, Hotel $hotel): JsonResponse
    {
        $request->validate([
            'rate' => 'required|numeric|min:1|max:5',
        ]);

        $hotel->rate = $request->rate;
        $hotel->save();

        return response()->json([
            'success' => true,
            'message' => __('api.hotels.rating_added'),
            'data' => [
                 'id' => $hotel->id,
                 'rating' => (float) $hotel->rate
            ]
        ]);
    }

    /**
     * Get hotel details with rooms and media.
     */
    public function getHotelDetails(Hotel $hotel): JsonResponse
    {
        if (!$hotel->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('api.hotels.hotel_not_available'),
            ], 404);
        }

        $hotel->load(['province', 'media', 'managers', 'rooms' => function ($query) {
            $query->where('is_active', true);
        }]);

        $user = auth('sanctum')->user();
        $isFavorite = false;
        if ($user) {
            $isFavorite = Favorite::where('user_id', $user->id)
                ->where('favoritable_id', $hotel->id)
                ->where('favoritable_type', Hotel::class) // Ensure correct polymorphic type if applicable, or just id if unique scope (assuming favoritable_type is needed usually, but logic in list endpoint just used id array. sticking to list endpoint logic for consistency but safer to query directly)
                ->exists(); 

             // Optimization: reuse the list logic which was:
             // $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        }
        // Let's stick to the direct query for single item efficiency
        if ($user) {
             $isFavorite = Favorite::where('user_id', $user->id)
                ->where('favoritable_id', $hotel->id)
                ->exists();
        }

        $minPrice = $hotel->rooms->min('price_per_night');
        $maxPrice = $hotel->rooms->max('price_per_night');

        $rooms = $hotel->rooms->map(function ($room) {
            $images = $room->media->where('type', 'image')->map(function ($media) {
                 return $media->file_url;
            })->values();

            return [
                'id' => $room->id,
                'price_per_night' => (float) $room->price_per_night,
                'beds_count' => $room->beds_count,
                'bathrooms_count' => $room->bathrooms_count,
                'rooms_count' => $room->rooms_count,
                'images' => $images,
            ];
        });

        $images = $hotel->media->where('type', 'image')->map(function ($media) {
            return [
                'url' => $media->file_url,
                'order' => $media->order_column,
            ];
        })->sortBy('order')->values();

        $videos = $hotel->media->where('type', 'video')->map(function ($media) {
            return [
                'url' => $media->file_url,
                'order' => $media->order_column,
            ];
        })->sortBy('order')->values();

        $managers = $hotel->managers->map(function ($manager) {
            return [
                'id' => $manager->id,
                'name' => $manager->name,
                'image' => $manager->image, // Assuming 'image' attribute exists on User model
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $hotel->id,
                'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                'lat' => $hotel->lat ? (float) $hotel->lat : null,
                'lang' => $hotel->lang ? (float) $hotel->lang : null,
                'type' => $hotel->type,
                'rating' => $hotel->rate ? (float) $hotel->rate : null,
                'services' => $hotel->services ?? [],
                'is_favorite' => $isFavorite,
                'province' => $hotel->province ? [
                    'id' => $hotel->province->id,
                    'name' => app()->getLocale() === 'ar' ? $hotel->province->name_ar : $hotel->province->name_en,
                ] : null,
                'website_url' => $hotel->website_url,
                'about_info' => app()->getLocale() === 'ar' ? $hotel->about_info_ar : $hotel->about_info_en,
                'price' => ($minPrice !== null && $maxPrice !== null)
                        ? [
                            'min' => (float) $minPrice,
                            'max' => (float) $maxPrice,
                        ]
                        : null,
                'managers' => $managers,
                'images' => $images,
                'videos' => $videos,
                'rooms' => $rooms,
            ],
        ]);
    }

    /**
     * Get hotel rooms.
     */
    public function getHotelRooms(Hotel $hotel, Request $request): JsonResponse
    {
        if (!$hotel->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('api.hotels.hotel_not_available'),
            ], 404);
        }

        $query = $hotel->rooms()->where('is_active', true);

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        // Filter by beds count
        if ($request->filled('beds_count')) {
            $query->where('beds_count', '>=', $request->beds_count);
        }

        // Filter by availability dates
        if ($request->filled('check_in_date') && $request->filled('check_out_date')) {
            $checkIn = $request->check_in_date;
            $checkOut = $request->check_out_date;

            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where(function ($bookingQuery) use ($checkIn, $checkOut) {
                    $bookingQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                        });
                })
                ->whereIn('status', ['pending', 'confirmed', 'checked_in']);
            });
        }

        $rooms = $query->orderBy('price_per_night')
            ->get()
            ->map(function ($room) {
                $images = $room->media->where('type', 'image')->map(function ($media) {
                    return $media->file_url;
                })->values();

                return [
                    'id' => $room->id,
                    'price_per_night' => (float) $room->price_per_night,
                    'beds_count' => $room->beds_count,
                    'bathrooms_count' => $room->bathrooms_count,
                    'rooms_count' => $room->rooms_count,
                    'images' => $images,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }

    /**
     * Check room availability for specific dates.
     */
    public function checkRoomAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'room_id' => 'required|exists:hotel_rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $room = HotelRoom::with('hotel')->findOrFail($request->room_id);

        if (!$room->is_active || !$room->hotel->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('api.hotels.room_not_available'),
            ], 400);
        }

        // Check for conflicting bookings
        $conflictingBookings = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('check_in_date', '<=', $request->check_in_date)
                            ->where('check_out_date', '>=', $request->check_out_date);
                    });
            })
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->exists();

        $isAvailable = !$conflictingBookings;

        $nights = max(1, \Carbon\Carbon::parse($request->check_in_date)->diffInDays($request->check_out_date));
        $totalPrice = $room->price_per_night * $nights;

        return response()->json([
            'success' => true,
            'data' => [
                'available' => $isAvailable,
                'room' => [
                    'id' => $room->id,
                    'price_per_night' => (float) $room->price_per_night,
                    'beds_count' => $room->beds_count,
                    'bathrooms_count' => $room->bathrooms_count,
                    'rooms_count' => $room->rooms_count,
                ],
                'nights' => $nights,
                'total_price' => $totalPrice,
            ],
        ]);
    }
  
   /**
     * Filter and search hotels with advanced options.
     */
      public function filterHotels(Request $request): JsonResponse
      {
        
          $user = auth('sanctum')->user();
          $favoriteIds = [];
          if ($user) {
              $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
          }
          $query = Hotel::with([
                  'province',
                  'rooms' => function ($q) {
                      $q->where('is_active', true);
                  }
              ])
              ->where('is_active', true);

          // Filter by country
          if ($request->filled('country')) {
              $query->where('country', $request->country);
          }

          // Filter by rating
          if ($request->filled('min_rating')) {
              $query->where('rate', '>=', $request->min_rating);
          }

          // Filter by type
          if ($request->filled('type')) {
              $query->where('type', $request->type);
          }

          // Filter by services
          if ($request->filled('services')) {
              $services = is_array($request->services)
                  ? $request->services
                  : [$request->services];

              foreach ($services as $service) {
                  $query->whereJsonContains('services', $service);
              }
          }

          // Search
          if ($request->filled('search')) {
              $search = $request->search;
              $query->where(function ($q) use ($search) {
                  $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
              });
          }

          $hotels = $query->get()
              ->filter(function ($hotel) use ($request) {

                  if ($request->filled('min_price') || $request->filled('max_price')) {

                      $minPrice = $hotel->rooms->min('price_per_night');
                      $maxPrice = $hotel->rooms->max('price_per_night');

                      if ($request->filled('min_price') && $maxPrice < $request->min_price) {
                          return false;
                      }

                      if ($request->filled('max_price') && $minPrice > $request->max_price) {
                          return false;
                      }
                  }

                  return true;
              })->map(function ($hotel) use ($favoriteIds) {

                  $minPrice = $hotel->rooms->min('price_per_night');
                  $maxPrice = $hotel->rooms->max('price_per_night');

                  return [
                      'id' => $hotel->id,
                      'is_favorite' => in_array($hotel->id, $favoriteIds),
                      'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                      'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                      'lat' => $hotel->lat ? (float) $hotel->lat : null,
                      'lang' => $hotel->lang ? (float) $hotel->lang : null,
                      'type' => $hotel->type,
                      'rating' => $hotel->rate ? (float) $hotel->rate : null,
                      'services' => $hotel->services ?? [],
                      'province' => $hotel->province ? [
                          'id' => $hotel->province->id,
                          'name' => app()->getLocale() === 'ar'
                              ? $hotel->province->name_ar
                              : $hotel->province->name_en,
                      ] : null,
                      'website_url' => $hotel->website_url,
                      'about_info' => app()->getLocale() === 'ar'
                          ? $hotel->about_info_ar
                          : $hotel->about_info_en,
                      'rooms_count' => $hotel->rooms->count(),
                      'price' => ($minPrice !== null && $maxPrice !== null)
                          ? [
                              'min' => (float) $minPrice,
                              'max' => (float) $maxPrice,
                          ]
                          : null,
                  ];
              })
              ->values();

          return response()->json([
              'success' => true,
              'data' => $hotels,
          ]);
    }

    /**
     * Get nearest hotels sorted by distance.
     */
    public function getNearestHotels(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lang' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lang = $request->lang;

        $user = auth('sanctum')->user();
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        }

        $hotels = Hotel::with(['province', 'rooms' => function ($q) {
                $q->where('is_active', true);
            }])
            ->select('hotels.*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) *
                  cos( radians( lat ) ) *
                  cos( radians( lang ) - radians(?) ) +
                  sin( radians(?) ) *
                  sin( radians( lat ) ) ) ) AS distance',
                [$lat, $lang, $lat]
            )
            ->having('distance', '<=', 20)
            ->when($request->filled('type'), function ($q) use ($request) {
                return $q->where('type', $request->type);
            })
            ->where('is_active', true)
            ->whereNotNull('lat')
            ->whereNotNull('lang')
            ->orderBy('distance')
            ->get()
            ->map(function ($hotel) use ($favoriteIds) {

                $minPrice = $hotel->rooms->min('price_per_night');
                $maxPrice = $hotel->rooms->max('price_per_night');

                return [
                    'id' => $hotel->id,
                    'is_favorite' => in_array($hotel->id, $favoriteIds),
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'lat' => $hotel->lat ? (float) $hotel->lat : null,
                    'lang' => $hotel->lang ? (float) $hotel->lang : null,
                    'distance' => round($hotel->distance, 2) . ' km',
                    'type' => $hotel->type,
                    'rating' => $hotel->rate ? (float) $hotel->rate : null,
                    'services' => $hotel->services ?? [],
                    'province' => $hotel->province ? [
                        'id' => $hotel->province->id,
                        'name' => app()->getLocale() === 'ar'
                            ? $hotel->province->name_ar
                            : $hotel->province->name_en,
                    ] : null,
                    'website_url' => $hotel->website_url,
                    'about_info' => app()->getLocale() === 'ar'
                        ? $hotel->about_info_ar
                        : $hotel->about_info_en,
                    'rooms_count' => $hotel->rooms->count(),
                    'price' => ($minPrice !== null && $maxPrice !== null)
                        ? [
                            'min' => (float) $minPrice,
                            'max' => (float) $maxPrice,
                        ]
                        : null,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $hotels,
        ]);
    }

    
    /**
     * Get all countries where hotels are located.
     */
    public function getCountries(): JsonResponse
    {
        $countries = Hotel::where('is_active', true)
            ->whereNotNull('country')
            ->distinct()
            ->pluck('country');

        return response()->json([
            'success' => true,
            'data' => $countries,
        ]);
    }
}

