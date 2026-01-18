<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // Generate UUIDs for existing records
        DB::table('vehicles')->orderBy('id')->chunk(100, function ($vehicles) {
            foreach ($vehicles as $vehicle) {
                if (empty($vehicle->uuid)) {
                    DB::table('vehicles')
                        ->where('id', $vehicle->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
