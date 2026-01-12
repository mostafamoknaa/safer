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
        Schema::table('buses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('private_cars', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('private_cars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
