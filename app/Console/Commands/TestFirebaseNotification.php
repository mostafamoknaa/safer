<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\FirebaseNotificationService;

class TestFirebaseNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:firebase {user_id} {--token= : Override FCM token for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test Firebase notification to a specific user';

    /**
     * Execute the console command.
     */
    public function handle(FirebaseNotificationService $service)
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("User not found!");
            return 1;
        }

        $token = $this->option('token');
        if ($token) {
            $user->fcm_token = $token;
            $user->save();
            $this->info("Updated user FCM token to: $token");
        }

        if (!$user->fcm_token) {
            $this->error("User has no FCM token. Use --token=YOUR_TOKEN to set one.");
            return 1;
        }

        $this->info("Sending test notification to User: {$user->name} (ID: {$user->id})");

        $success = $service->sendToUser(
            $user,
            "safer+ Test Notification",
            "This is a test notification from the safer+ platform!",
            "test_notification",
            ['test_data' => 'hello world']
        );

        if ($success) {
            $this->info("Success! Check your device or Firebase logs.");
        } else {
            $this->error("Failed to send notification. Check storage/logs/laravel.log");
        }

        return 0;
    }
}
