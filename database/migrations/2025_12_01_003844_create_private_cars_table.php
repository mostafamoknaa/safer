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
        Schema::create('private_cars', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('price', 10, 2); // السعر لكل مدة (ساعة أو يوم)
            $table->integer('seats_count');
            $table->string('image')->nullable();
            $table->integer('max_speed')->nullable(); // السرعة القصوى بالكيلومتر
            $table->decimal('acceleration', 5, 2)->nullable(); // التسارع
            $table->integer('power')->nullable(); // القوة بالحصان
            $table->text('notes_ar')->nullable();
            $table->text('notes_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_cars');
    }
};
