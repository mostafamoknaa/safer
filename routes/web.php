<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactLinkController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HotelRoomController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('web.home');

// Public Hotels Routes
Route::get('/hotels', [\App\Http\Controllers\Web\HotelController::class, 'index'])->name('web.hotels.index');
Route::get('/hotels/{hotel}', [\App\Http\Controllers\Web\HotelController::class, 'show'])->name('web.hotels.show');
Route::get('/hotels/{hotel}/rooms', [\App\Http\Controllers\Web\HotelController::class, 'rooms'])->name('web.hotels.rooms');

// Public Events Routes
Route::get('/events', [\App\Http\Controllers\Web\EventController::class, 'index'])->name('web.events.index');
Route::get('/events/{event}', [\App\Http\Controllers\Web\EventController::class, 'show'])->name('web.events.show');

// Public Services Routes
Route::get('/services/buses', [\App\Http\Controllers\Web\ServiceController::class, 'buses'])->name('web.services.buses');
Route::get('/services/trips', [\App\Http\Controllers\Web\ServiceController::class, 'trips'])->name('web.services.trips');
Route::get('/services/trips/{trip}', [\App\Http\Controllers\Web\ServiceController::class, 'showTrip'])->name('web.services.trips.show');
Route::get('/services/private-cars', [\App\Http\Controllers\Web\ServiceController::class, 'privateCars'])->name('web.services.private-cars');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    // Bookings
    Route::get('/my-bookings', [\App\Http\Controllers\Web\BookingController::class, 'index'])->name('web.bookings.index');
    Route::get('/bookings/create', [\App\Http\Controllers\Web\BookingController::class, 'create'])->name('web.bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\Web\BookingController::class, 'store'])->name('web.bookings.store');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Web\BookingController::class, 'show'])->name('web.bookings.show');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\Web\BookingController::class, 'cancel'])->name('web.bookings.cancel');

    // Conversations
    Route::get('/conversation', [\App\Http\Controllers\Web\ConversationController::class, 'index'])->name('web.conversations.index');
    Route::post('/conversation/message', [\App\Http\Controllers\Web\ConversationController::class, 'sendMessage'])->name('web.conversations.send-message');

    // Events - Purchase Tickets
    Route::post('/events/purchase', [\App\Http\Controllers\Web\EventController::class, 'purchaseTickets'])->name('web.events.purchase');
    Route::get('/my-tickets', [\App\Http\Controllers\Web\EventController::class, 'myTickets'])->name('web.events.my-tickets');

    // Services - Requests
    Route::post('/services/bus-request', [\App\Http\Controllers\Web\ServiceController::class, 'createBusRequest'])->name('web.services.bus-request');
    Route::post('/services/private-car-request', [\App\Http\Controllers\Web\ServiceController::class, 'createPrivateCarRequest'])->name('web.services.private-car-request');
    Route::get('/my-service-requests', [\App\Http\Controllers\Web\ServiceController::class, 'myRequests'])->name('web.services.my-requests');
});

// Web Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Web\AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Web\AuthWebController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Web\AuthWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Web\AuthWebController::class, 'register']);
});

