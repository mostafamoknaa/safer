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
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('service_type', ['bus', 'private_car']);
            
            // For bus service
            $table->foreignId('trip_id')->nullable()->constrained('trips')->onDelete('set null');
            $table->foreignId('bus_id')->nullable()->constrained('buses')->onDelete('set null');
            $table->string('departure_location_ar')->nullable();
            $table->string('departure_location_en')->nullable();
            $table->string('arrival_location_ar')->nullable();
            $table->string('arrival_location_en')->nullable();
            $table->integer('passengers_count')->nullable();
            $table->date('trip_date')->nullable();
            
            // For private car service
            $table->foreignId('private_car_id')->nullable()->constrained('private_cars')->onDelete('set null');
            $table->integer('duration_hours')->nullable(); // مدة التأجير بالساعات
            $table->date('start_date')->nullable();
            
            // Common fields
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('request_reference')->unique()->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['service_type', 'status']);
            $table->index('request_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
