<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول أولاً
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                ->with('error', __('admin.auth.login_required'));
        }

        $user = auth()->user();

        // التحقق من أن المستخدم هو مسؤول عام
        if (! $user->is_admin) {
            auth()->logout();
            return redirect()->route('admin.login')
                ->with('error', __('admin.auth.login_required'));
        }

        return $next($request);
    }
}

