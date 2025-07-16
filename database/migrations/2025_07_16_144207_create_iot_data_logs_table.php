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
        Schema::create('iot_data_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained()->onDelete('restrict');
            $table->foreignId('device_id')->constrained()->onDelete('restrict');
            $table->string('parameter');
            $table->double('min_value');
            $table->double('max_value');
            $table->double('avg_value');
            $table->dateTime('record_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iot_data_logs');
    }
};
