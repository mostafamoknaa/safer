<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ConversationController extends Controller
{
    /**
     * Get or create user conversation.
     */
    public function getConversation(Request $request): JsonResponse
    {
        $user = $request->user();

        // البحث عن محادثة مع المسؤول العام أو مع مسئول فندق
        $conversation = Conversation::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('admin_id')
                      ->orWhereNotNull('hotel_manager_id');
            })
            ->first();

        // إذا لم توجد محادثة، أنشئ واحدة جديدة مع المسؤول العام
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'status' => 'open',
            ]);
        }

        $conversation->load(['messages.sender', 'admin', 'hotelManager', 'hotel']);

        // تحديث حالة الرسائل غير المقروءة للمسؤول أو مسئول الفندق
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => [
                'conversation' => [
                    'id' => $conversation->id,
                    'status' => $conversation->status,
                    'hotel_id' => $conversation->hotel_id,
                    'hotel_name' => $conversation->hotel ? (app()->getLocale() === 'ar' ? $conversation->hotel->name_ar : $conversation->hotel->name_en) : null,
                    'manager_name' => $conversation->hotelManager ? $conversation->hotelManager->name : ($conversation->admin ? $conversation->admin->name : null),
                    'messages' => $conversation->messages->map(function ($message) {
                        return [
                            'id' => $message->id,
                            'sender_type' => $message->sender_type,
                            'sender_name' => $message->sender->name,
                            'message' => $message->message,
                            'type' => $message->type,
                            'file_path' => $message->file_path ? Storage::url($message->file_path) : null,
                            'file_name' => $message->file_name,
                            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                        ];
                    }),
                ],
            ],
        ]);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'message' => ['nullable', 'string', 'required_without:file'],
                'file' => ['nullable', 'file', 'max:10240'],
            ], [
                'message.required_without' => 'يجب إدخال رسالة أو رفع ملف.',
            ]);

            // البحث عن محادثة موجودة أو إنشاء واحدة جديدة
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

            $message = Message::create($data);
            $message->load('sender');

            // تحديث وقت آخر رسالة
            $conversation->update(['last_message_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الرسالة بنجاح',
                'data' => [
                    'message' => [
                        'id' => $message->id,
                        'sender_type' => $message->sender_type,
                        'sender_name' => $message->sender->name,
                        'message' => $message->message,
                        'type' => $message->type,
                        'file_path' => $message->file_path ? Storage::url($message->file_path) : null,
                        'file_name' => $message->file_name,
                        'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    ],
                ],
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الرسالة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get unread messages count.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();

        $conversation = Conversation::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('admin_id')
                      ->orWhereNotNull('hotel_manager_id');
            })
            ->first();

        if (!$conversation) {
            return response()->json([
                'success' => true,
                'data' => ['unread_count' => 0],
            ]);
        }

        $unreadCount = Message::where('conversation_id', $conversation->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['unread_count' => $unreadCount],
        ]);
    }
}
