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
    Route::post('/update-fcm-token', [NotificationController::class, 'updateFcmToken']);

    // Services routes
    Route::get('/services/buses', [\App\Http\Controllers\Api\ServiceController::class, 'getBuses']);
    Route::get('/services/trips', [\App\Http\Controllers\Api\ServiceController::class, 'getTrips']);
    Route::get('/services/trips/{trip}', [\App\Http\Controllers\Api\ServiceController::class, 'getTripDetails']);
    Route::get('/services/trips/{trip}/unavailable-seats', [\App\Http\Controllers\Api\ServiceController::class, 'getUnavailableSeats']);
    Route::post('/services/trips/{trip}/toggle-seats', [\App\Http\Controllers\Api\ServiceController::class, 'toggleSeatStatus']);
    Route::get('/services/private-cars', [\App\Http\Controllers\Api\ServiceController::class, 'getPrivateCars']);
    Route::post('/services/bus-request', [\App\Http\Controllers\Api\ServiceController::class, 'createBusRequest']);
    Route::post('/services/private-car-request', [\App\Http\Controllers\Api\ServiceController::class, 'createPrivateCarRequest']);
    Route::post('/services/reservation', [\App\Http\Controllers\Api\ServiceController::class, 'createReservation']);
    Route::get('/services/master', [\App\Http\Controllers\Api\ServiceController::class, 'getMasterServices']);
    Route::get('/services/my-requests', [\App\Http\Controllers\Api\ServiceController::class, 'getUserRequests']);

    // Events routes
    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'getEvents']);
    Route::get('/events/nearby', [\App\Http\Controllers\Api\EventController::class, 'getNearbyEvents']);
    Route::get('/events/my-tickets', [\App\Http\Controllers\Api\EventController::class, 'getUserTickets']);
    Route::get('/events/{event}', [\App\Http\Controllers\Api\EventController::class, 'getEventDetails']);
    Route::post('/events/purchase', [\App\Http\Controllers\Api\EventController::class, 'purchaseTickets']);
    Route::delete('/events/{event}' , [\App\Http\Controllers\Api\EventController::class, 'deleteEvent']);

    // Hotels routes
    Route::get('/hotels', [\App\Http\Controllers\Api\HotelController::class, 'getHotels']);
    Route::get('/hotels/{hotel}', [\App\Http\Controllers\Api\HotelController::class, 'getHotelDetails']);
    Route::get('/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\HotelController::class, 'getHotelRooms']);
    Route::get('/rooms/{room}', [\App\Http\Controllers\Api\HotelController::class, 'getRoomDetails']);
    Route::post('/hotels/{hotel}/rate', [\App\Http\Controllers\Api\HotelController::class, 'addRating']);
    Route::post('/hotels/{hotel}/review', [\App\Http\Controllers\Api\HotelController::class, 'addReview']);
    Route::post('/hotels/check-availability', [\App\Http\Controllers\Api\HotelController::class, 'checkRoomAvailability']);

    // Bookings routes
    Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'getUserBookings']);
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Api\BookingController::class, 'getBookingDetails']);
    Route::post('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'createBooking']);
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Api\BookingController::class, 'cancelBooking']);

    // Cancellation routes
    Route::get('/cancellation/reasons', [\App\Http\Controllers\Api\CancellationController::class, 'getCancellationReasons']);
    Route::post('/cancellation/hotel/{booking}', [\App\Http\Controllers\Api\CancellationController::class, 'cancelHotelBooking']);
    Route::post('/cancellation/service/{serviceRequest}', [\App\Http\Controllers\Api\CancellationController::class, 'cancelServiceRequest']);
    Route::post('/cancellation/event/{eventTicket}', [\App\Http\Controllers\Api\CancellationController::class, 'cancelEventTicket']);

    // Payments routes`
    Route::get('/payments/verify', [\App\Http\Controllers\Api\PaymentController::class, 'verifyPayment'])->name('api.payments.verify');
    Route::get('/payments/callback/success', [\App\Http\Controllers\Api\PaymentController::class, 'paymentSuccess'])->name('api.payments.callback.success');
    Route::get('/payments/callback/fail', [\App\Http\Controllers\Api\PaymentController::class, 'paymentFail'])->name('api.payments.callback.fail');
    Route::get('/payments/callback/pending', [\App\Http\Controllers\Api\PaymentController::class, 'paymentPending'])->name('api.payments.callback.pending');
    Route::post('/payments/webhook_json', [\App\Http\Controllers\Api\PaymentController::class, 'webhook'])->name('api.payments.webhook');
    
    Route::get('/payments', [\App\Http\Controllers\Api\PaymentController::class, 'getUserPayments']);
    Route::post('/payments', [\App\Http\Controllers\Api\PaymentController::class, 'createPayment']);
    Route::get('/payments/{payment}', [\App\Http\Controllers\Api\PaymentController::class, 'getPaymentDetails']);

    // Favorites routes
    Route::get('/favorites', [\App\Http\Controllers\Api\FavoriteController::class, 'getFavorites']);
    Route::post('/favorites/toggle', [\App\Http\Controllers\Api\FavoriteController::class, 'toggle']);

    // Support routes
    Route::get('/support/contact', [\App\Http\Controllers\Api\SupportController::class, 'getContactInfo']);
    Route::get('/policies', [\App\Http\Controllers\Api\SupportController::class, 'getPolicies']);
    Route::get('/terms-and-conditions', [\App\Http\Controllers\Api\SupportController::class, 'getTerms']);
    Route::post('/support/contact', [\App\Http\Controllers\Api\SupportController::class, 'sendMessage']);
    Route::get('/support/faq', [\App\Http\Controllers\Api\SupportController::class, 'getFAQ']);
    Route::post('/support/insert-contacts', [\App\Http\Controllers\Api\SupportController::class, 'insertContactLinks']);
    Route::post('/support/insert-faqs', [\App\Http\Controllers\Api\SupportController::class, 'insertFAQs']);

    // User Activities routes
    Route::get('/user/activities', [\App\Http\Controllers\Api\UserActivityController::class, 'getUserActivities']);
    Route::get('/user/my-activities', [\App\Http\Controllers\Api\BookingController::class, 'getUserEventBookings']);
    Route::post('/user/activities', [\App\Http\Controllers\Api\UserActivityController::class, 'store']);
    Route::post('/user/activities/{activity}', [\App\Http\Controllers\Api\UserActivityController::class, 'update']);

    // User Hotels routes
    Route::get('/user/hotels', [\App\Http\Controllers\Api\UserHotelController::class, 'getUserHotels']);
    Route::post('/user/hotels', [\App\Http\Controllers\Api\UserHotelController::class, 'store']);
    Route::get('/user/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'show']);
    Route::post('/user/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'update']);
    Route::post('/user/hotels/{hotel}/clone', [\App\Http\Controllers\Api\UserHotelController::class, 'clone']);
    Route::get('/user/hotels/{hotel}/bookings', [\App\Http\Controllers\Api\UserHotelController::class, 'getBookings']);
    Route::get('/user/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\UserHotelController::class, 'getRooms']);

    // User Hotel Rooms
    Route::post('/user/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'store']);
    Route::post('/user/hotels/{hotel}/rooms/bulk-update', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'bulkUpdate']);
    Route::post('/user/hotels/{hotel}/rooms/mass-update', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'massUpdate']);
    Route::post('/user/hotels/{hotel}/rooms/bulk-delete', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'bulkDelete']);
    Route::post('/user/rooms/{room}', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'update']);
    Route::delete('/user/rooms/{room}', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'destroy']);
    Route::post('/user/rooms/{room}/clone', [\App\Http\Controllers\Api\UserHotelRoomController::class, 'clone']);
    Route::delete('/user/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'destroy']);

    // User Buses
    Route::apiResource('user/buses', \App\Http\Controllers\Api\UserBusController::class);
    Route::post('/user/buses/{bus}/clone', [\App\Http\Controllers\Api\UserBusController::class, 'clone']);

    // User Trips
    Route::apiResource('user/trips', \App\Http\Controllers\Api\UserTripController::class);
    Route::post('/user/trips/{trip}/clone', [\App\Http\Controllers\Api\UserTripController::class, 'clone']);

    // User Cars
    Route::apiResource('user/cars', \App\Http\Controllers\Api\UserCarController::class);
    Route::post('user/cars-update/{car}', [\App\Http\Controllers\Api\UserCarController::class, 'update']);
    Route::post('/user/cars/{car}/clone', [\App\Http\Controllers\Api\UserCarController::class, 'clone']);

    // User Events
    Route::apiResource('user/events', \App\Http\Controllers\Api\UserEventController::class);
    Route::post('/user/events/{event}/clone', [\App\Http\Controllers\Api\UserEventController::class, 'clone']);

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
Route::get('/hotels-filter', [\App\Http\Controllers\Api\HotelController::class, 'filterHotels']);
Route::get('/hotels', [\App\Http\Controllers\Api\HotelController::class, 'getHotels']);
Route::get('/hotels/{hotel}', [\App\Http\Controllers\Api\HotelController::class, 'getHotelDetails']);
Route::get('/hotels/{hotel}/rooms', [\App\Http\Controllers\Api\HotelController::class, 'getHotelRooms']);

// Explore places
Route::get('/explore/popular', [\App\Http\Controllers\Api\ExploreController::class, 'getPopularPlaces']);
Route::get('/explore/nearby', [\App\Http\Controllers\Api\ExploreController::class, 'getNearbyPlaces']);



    // Payments routes`
    Route::get('/payments/verify', [\App\Http\Controllers\Api\PaymentController::class, 'verifyPayment'])->name('api.payments.verify');
    Route::get('/payments/callback/success', [\App\Http\Controllers\Api\PaymentController::class, 'paymentSuccess'])->name('api.payments.callback.success');
    Route::get('/payments/callback/fail', [\App\Http\Controllers\Api\PaymentController::class, 'paymentFail'])->name('api.payments.callback.fail');
    Route::get('/payments/callback/pending', [\App\Http\Controllers\Api\PaymentController::class, 'paymentPending'])->name('api.payments.callback.pending');
    Route::post('/payments/webhook_json', [\App\Http\Controllers\Api\PaymentController::class, 'webhook'])->name('api.payments.webhook');
