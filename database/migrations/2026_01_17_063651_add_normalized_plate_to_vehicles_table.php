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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('normalized_plate_number')->after('plate_number')->nullable()->index();
        });

        // Populate the new column for existing records
        \Illuminate\Support\Facades\DB::table('vehicles')->orderBy('id')->chunk(100, function ($vehicles) {
            foreach ($vehicles as $vehicle) {
                $normalized = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $vehicle->plate_number));
                \Illuminate\Support\Facades\DB::table('vehicles')
                    ->where('id', $vehicle->id)
                    ->update(['normalized_plate_number' => $normalized]);
            }
        });
        
        // Make it not nullable after population if needed, but keeping nullable for safety first is okay.
        // However, better to enforce it if we rely on it.
        Schema::table('vehicles', function (Blueprint $table) {
             $table->string('normalized_plate_number')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('normalized_plate_number');
        });
    }
};
