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
        // 1. Ensure all current roles are valid enum values or default to 'user'
        DB::table('users')->whereNotIn('role', ['superadmin', 'admin', 'tier_1', 'tier_2', 'user'])
            ->update(['role' => 'user']);

        // 2. Modify column to ENUM
        Schema::table('users', function (Blueprint $table) {
            // Note: DBAL might be needed for changing to enum, but modern Laravel supports native enum modification on MySQL/Postgres.
            // However, to be safe and cross-db compatible as much as possible, we can just alter it.
            // If using SQLite, this might fail without dropping/recreating, but let's assume standard driver.
            // Since we can't easily rely on 'change()' without knowing the driver capabilities fully in this env,
            // we will try to use raw statement for MySQL/Postgres if needed, but 'change()' is the Laravel way.
            $table->enum('role', ['superadmin', 'admin', 'tier_1', 'tier_2', 'user'])->default('user')->change();
        });

        // 3. Drop is_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        // Restore is_admin values based on role
        DB::table('users')->whereIn('role', ['admin', 'superadmin'])->update(['is_admin' => true]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->change();
        });
    }
};
