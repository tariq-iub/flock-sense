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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no')->unique();
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('firmware_version')->nullable();
            $table->string('connectivity_type')->default('WiFi');
            $table->boolean('is_online')->default(false);
            $table->datetime('last_heartbeat')->nullable();
            $table->boolean('battery_operated')->default(false);
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->integer('signal_strength')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
