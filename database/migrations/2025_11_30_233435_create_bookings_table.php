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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained('hotel_rooms')->onDelete('set null');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('guests_count')->default(1);
            $table->integer('rooms_count')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->decimal('price_per_night', 10, 2);
            $table->integer('nights_count')->default(1);
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('booking_reference')->unique()->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['hotel_id', 'status']);
            $table->index(['check_in_date', 'check_out_date']);
            $table->index('booking_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
