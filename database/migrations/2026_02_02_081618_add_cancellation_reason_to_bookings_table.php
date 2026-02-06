<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancelled_at']);
        });

        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancelled_at']);
        });

        Schema::table('event_tickets', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancelled_at']);
        });
    }
};