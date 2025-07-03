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
            $table->foreignId('device_id')->constrained(); // Device that owns this appliance
            $table->string('type'); // e.g., fan, light, exhaust
            $table->string('name')->nullable(); // Optional: "Fan A", "Light 1"
            $table->unsignedTinyInteger('channel')->nullable(); // for I/O channel mapping if needed
            $table->json('config')->nullable(); // optional e.g. {"intensity": "range"}

            // Status fields (previously in separate table)
            $table->boolean('status')->default(false); // basic ON/OFF
            $table->json('metrics')->nullable(); // optional, e.g., {"speed": 3, "intensity": 50}
            $table->datetime('status_updated_at')->nullable(); // track last status update

            $table->timestamps();
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
