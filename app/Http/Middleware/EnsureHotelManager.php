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

        // التحقق من أن المستخدم ليس مسؤول عام
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        // التحقق من أن المستخدم لديه فنادق مسئول عنها
        if ($user->managedHotels()->count() === 0) {
            auth()->logout();
            return redirect()->route('hotel.login')
                ->with('error', 'ليس لديك صلاحية للوصول إلى لوحة تحكم الفنادق.');
        }

        return $next($request);
    }
}
