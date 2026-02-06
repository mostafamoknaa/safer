<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /**
     * Display a listing of conversations.
     */
    public function index(Request $request): View
    {
        $query = Conversation::with(['user', 'lastMessage', 'admin']);

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فلترة المحادثات المفتوحة فقط
        if (!$request->has('status')) {
            $query->where('status', 'open');
        }

        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $conversations = $query->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate(15);

        // عدد الرسائل غير المقروءة
        $unreadCount = Message::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();

        return view('admin.conversations.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Get or create conversation with a user.
     */
    public function getOrCreate(User $user): RedirectResponse
    {
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $user->id],
            [
                'admin_id' => auth()->id(),
                'status' => 'open',
            ]
        );

        // تحديد المحادثة للمسؤول الحالي إذا لم تكن محددة
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => auth()->id()]);
        }

        // فتح المحادثة إذا كانت مغلقة
        if ($conversation->status === 'closed') {
            $conversation->update(['status' => 'open']);
        }

        return redirect()
            ->route('admin.conversations.show', $conversation);
    }

    /**
     * Show a specific conversation.
     */
    public function show(Conversation $conversation): View
    {
        $conversation->load(['user', 'messages.sender', 'admin']);

        // تحديد المحادثة للمسؤول الحالي
        if (!$conversation->admin_id) {
            $conversation->update(['admin_id' => auth()->id()]);
        }

        // تحديث حالة الرسائل غير المقروءة للمستخدم
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.conversations.show', compact('conversation'));
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'required_without:file'],
            'file' => ['nullable', 'file', 'max:10240'],
        ], [
            'message.required_without' => 'يجب إدخال رسالة أو رفع ملف.',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'admin',
            'type' => 'text',
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('conversations/' . $conversation->id . '/files', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['type'] = 'file';
            $data['message'] = $validated['message'] ?? $file->getClientOriginalName();
        } else {
            $data['message'] = $validated['message'];
        }

        Message::create($data);

        // تحديث وقت آخر رسالة
        $conversation->update(['last_message_at' => now()]);

        return redirect()
            ->route('admin.conversations.show', $conversation)
            ->with('success', trans('admin.conversations.messages.sent'));
    }

    /**
     * Close a conversation.
     */
    public function close(Conversation $conversation): RedirectResponse
    {
        $conversation->update(['status' => 'closed']);

        return redirect()
            ->route('admin.conversations.index')
            ->with('success', trans('admin.conversations.messages.closed'));
    }

    /**
     * Reopen a conversation.
     */
    public function reopen(Conversation $conversation): RedirectResponse
    {
        $conversation->update(['status' => 'open']);

        return redirect()
            ->route('admin.conversations.show', $conversation)
            ->with('success', trans('admin.conversations.messages.reopened'));
    }
}
