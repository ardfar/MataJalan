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
            $table->string('make')->nullable();
            $table->integer('year')->nullable();
            $table->string('color')->nullable();
            $table->string('vin')->nullable()->unique();
        });

        Schema::create('registration_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_feedbacks');

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['make', 'year', 'color', 'vin']);
        });
    }
};