Route::post('/logout', [\App\Http\Controllers\Web\AuthWebController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', AdminDashboardController::class)
            ->name('dashboard');

        Route::resource('contact-links', ContactLinkController::class)
            ->except('show');

        Route::resource('policies', PolicyController::class)
            ->except('show');

        Route::resource('faqs', FaqController::class)
            ->except('show');

        Route::resource('hotels', HotelController::class)
            ->except('show');

        Route::resource('hotel-rooms', HotelRoomController::class)
            ->except('show');

        Route::resource('users', UserController::class)
            ->only(['index', 'destroy']);

        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])
            ->name('users.toggle');

        Route::get('users/{user}/manage-hotels', [UserController::class, 'manageHotels'])
            ->name('users.manage-hotels');
        Route::put('users/{user}/hotels', [UserController::class, 'updateHotels'])
            ->name('users.update-hotels');

        Route::get('conversations', [ConversationController::class, 'index'])
            ->name('conversations.index');
        Route::get('conversations/user/{user}', [ConversationController::class, 'getOrCreate'])
            ->name('conversations.get-or-create');
        Route::get('conversations/{conversation}', [ConversationController::class, 'show'])
            ->name('conversations.show');
        Route::post('conversations/{conversation}/message', [ConversationController::class, 'sendMessage'])
            ->name('conversations.send-message');
        Route::patch('conversations/{conversation}/close', [ConversationController::class, 'close'])
            ->name('conversations.close');
        Route::patch('conversations/{conversation}/reopen', [ConversationController::class, 'reopen'])
            ->name('conversations.reopen');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
        Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)->except(['create', 'edit']);
        Route::get('payments/create', [\App\Http\Controllers\Admin\PaymentController::class, 'create'])
            ->name('payments.create');
        Route::get('bookings/{booking}/payments/create', [\App\Http\Controllers\Admin\PaymentController::class, 'create'])
            ->name('bookings.payments.create');

        // Services routes
        Route::resource('buses', \App\Http\Controllers\Admin\BusController::class);
        Route::resource('trips', \App\Http\Controllers\Admin\TripController::class);
        Route::resource('private-cars', \App\Http\Controllers\Admin\PrivateCarController::class);
        Route::resource('service-requests', \App\Http\Controllers\Admin\ServiceRequestController::class)->except(['create', 'store']);

        // Events routes
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

        // Reports
        Route::get('reports/bookings', [\App\Http\Controllers\Admin\ReportController::class, 'bookings'])
            ->name('reports.bookings');
        Route::get('reports/payments', [\App\Http\Controllers\Admin\ReportController::class, 'payments'])
            ->name('reports.payments');
        Route::get('reports/services', [\App\Http\Controllers\Admin\ReportController::class, 'services'])
            ->name('reports.services');
        Route::get('reports/events', [\App\Http\Controllers\Admin\ReportController::class, 'events'])
            ->name('reports.events');
    });
});

// Hotel Manager Routes
Route::prefix('hotel')->name('hotel.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\Hotel\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [\App\Http\Controllers\Hotel\Auth\AuthenticatedSessionController::class, 'store'])
            ->name('login.store');
    });

    Route::middleware(['auth', 'hotel.manager'])->group(function () {
        Route::get('dashboard', \App\Http\Controllers\Hotel\DashboardController::class)
            ->name('dashboard');

        Route::resource('hotels', \App\Http\Controllers\Hotel\HotelController::class)
            ->except('show', 'destroy');

        Route::resource('hotel-rooms', \App\Http\Controllers\Hotel\HotelRoomController::class)
            ->except('show');

        Route::get('conversations', [\App\Http\Controllers\Hotel\ConversationController::class, 'index'])
            ->name('conversations.index');
        Route::get('conversations/user/{user}', [\App\Http\Controllers\Hotel\ConversationController::class, 'getOrCreate'])
            ->name('conversations.get-or-create');
        Route::get('conversations/{conversation}', [\App\Http\Controllers\Hotel\ConversationController::class, 'show'])
            ->name('conversations.show');
        Route::post('conversations/{conversation}/message', [\App\Http\Controllers\Hotel\ConversationController::class, 'sendMessage'])
            ->name('conversations.send-message');
        Route::patch('conversations/{conversation}/close', [\App\Http\Controllers\Hotel\ConversationController::class, 'close'])
            ->name('conversations.close');
        Route::patch('conversations/{conversation}/reopen', [\App\Http\Controllers\Hotel\ConversationController::class, 'reopen'])
            ->name('conversations.reopen');

        Route::post('logout', [\App\Http\Controllers\Hotel\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::resource('bookings', \App\Http\Controllers\Hotel\BookingController::class);
        Route::resource('payments', \App\Http\Controllers\Hotel\PaymentController::class)->except(['create', 'edit']);
        Route::get('payments/create', [\App\Http\Controllers\Hotel\PaymentController::class, 'create'])
            ->name('payments.create');
        Route::get('bookings/{booking}/payments/create', [\App\Http\Controllers\Hotel\PaymentController::class, 'create'])
            ->name('bookings.payments.create');

        // Hotel manager reports
        Route::get('reports/bookings', [\App\Http\Controllers\Hotel\ReportController::class, 'bookings'])
            ->name('reports.bookings');
        Route::get('reports/payments', [\App\Http\Controllers\Hotel\ReportController::class, 'payments'])
            ->name('reports.payments');
    });
});
