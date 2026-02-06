<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->change();
            $table->nullableMorphs('payable');
            $table->index(['payable_id', 'payable_type']);
        });

        // Migrate existing booking_id data to polymorphic columns
        DB::table('payments')->whereNotNull('booking_id')->update([
            'payable_id' => DB::raw('booking_id'),
            'payable_type' => 'booking' 
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropMorphs('payable');
            $table->foreignId('booking_id')->nullable(false)->change();
        });
    }
};
