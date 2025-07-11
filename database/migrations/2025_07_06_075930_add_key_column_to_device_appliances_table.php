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
        Schema::table('device_appliances', function (Blueprint $table) {
            $table->string('key')->nullable()->after('device_id'); // Appliance key: f1, f2, b1, b2, c1, etc.
            $table->unique(['device_id', 'key']); // Ensure key is unique per device
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_appliances', function (Blueprint $table) {
            $table->dropUnique(['device_id', 'key']);
            $table->dropColumn('key');
        });
    }
};
