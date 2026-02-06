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
        Schema::create('private_car_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('private_car_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->integer('order_column')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_car_media');
    }
};
