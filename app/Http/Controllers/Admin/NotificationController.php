<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|max:50',
            'send_to_all' => 'boolean',
        ]);

        if ($request->send_to_all) {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => $request->type,
                ]);
            }
        } else {
            Notification::create($request->only(['user_id', 'title', 'message', 'type']));
        }

        return redirect()->route('admin.notifications.index')->with('success', 'تم إرسال الإشعار بنجاح');
    }

    public function show(Notification $notification)
    {
        return view('admin.notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'تم حذف الإشعار بنجاح');
    }
}