<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            $table->time('checkin_time')->default('14:00')->after('is_active');
            $table->time('checkout_time')->default('12:00')->after('checkin_time');
            $table->json('blocked_slots')->nullable()->after('checkout_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            $table->dropColumn(['checkin_time', 'checkout_time', 'blocked_slots']);
        });
    }
};
