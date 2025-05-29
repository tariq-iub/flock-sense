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
        Schema::create('shed_devices', function (Blueprint $table) {
            $table->foreignId('shed_id')->constrained('sheds');
            $table->foreignId('device_id')->constrained('devices');
            $table->timestamp('link_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shed_devices');
    }
};
