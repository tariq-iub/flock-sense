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
        Schema::create('device_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->json('data'); // { "temperature": 22.1, "humidity": 58, ... }
            $table->dateTime('recorded_at')->index(); // When the reading was captured
            $table->string('unit')->nullable(); // e.g., °C, %, ppm (optional: for a single-sensor device)
            $table->json('quality')->nullable(); // Optional: { "confidence": 0.98, "error": "none" }
            $table->timestamps();

            $table->index(['device_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_readings');
    }
};
