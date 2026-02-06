<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    /**
     * Display user's conversation.
     */
    public function index()
    {
        $user = auth()->user();

        // Get or create conversation
        $conversation = Conversation::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('admin_id')
                    ->orWhereNotNull('hotel_manager_id');
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'status' => 'open',
            ]);
        }

        $conversation->load(['messages.sender', 'admin', 'hotelManager', 'hotel']);

        // Mark admin messages as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('web.conversations.index', compact('conversation'));
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'required_without:file'],
            'file' => ['nullable', 'file', 'max:10240'],
        ], [
            'message.required_without' => 'يجب إدخال رسالة أو رفع ملف.',
        ]);

        $user = auth()->user();

        // Get or create conversation
        $conversation = Conversation::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('admin_id')
                    ->orWhereNotNull('hotel_manager_id');
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'status' => 'open',
            ]);
        }

        $data = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_type' => 'user',
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

        // Update last message time
        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('web.conversations.index')
            ->with('success', 'تم إرسال الرسالة بنجاح');
    }
}
