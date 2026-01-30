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
            $table->string('type')->default('car')->after('id'); // 'car', 'motorcycle'
        });

        Schema::table('vehicle_specs', function (Blueprint $table) {
            $table->string('type')->default('car')->after('id');
            // Change category to string to support more types (Scooter, Sport, etc.)
            // Note: We use string instead of modifying enum because modifying enum is database specific and can be tricky.
            // We'll rely on application level validation.
            $table->string('category', 50)->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('vehicle_specs', function (Blueprint $table) {
            $table->dropColumn('type');
            // Revert category to enum (this might be lossy if we have new categories, but for down migration it's acceptable to reset structure)
            // Ideally we would only revert if we knew the state, but here we just redefine the original enum.
            $table->enum('category', ['MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial'])->change();
        });
    }
};
