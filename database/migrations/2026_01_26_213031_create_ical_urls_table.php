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
        Schema::create('ical_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->cascadeOnDelete();
            $table->string('url');
            $table->string('name')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->enum('sync_status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('sync_message')->nullable(); // For error messages
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ical_urls');
    }
};
