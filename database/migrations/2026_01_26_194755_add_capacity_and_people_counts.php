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
            $table->integer('max_people')->default(0)->after('rooms_count');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('adults_count')->default(0)->after('guests_count');
            $table->integer('young_count')->default(0)->after('adults_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_rooms', function (Blueprint $table) {
            $table->dropColumn('max_people');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['adults_count', 'young_count']);
        });
    }
};
