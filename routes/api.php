<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/me', [LoginController::class, 'me']);
    Route::post('/update-profile' , [LoginController::class, 'updateProfile']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user(),
            ],
        ]);
    });

    // Conversation routes
    Route::get('/conversation', [ConversationController::class, 'getConversation']);
    Route::post('/conversation/message', [ConversationController::class, 'sendMessage']);
    Route::get('/conversation/unread-count', [ConversationController::class, 'unreadCount']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);

    // Services routes
    Route::get('/services/buses', [\App\Http\Controllers\Api\ServiceController::class, 'getBuses']);
    Route::get('/services/trips', [\App\Http\Controllers\Api\ServiceController::class, 'getTrips']);
    Route::get('/services/trips/{trip}', [\App\Http\Controllers\Api\ServiceController::class, 'getTripDetails']);
    Route::get('/services/private-cars', [\App\Http\Controllers\Api\ServiceController::class, 'getPrivateCars']);
    Route::post('/services/bus-request', [\App\Http\Controllers\Api\ServiceController::class, 'createBusRequest']);
    Route::post('/services/private-car-request', [\App\Http\Controllers\Api\ServiceController::class, 'createPrivateCarRequest']);
    Route::post('/services/reservation', [\App\Http\Controllers\Api\ServiceController::class, 'createReservation']);
    Route::get('/services/my-requests', [\App\Http\Controllers\Api\ServiceController::class, 'getUserRequests']);

    // Events routes
    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'getEvents']);
    Route::get('/events/nearby', [\App\Http\Controllers\Api\EventController::class, 'getNearbyEvents']);
    Route::get('/events/my-tickets', [\App\Http\Controllers\Api\EventController::class, 'getUserTickets']);
    Route::get('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'getEventDetails']);
    Route::post('/events/purchase', [\App\Http\Controllers\Api\EventController::class, 'purchaseTickets']);

    // Hotels routes
    Route::get('/hotels', [\App\Http\Controllers\Api\HotelController::class, 'getHotels']);
    Route::get('/hotels/{hotel}', [\App\Http\Controllers\Api\HotelController::class, 'getHotelDetails']);
    Route::get('/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\HotelController::class, 'getHotelRooms']);
    Route::post('/hotels/{hotel}/rate', [\App\Http\Controllers\Api\HotelController::class, 'addRating']);
    Route::post('/hotels/{hotel}/review', [\App\Http\Controllers\Api\HotelController::class, 'addReview']);
    Route::post('/hotels/check-availability', [\App\Http\Controllers\Api\HotelController::class, 'checkRoomAvailability']);

    // Bookings routes
    Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'getUserBookings']);
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Api\BookingController::class, 'getBookingDetails']);
    Route::post('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'createBooking']);
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Api\BookingController::class, 'cancelBooking']);

    // Payments routes
    Route::get('/payments', [\App\Http\Controllers\Api\PaymentController::class, 'getUserPayments']);
    Route::get('/payments/{payment}', [\App\Http\Controllers\Api\PaymentController::class, 'getPaymentDetails']);
    Route::post('/payments', [\App\Http\Controllers\Api\PaymentController::class, 'createPayment']);

    // Favorites routes
    Route::get('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'getFavorites']);
    Route::post('/favorites/toggle', [\App\Http\Controllers\Api\FavoriteController::class, 'toggle']);

    // Support routes
    Route::get('/support/contact', [\App\Http\Controllers\Api\SupportController::class, 'getContactInfo']);
    Route::post('/support/contact', [\App\Http\Controllers\Api\SupportController::class, 'sendMessage']);
    Route::get('/support/faq', [\App\Http\Controllers\Api\SupportController::class, 'getFAQ']);
    Route::post('/support/insert-contacts', [\App\Http\Controllers\Api\SupportController::class, 'insertContactLinks']);
    Route::post('/support/insert-faqs', [\App\Http\Controllers\Api\SupportController::class, 'insertFAQs']);

    // Wallet routes
    Route::get('/wallet', [\App\Http\Controllers\Api\WalletController::class, 'getWallet']);
    Route::get('/wallet/transactions', [\App\Http\Controllers\Api\WalletController::class, 'getTransactions']);
    Route::post('/wallet/add-money', [\App\Http\Controllers\Api\WalletController::class, 'addMoney']);

    // Voucher routes
    Route::get('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'getVouchers']);
    Route::get('/vouchers/my-vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'getUserVouchers']);
    Route::post('/vouchers/validate', [\App\Http\Controllers\Api\VoucherController::class, 'validateVoucher']);
    Route::post('/vouchers/insert-samples', [\App\Http\Controllers\Api\VoucherController::class, 'insertSampleVouchers']);
});

// Public routes for services and events
Route::get('/services/buses', [\App\Http\Controllers\Api\ServiceController::class, 'getBuses']);
Route::get('/services/trips', [\App\Http\Controllers\Api\ServiceController::class, 'getTrips']);
Route::get('/services/trips/{trip}', [\App\Http\Controllers\Api\ServiceController::class, 'getTripDetails']);
Route::get('/services/private-cars', [\App\Http\Controllers\Api\ServiceController::class, 'getPrivateCars']);
Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'getEvents']);
Route::get('/events/nearby', [\App\Http\Controllers\Api\EventController::class, 'getNearbyEvents']);
Route::get('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'getEventDetails']);

// Public routes for hotels and provinces
Route::get('/provinces', [\App\Http\Controllers\Api\HotelController::class, 'getProvinces']);
Route::get('/hotels-countries', [\App\Http\Controllers\Api\HotelController::class, 'getCountries']);
Route::get('/hotels/nearest', [\App\Http\Controllers\Api\HotelController::class, 'getNearestHotels']);
Route::get('/hotels/filter', [\App\Http\Controllers\Api\HotelController::class, 'filterHotels']);
Route::get('/hotels', [\App\Http\Controllers\Api\HotelController::class, 'getHotels']);
Route::get('/hotels/{hotel}', [\App\Http\Controllers\Api\HotelController::class, 'getHotelDetails']);
Route::get('/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\HotelController::class, 'getHotelRooms']);

// Explore places
Route::get('/explore/popular', [\App\Http\Controllers\Api\ExploreController::class, 'getPopularPlaces']);
Route::get('/explore/nearby', [\App\Http\Controllers\Api\ExploreController::class, 'getNearbyPlaces']);
