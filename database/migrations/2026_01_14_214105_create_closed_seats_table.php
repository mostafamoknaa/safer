<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closed_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->integer('seat_number');
            $table->timestamps();

            $table->unique(['trip_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closed_seats');
    }
};
