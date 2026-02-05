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
use App\Http\Controllers\Admin\MasterServiceController;
use App\Http\Controllers\Web\PrivateCarController;
use App\Http\Controllers\Web\ProfileController; // Import ProfileController
use App\Http\Controllers\Web\ContactController; // Import ContactController
use Illuminate\Support\Facades\Route;

// Public Services Routes
Route::get('/services/buses', [
    \App\Http\Controllers\Web\ServiceController::class,
    'buses'
])->name('web.services.buses');
Route::get('/services/trips', [
    \App\Http\Controllers\Web\ServiceController::class,
    'trips'
])->name('web.services.trips');
Route::get('/services/trips/{trip}', [
    \App\Http\Controllers\Web\ServiceController::class,
    'showTrip'
])->name('web.services.trips.show');
Route::get('/services/private-cars', [
    \App\Http\Controllers\Web\ServiceController::class,
    'privateCars'
])->name('web.services.private-cars');

// New Private Cars Routes
Route::get('/private-cars', [PrivateCarController::class, 'index'])->name('web.private_cars.index');
Route::get('/private-cars/{car}', [PrivateCarController::class, 'show'])->name('web.private_cars.show');

// Contact Us
Route::get('/contact-us', [ContactController::class, 'index'])->name('web.contact');
Route::post('/contact-us', [ContactController::class, 'store'])->name('web.contact.store');

// Bus Booking Routes
Route::get('/buses/search', [\App\Http\Controllers\Web\BusBookingController::class, 'search'])->name('web.buses.search');
Route::match(['get', 'post'], '/buses/search-results', [\App\Http\Controllers\Web\BusBookingController::class, 'searchResults'])->name('web.buses.search.results');
Route::get('/buses/{trip}/select-seat', [\App\Http\Controllers\Web\BusBookingController::class, 'selectSeat'])->name('web.buses.select-seat');
Route::post('/buses/{trip}/confirm-seat', [\App\Http\Controllers\Web\BusBookingController::class, 'confirmSeat'])->name('web.buses.confirm-seat')->middleware('auth');
Route::get('/buses/payment', [\App\Http\Controllers\Web\BusBookingController::class, 'payment'])->name('web.buses.payment')->middleware('auth');
Route::post('/buses/payment', [\App\Http\Controllers\Web\BusBookingController::class, 'processPayment'])->name('web.buses.process-payment')->middleware('auth');
Route::get('/buses/confirmation/{serviceRequest}', [\App\Http\Controllers\Web\BusBookingController::class, 'confirmation'])->name('web.buses.confirmation')->middleware('auth');

