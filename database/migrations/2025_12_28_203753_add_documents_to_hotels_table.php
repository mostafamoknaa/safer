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
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_2')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'user_id',
                'price',
                'phone',
                'phone_2',
                'description_ar',
                'description_en'
            ]);
        });
    }
};
