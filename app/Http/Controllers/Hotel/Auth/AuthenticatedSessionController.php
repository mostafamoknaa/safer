<?php

namespace App\Http\Controllers\Hotel\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the hotel manager login form.
     */
    public function create(): View
    {
        return view('hotel.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt(
            [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                'is_active' => true,
            ],
            $remember
        )) {
            throw ValidationException::withMessages([
                'email' => trans('hotel.auth.failed'),
            ]);
        }

        $user = Auth::user();

        // التحقق من أن المستخدم ليس مسؤول عام
        if ($user->is_admin) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => trans('hotel.auth.admin_not_allowed'),
            ]);
        }

        // التحقق من أن المستخدم لديه فنادق مسئول عنها
        if (!$user->isHotelManager()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => trans('hotel.auth.no_hotels'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('hotel.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hotel.login');
    }
}