// Public Website Routes
Route::get('/', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('web.home');

// Public Hotels Routes
Route::get('/hotels', [\App\Http\Controllers\Web\HotelController::class, 'index'])->name('web.hotels.index');
Route::get('/hotels/{hotel}', [\App\Http\Controllers\Web\HotelController::class, 'show'])->name('web.hotels.show');
Route::get('/hotels/{hotel}/rooms', [
    \App\Http\Controllers\Web\HotelController::class,
    'rooms'
])->name('web.hotels.rooms');

// Hotel Collections (Show All)
Route::get('/popular-hotels', [
    \App\Http\Controllers\Web\HotelController::class,
    'popular'
])->name('web.hotels.popular');
Route::get('/nearby-hotels', [\App\Http\Controllers\Web\HotelController::class, 'nearby'])->name('web.hotels.nearby');
Route::get('/discovery', [\App\Http\Controllers\Web\HotelController::class, 'discovery'])->name('web.hotels.discovery');

// Public Events Routes
Route::get('/events', [\App\Http\Controllers\Web\EventController::class, 'index'])->name('web.events.index');
Route::get('/events/{event}', [\App\Http\Controllers\Web\EventController::class, 'show'])->name('web.events.show');

// Public Services Routes
Route::get('/services/buses', [
    \App\Http\Controllers\Web\ServiceController::class,
    'buses'
])->name('web.services.buses');
Route::get('/services/trips', [
    \App\Http\Controllers\Web\ServiceController::class,
    'trips'
])->name('web.services.trips');
Route::get('/services/trips/{trip}', [
    \App\Http\Controllers\Web\ServiceController::class,
    'showTrip'
])->name('web.services.trips.show');
Route::get('/services/private-cars', [
    \App\Http\Controllers\Web\ServiceController::class,
    'privateCars'
])->name('web.services.private-cars');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    // Bookings
    Route::get('/my-bookings', [\App\Http\Controllers\Web\BookingController::class, 'index'])->name('web.bookings.index');
    Route::get('/bookings/create', [
        \App\Http\Controllers\Web\BookingController::class,
        'create'
    ])->name('web.bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\Web\BookingController::class, 'store'])->name('web.bookings.store');
    Route::get('/bookings/{booking}', [
        \App\Http\Controllers\Web\BookingController::class,
        'show'
    ])->name('web.bookings.show');
    Route::post('/bookings/{booking}/cancel', [
        \App\Http\Controllers\Web\BookingController::class,
        'cancel'
    ])->name('web.bookings.cancel');
    Route::get('/my-service-requests/{id}', [
        \App\Http\Controllers\Web\BookingController::class,
        'serviceShow'
    ])->name('web.bookings.service_show');
    Route::post('/my-service-requests/{request}/cancel', [
        \App\Http\Controllers\Web\BookingController::class,
        'cancelServiceRequest'
    ])->name('web.bookings.service_cancel');

    Route::get('/my-event-tickets/{id}', [
        \App\Http\Controllers\Web\BookingController::class,
        'eventTicketShow'
    ])->name('web.bookings.event_ticket_show');
    Route::post('/my-event-tickets/{ticket}/cancel', [
        \App\Http\Controllers\Web\BookingController::class,
        'cancelEventTicket'
    ])->name('web.bookings.event_ticket_cancel');

    // Favorites
    Route::get('/favorites', [\App\Http\Controllers\Web\FavoriteController::class, 'index'])->name('web.favorites.index');
    Route::post('/favorites/toggle', [
        \App\Http\Controllers\Web\FavoriteController::class,
        'toggle'
    ])->name('web.favorites.toggle');

    // Conversations
    Route::get('/conversation', [
        \App\Http\Controllers\Web\ConversationController::class,
        'index'
    ])->name('web.conversations.index');
    Route::post('/conversation/message', [
        \App\Http\Controllers\Web\ConversationController::class,
        'sendMessage'
    ])->name('web.conversations.send-message');

    // Events - Purchase Tickets
    Route::post('/events/purchase', [
        \App\Http\Controllers\Web\EventController::class,
        'purchaseTickets'
    ])->name('web.events.purchase');
    Route::get('/my-tickets', [
        \App\Http\Controllers\Web\EventController::class,
        'myTickets'
    ])->name('web.events.my-tickets');

    // Services - Requests
    Route::post('/services/bus-request', [
        \App\Http\Controllers\Web\ServiceController::class,
        'createBusRequest'
    ])->name('web.services.bus-request');
    Route::post('/services/private-car-request', [
        \App\Http\Controllers\Web\ServiceController::class,
        'createPrivateCarRequest'
    ])->name('web.services.private-car-request');
    Route::get('/my-service-requests', [
        \App\Http\Controllers\Web\ServiceController::class,
        'myRequests'
    ])->name('web.services.my-requests');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('web.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('web.profile.update');
});

// Web Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Web\AuthWebController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Web\AuthWebController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Web\AuthWebController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Web\AuthWebController::class, 'register']);
});

