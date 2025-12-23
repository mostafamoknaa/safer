<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /**
     * Display a listing of conversations for managed hotels.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $managedHotelIds = $user->managedHotels()->pluck('hotels.id');

        $query = Conversation::with(['user', 'lastMessage', 'hotel'])
            ->whereIn('hotel_id', $managedHotelIds);

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
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
        $unreadCount = Message::whereHas('conversation', function ($q) use ($managedHotelIds) {
            $q->whereIn('hotel_id', $managedHotelIds);
        })
        ->where('sender_type', 'user')
        ->where('is_read', false)
        ->count();

        return view('hotel.conversations.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Show a specific conversation.
     */
    public function show(Conversation $conversation): View
    {
        $user = auth()->user();

        // التحقق من أن المحادثة تخص فندق مسئول عنه
        if (!$conversation->hotel_id || !$user->managesHotel($conversation->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لعرض هذه المحادثة.');
        }

        $conversation->load(['user', 'messages.sender', 'hotel']);

        // تحديد المحادثة للمسئول الحالي
        if (!$conversation->hotel_manager_id) {
            $conversation->update(['hotel_manager_id' => $user->id]);
        }

        // تحديث حالة الرسائل غير المقروءة للمستخدم
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('hotel.conversations.show', compact('conversation'));
    }

    /**
     * Get or create conversation with a user.
     */
    public function getOrCreate(\App\Models\User $user): RedirectResponse
    {
        $manager = auth()->user();
        $hotels = $manager->managedHotels()->get();

        // إذا كان هناك فندق واحد فقط، استخدمه
        // وإلا سيحتاج المستخدم لاختيار الفندق
        if ($hotels->count() === 1) {
            $hotel = $hotels->first();
        } else {
            // TODO: يمكن إضافة صفحة لاختيار الفندق
            $hotel = $hotels->first();
        }

        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => $user->id,
                'hotel_id' => $hotel->id,
            ],
            [
                'hotel_manager_id' => $manager->id,
                'status' => 'open',
            ]
        );

        // فتح المحادثة إذا كانت مغلقة
        if ($conversation->status === 'closed') {
            $conversation->update(['status' => 'open']);
        }

        return redirect()
            ->route('hotel.conversations.show', $conversation);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();

        // التحقق من أن المحادثة تخص فندق مسئول عنه
        if (!$conversation->hotel_id || !$user->managesHotel($conversation->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لإرسال رسالة في هذه المحادثة.');
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'required_without:file'],
            'file' => ['nullable', 'file', 'max:10240'],
        ], [
            'message.required_without' => 'يجب إدخال رسالة أو رفع ملف.',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
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
            ->route('hotel.conversations.show', $conversation)
            ->with('success', trans('hotel.conversations.messages.sent'));
    }

    /**
     * Close a conversation.
     */
    public function close(Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();

        // التحقق من أن المحادثة تخص فندق مسئول عنه
        if (!$conversation->hotel_id || !$user->managesHotel($conversation->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لإغلاق هذه المحادثة.');
        }

        $conversation->update(['status' => 'closed']);

        return redirect()
            ->route('hotel.conversations.index')
            ->with('success', trans('hotel.conversations.messages.closed'));
    }

    /**
     * Reopen a conversation.
     */
    public function reopen(Conversation $conversation): RedirectResponse
    {
        $user = auth()->user();

        // التحقق من أن المحادثة تخص فندق مسئول عنه
        if (!$conversation->hotel_id || !$user->managesHotel($conversation->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لإعادة فتح هذه المحادثة.');
        }

        $conversation->update(['status' => 'open']);

        return redirect()
            ->route('hotel.conversations.show', $conversation)
            ->with('success', trans('hotel.conversations.messages.reopened'));
    }
}
