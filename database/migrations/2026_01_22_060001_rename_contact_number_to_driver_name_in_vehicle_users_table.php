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
        Schema::table('vehicle_users', function (Blueprint $table) {
            $table->renameColumn('contact_number', 'driver_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_users', function (Blueprint $table) {
            $table->renameColumn('driver_name', 'contact_number');
        });
    }
};
