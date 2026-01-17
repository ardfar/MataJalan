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
        Schema::create('rating_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_type'); // image, video
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_honest')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_media');

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'address', 'is_honest']);
        });
    }
};
