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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->string('kyc_status')->default('none')->after('is_admin'); // none, pending, approved, rejected
            $table->text('kyc_data')->nullable()->after('kyc_status'); // JSON data
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_data');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin',
                'kyc_status',
                'kyc_data',
                'kyc_submitted_at',
                'kyc_verified_at',
            ]);
        });
    }
};
