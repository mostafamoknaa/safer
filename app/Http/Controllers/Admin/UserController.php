<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // فلترة حسب الحالة (نشط/متوقف)
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // فلترة غير المدراء
        $query->where('is_admin', false);

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->with('managedHotels')->orderByDesc('created_at')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for managing user's hotels.
     */
    public function manageHotels(User $user): View
    {
        // منع إدارة فنادق المدراء
        if ($user->is_admin) {
            abort(403, 'لا يمكن إدارة فنادق المسؤول العام.');
        }

        $allHotels = Hotel::where('is_active', true)->orderBy('name_ar')->get();
        $userHotels = $user->managedHotels()->pluck('hotels.id')->toArray();

        return view('admin.users.manage-hotels', compact('user', 'allHotels', 'userHotels'));
    }

    /**
     * Update user's hotels.
     */
    public function updateHotels(Request $request, User $user): RedirectResponse
    {
        // منع إدارة فنادق المدراء
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'لا يمكن إدارة فنادق المسؤول العام.');
        }

        $validated = $request->validate([
            'hotels' => ['nullable', 'array'],
            'hotels.*' => ['exists:hotels,id'],
        ]);

        $hotelIds = $validated['hotels'] ?? [];

        // Sync hotels
        $user->managedHotels()->sync($hotelIds);

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('admin.users.messages.hotels_updated'));
    }

    /**
     * Toggle user active status.
     */
    public function toggle(User $user): RedirectResponse
    {
        // منع تعديل حالة المدراء
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', trans('admin.users.messages.cannot_modify_admin'));
        }

        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active
            ? trans('admin.users.messages.activated')
            : trans('admin.users.messages.deactivated');

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        // منع حذف المدراء
        if ($user->is_admin) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', trans('admin.users.messages.cannot_delete_admin'));
        }

        // حذف جميع tokens المستخدم
        $user->tokens()->delete();

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('admin.users.messages.deleted'));
    }
}
