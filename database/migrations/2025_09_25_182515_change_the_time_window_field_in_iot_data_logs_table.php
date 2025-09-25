<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum values to include '3h' and 'latest'
        DB::statement("
            ALTER TABLE iot_data_logs
            MODIFY COLUMN time_window ENUM('hourly', '3h', '6h', '12h', 'daily', 'latest')
            NOT NULL AFTER record_time
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old enum values
    }
};
