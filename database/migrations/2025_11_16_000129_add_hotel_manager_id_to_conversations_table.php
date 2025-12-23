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
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('hotel_manager_id')->nullable()->after('admin_id')->constrained('users')->onDelete('set null');
            $table->foreignId('hotel_id')->nullable()->after('hotel_manager_id')->constrained('hotels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['hotel_manager_id']);
            $table->dropForeign(['hotel_id']);
            $table->dropColumn(['hotel_manager_id', 'hotel_id']);
        });
    }
};
