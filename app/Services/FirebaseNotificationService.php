<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification as DbNotification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    /**
     * Send notification to a specific user.
     */
    public function sendToUser(User $user, string $title, string $message, string $type, array $data = [])
    {
        // Save to database first
        DbNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);

        if (!$user->fcm_token) {
            Log::info("User ID {$user->id} does not have an FCM token.");
            return false;
        }

        try {
            $messaging = Firebase::messaging();
            $notification = Notification::create($title, $message);
            $cloudMessage = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification($notification)
                ->withData(array_merge($data, ['type' => $type]));

            $messaging->send($cloudMessage);
            return true;
        } catch (\Exception $e) {
            Log::error("Firebase notification failed for User ID {$user->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to all admins.
     */
    public function sendToAdmins(string $title, string $message, string $type, array $data = [])
    {
        $admins = User::where('is_admin', true)->whereNotNull('fcm_token')->get();

        foreach ($admins as $admin) {
            $this->sendToUser($admin, $title, $message, $type, $data);
        }
    }

    /**
     * Standard notification for submission (waiting for approval).
     */
    public function notifySubmission(User $user, string $itemName, string $type)
    {
        $title = "طلب قيد المراجعة";
        $message = "تم استلام طلبك لـ ($itemName)، وهو قيد المراجعة حالياً من قبل الإدارة.";
        
        // Notify User
        $this->sendToUser($user, $title, $message, "{$type}_submission_pending", ['item_name' => $itemName]);

        // Notify Admins
        $adminTitle = "طلب جديد";
        $adminMessage = "قام المستخدم ({$user->name}) بإضافة ($itemName) جديد. يرجى المراجعة.";
        $this->sendToAdmins($adminTitle, $adminMessage, "new_{$type}_submission", [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'item_name' => $itemName
        ]);
    }

    /**
     * Standard notification for acceptance.
     */
    public function notifyAcceptance(User $user, string $itemName, string $type)
    {
        $title = "تم قبول طلبك";
        $message = "تهانينا! تم قبول طلبك لـ ($itemName) وهو متاح الآن على المنصة.";
        
        $this->sendToUser($user, $title, $message, "{$type}_accepted", ['item_name' => $itemName]);
    }

    /**
     * Standard notification for booking approval.
     */
    public function notifyBookingApproval(User $user, string $bookingDetails, string $type)
    {
        $title = "تأكيد الحجز";
        $message = "تم قبول حجزك بنجاح ($bookingDetails).";
        
        $this->sendToUser($user, $title, $message, "booking_approved", ['details' => $bookingDetails, 'type' => $type]);
    }
}
