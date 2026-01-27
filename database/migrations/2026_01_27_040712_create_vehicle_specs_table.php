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
        Schema::create('vehicle_specs', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 50);
            $table->string('model', 100);
            $table->string('variant', 100);
            $table->enum('category', ['MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial']);
            $table->integer('engine_cc')->nullable();
            $table->decimal('battery_kwh', 5, 1)->nullable();
            $table->integer('horsepower');
            $table->integer('torque');
            $table->string('transmission', 50);
            $table->string('fuel_type', 50);
            $table->integer('seat_capacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_specs');
    }
};
