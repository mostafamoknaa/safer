<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'hotel.manager' => \App\Http\Middleware\EnsureHotelManager::class,
        ]);

        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'hotel/login',
            'hotel/logout',
            'logout',
            'bookings',
            'bookings/*',
            'favorites/toggle',
            'events/purchase',
          	'services/*',
            'contact',
          	'my-service-requests/*',
          	'my-event-tickets/*',
          	'buses/*',
            'profile',
        ]);

        // تعطيل redirect الافتراضي للمستخدمين غير المسجلين
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('hotel/*')) {
                return route('hotel.login');
            }

            // افتراضي: redirect إلى admin login
            return route('login');
        });
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // تخصيص redirect للمستخدمين غير المسجلين حسب المسار
        $exceptions->respond(function ($response, \Throwable $exception, \Illuminate\Http\Request $request) {
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                if ($request->is('admin/*')) {
                    return redirect()->route('admin.login')
                        ->with('error', __('admin.auth.login_required'));
                }

                if ($request->is('hotel/*')) {
                    return redirect()->route('hotel.login')
                        ->with('error', __('hotel.auth.login_required'));
                }
                return redirect()->route('login');
            }

            return $response;
        });
     })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('ical:sync')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();
    })->create();
