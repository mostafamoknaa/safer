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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('departure_location_ar');
            $table->string('departure_location_en');
            $table->string('arrival_location_ar');
            $table->string('arrival_location_en');
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->date('trip_date');
            $table->time('trip_time');
            $table->integer('duration_minutes'); // مدة الرحلة بالدقائق
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['trip_date', 'is_active']);
            $table->index('bus_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
