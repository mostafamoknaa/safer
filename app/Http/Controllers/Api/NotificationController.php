<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications.
     */
    public function getNotifications(Request $request): JsonResponse
    {
        $query = Notification::where('user_id', Auth::id());

        // Filter by read status
        if ($request->filled('read')) {
            if ($request->boolean('read')) {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }

        $notifications = $query->orderByDesc('created_at')
            ->paginate(20)
            ->through(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'is_read' => $notification->isRead(),
                    'created_at' => $notification->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count,
            ],
        ]);
    }

    /**
     * Insert sample notifications for testing.
     */
    public function insertSampleNotifications(): JsonResponse
    {
        $userId = Auth::id();
        
        $notifications = [
            [
                'user_id' => $userId,
                'title' => 'تم تأكيد حجزك',
                'message' => 'تم تأكيد حجزك في فندق الريتز كارلتون. رقم الحجز: BK001',
                'type' => 'booking_confirmed',
                'data' => ['booking_id' => 1, 'hotel_name' => 'فندق الريتز كارلتون'],
            ],
            [
                'user_id' => $userId,
                'title' => 'عرض خاص',
                'message' => 'احصل على خصم 20% على حجزك القادم في فنادق الرياض',
                'type' => 'promotion',
                'data' => ['discount' => 20, 'city' => 'الرياض'],
            ],
            [
                'user_id' => $userId,
                'title' => 'تذكير بالوصول',
                'message' => 'تذكير: موعد وصولك غداً في فندق هيلتون. تأكد من إحضار هويتك',
                'type' => 'check_in_reminder',
                'data' => ['booking_id' => 2, 'check_in_date' => '2024-01-15'],
            ],
            [
                'user_id' => $userId,
                'title' => 'تقييم الإقامة',
                'message' => 'كيف كانت إقامتك في فندق شيراتون؟ شاركنا تقييمك',
                'type' => 'review_request',
                'data' => ['booking_id' => 3, 'hotel_name' => 'فندق شيراتون'],
                'read_at' => now(),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sample notifications inserted successfully',
        ]);
    }

}