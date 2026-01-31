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
        Schema::table('vehicle_specs', function (Blueprint $table) {
            $table->integer('cargo_capacity_kg')->nullable()->after('seat_capacity');
            $table->integer('gvwr_kg')->nullable()->after('cargo_capacity_kg'); // Gross Vehicle Weight Rating
            $table->integer('axle_count')->nullable()->after('gvwr_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_specs', function (Blueprint $table) {
            $table->dropColumn(['cargo_capacity_kg', 'gvwr_kg', 'axle_count']);
        });
    }
};
