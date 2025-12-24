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

Route::get('/', function () {
    return view('welcome');
});

// Default login route (redirects to admin login)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

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
        Route::get('services/private-cars/{privateCar}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'editPrivateCar'])
            ->name('services.private-cars.edit');
        Route::put('services/private-cars/{privateCar}', [\App\Http\Controllers\Admin\ServiceController::class, 'updatePrivateCar'])
            ->name('services.private-cars.update');
        Route::delete('services/private-cars/{privateCar}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroyPrivateCar'])
            ->name('services.private-cars.destroy');

        Route::get('services/requests', [\App\Http\Controllers\Admin\ServiceController::class, 'requests'])
            ->name('services.requests');
        Route::get('services/requests/{request}', [\App\Http\Controllers\Admin\ServiceController::class, 'showRequest'])
            ->name('services.requests.show');
        Route::patch('services/requests/{request}/status', [\App\Http\Controllers\Admin\ServiceController::class, 'updateRequestStatus'])
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