Route::post('/logout', [
    \App\Http\Controllers\Web\AuthWebController::class,
    'logout'
])->name('logout')->middleware('auth');

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

        Route::resource('hotels', HotelController::class);

        Route::resource('hotel-rooms', HotelRoomController::class)
            ->except(['show', 'update']);
        Route::post('hotel-rooms/{hotelRoom}/update', [HotelRoomController::class, 'update'])
            ->name('hotel-rooms.update');
        Route::post('hotel-rooms/{hotelRoom}/clone', [HotelRoomController::class, 'clone'])
            ->name('hotel-rooms.clone');

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
        Route::resource('master-services', MasterServiceController::class)->except('show');
        Route::resource('private-cars', \App\Http\Controllers\Admin\PrivateCarController::class);
        Route::resource('service-requests', \App\Http\Controllers\Admin\ServiceRequestController::class)->except([
            'create',
            'store'
        ]);

        // Events routes
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

        // Vouchers routes
        Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);

        // Notifications routes
        Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class)
            ->except(['edit', 'update']);

        // Wallets routes
        Route::get('wallets', [\App\Http\Controllers\Admin\WalletController::class, 'index'])
            ->name('wallets.index');
        Route::get('wallets/{wallet}', [\App\Http\Controllers\Admin\WalletController::class, 'show'])
            ->name('wallets.show');
        Route::get('wallet-transactions', [\App\Http\Controllers\Admin\WalletController::class, 'transactions'])
            ->name('wallets.transactions');
        Route::get('wallets/add-money/form', [\App\Http\Controllers\Admin\WalletController::class, 'addMoney'])
            ->name('wallets.add-money');
        Route::post('wallets/add-money', [\App\Http\Controllers\Admin\WalletController::class, 'storeMoney'])
            ->name('wallets.store-money');

        // Services routes (enhanced)
        Route::get('services/buses', [\App\Http\Controllers\Admin\ServiceController::class, 'buses'])
            ->name('services.buses');
        Route::get('services/buses/create', [\App\Http\Controllers\Admin\ServiceController::class, 'createBus'])
            ->name('services.buses.create');
        Route::post('services/buses', [\App\Http\Controllers\Admin\ServiceController::class, 'storeBus'])
            ->name('services.buses.store');
        Route::get('services/buses/{bus}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'editBus'])
            ->name('services.buses.edit');
        Route::put('services/buses/{bus}', [\App\Http\Controllers\Admin\ServiceController::class, 'updateBus'])
            ->name('services.buses.update');
        Route::delete('services/buses/{bus}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroyBus'])
            ->name('services.buses.destroy');

        Route::get('services/trips', [\App\Http\Controllers\Admin\ServiceController::class, 'trips'])
            ->name('services.trips');
        Route::get('services/trips/create', [\App\Http\Controllers\Admin\ServiceController::class, 'createTrip'])
            ->name('services.trips.create');
        Route::post('services/trips', [\App\Http\Controllers\Admin\ServiceController::class, 'storeTrip'])
            ->name('services.trips.store');
        Route::get('services/trips/{trip}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'editTrip'])
            ->name('services.trips.edit');
        Route::put('services/trips/{trip}', [\App\Http\Controllers\Admin\ServiceController::class, 'updateTrip'])
            ->name('services.trips.update');
        Route::delete('services/trips/{trip}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroyTrip'])
            ->name('services.trips.destroy');

        Route::get('services/private-cars', [\App\Http\Controllers\Admin\ServiceController::class, 'privateCars'])
            ->name('services.private-cars');
        Route::get('services/private-cars/create', [\App\Http\Controllers\Admin\ServiceController::class, 'createPrivateCar'])
            ->name('services.private-cars.create');
        Route::post('services/private-cars', [\App\Http\Controllers\Admin\ServiceController::class, 'storePrivateCar'])
            ->name('services.private-cars.store');
        Route::get('services/private-cars/{privateCar}/edit', [
            \App\Http\Controllers\Admin\ServiceController::class,
            'editPrivateCar'
        ])
            ->name('services.private-cars.edit');
        Route::put('services/private-cars/{privateCar}', [
            \App\Http\Controllers\Admin\ServiceController::class,
            'updatePrivateCar'
        ])
            ->name('services.private-cars.update');
        Route::delete('services/private-cars/{privateCar}', [
            \App\Http\Controllers\Admin\ServiceController::class,
            'destroyPrivateCar'
        ])
            ->name('services.private-cars.destroy');

        Route::get('services/requests', [\App\Http\Controllers\Admin\ServiceController::class, 'requests'])
            ->name('services.requests');
        Route::get('services/requests/{request}', [\App\Http\Controllers\Admin\ServiceController::class, 'showRequest'])
            ->name('services.requests.show');
        Route::patch('services/requests/{request}/status', [
            \App\Http\Controllers\Admin\ServiceController::class,
            'updateRequestStatus'
        ])
            ->name('services.requests.update-status');

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
            ->except(['show', 'update']);
        Route::post('hotel-rooms/{hotelRoom}/update', [\App\Http\Controllers\Hotel\HotelRoomController::class, 'update'])
            ->name('hotel-rooms.update');
        Route::post('hotel-rooms/{hotelRoom}/clone', [\App\Http\Controllers\Hotel\HotelRoomController::class, 'clone'])
            ->name('hotel-rooms.clone');

        Route::get('conversations', [\App\Http\Controllers\Hotel\ConversationController::class, 'index'])
            ->name('conversations.index');
        Route::get('conversations/user/{user}', [\App\Http\Controllers\Hotel\ConversationController::class, 'getOrCreate'])
            ->name('conversations.get-or-create');
        Route::get('conversations/{conversation}', [\App\Http\Controllers\Hotel\ConversationController::class, 'show'])
            ->name('conversations.show');
        Route::post('conversations/{conversation}/message', [
            \App\Http\Controllers\Hotel\ConversationController::class,
            'sendMessage'
        ])
            ->name('conversations.send-message');
        Route::patch('conversations/{conversation}/close', [\App\Http\Controllers\Hotel\ConversationController::class, 'close'])
            ->name('conversations.close');
        Route::patch('conversations/{conversation}/reopen', [
            \App\Http\Controllers\Hotel\ConversationController::class,
            'reopen'
        ])
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