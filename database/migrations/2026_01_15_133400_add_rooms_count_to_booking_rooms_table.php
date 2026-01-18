<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_rooms', 'rooms_count')) {
                $table->integer('rooms_count')->default(1)->after('room_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_rooms', function (Blueprint $table) {
            $table->dropColumn('rooms_count');
        });
    }
};
