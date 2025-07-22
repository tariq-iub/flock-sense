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
        Schema::create('device_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->string('event_type'); // e.g., "low_battery", "offline", "alert", "threshold_breach"
            $table->string('severity')->nullable(); // e.g., "info", "warning", "critical"
            $table->json('details')->nullable(); // { "battery_level": 5, "threshold": 10 }
            $table->dateTime('occurred_at')->index(); // When the event occurred
            $table->timestamps();

            $table->index(['device_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_events');
    }
};
