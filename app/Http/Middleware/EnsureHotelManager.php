<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHotelManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('hotel.login');
        }

        $user = auth()->user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
