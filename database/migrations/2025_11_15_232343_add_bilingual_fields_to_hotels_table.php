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
        // Add new bilingual columns
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('address_en')->nullable()->after('address');
            $table->text('about_info_en')->nullable()->after('about_info');
        });
        
        // Copy existing data from name to name_ar (will rename later)
        DB::statement('UPDATE hotels SET name = name WHERE name IS NOT NULL');
        
        // Rename columns - use raw SQL for MySQL compatibility
        DB::statement('ALTER TABLE hotels CHANGE COLUMN name name_ar VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE hotels CHANGE COLUMN address address_ar TEXT NOT NULL');
        DB::statement('ALTER TABLE hotels CHANGE COLUMN about_info about_info_ar TEXT NULL');
        
        // Make name_en and address_en required
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name_en')->nullable(false)->change();
            $table->text('address_en')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename back
        DB::statement('ALTER TABLE hotels CHANGE COLUMN name_ar name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE hotels CHANGE COLUMN address_ar address TEXT NOT NULL');
        DB::statement('ALTER TABLE hotels CHANGE COLUMN about_info_ar about_info TEXT NULL');
        
        // Drop new columns
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'address_en', 'about_info_en']);
        });
    }
};
