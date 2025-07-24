<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->string('type'); // fan, light, exhaust
            $table->string('key')->nullable();
            $table->string('name')->nullable();
            $table->unsignedTinyInteger('channel')->nullable();
            $table->json('config')->nullable();

            // Status and metrics
            $table->boolean('status')->default(false);
            $table->dateTime('status_updated_at')->nullable();
            $table->string('last_command_source')->nullable(); // e.g., auto, manual, api
            $table->json('metrics')->nullable();

            $table->timestamps();

            $table->unique(['device_id', 'type'], 'device_appliances_unique_device_type'); // Ensure key is unique per device
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_appliances');
    }
};
