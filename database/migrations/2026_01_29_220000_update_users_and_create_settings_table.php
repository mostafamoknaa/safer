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
        // Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['customer', 'provider'])->default('customer')->after('email');
            $table->string('card_number')->nullable()->after('type');
        });

        // Create global_settings table
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('hotel_commission', 5, 2)->default(0);
            $table->decimal('apartment_commission', 5, 2)->default(0);
            $table->decimal('car_hour_commission', 5, 2)->default(0);
            $table->decimal('car_day_commission', 5, 2)->default(0);
            $table->decimal('bus_commission', 5, 2)->default(0);
            $table->decimal('activity_commission', 5, 2)->default(0);
            $table->timestamps();
        });

        // Seed default settings
        \DB::table('global_settings')->insert([
            'hotel_commission' => 0,
            'apartment_commission' => 0,
            'car_hour_commission' => 0,
            'car_day_commission' => 0,
            'bus_commission' => 0,
            'activity_commission' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type', 'card_number']);
        });
    }
};
