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
        Schema::table('iot_data_logs', function (Blueprint $table) {
            $table->enum('time_window', ['hourly', '6h', '12h', 'daily'])->after('record_time');
            $table->index(['device_id', 'parameter', 'record_time', 'time_window'], 'idx_device_param_window');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iot_data_logs', function (Blueprint $table) {
            //
        });
    }
};
