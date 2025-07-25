<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('device_appliances', function (Blueprint $table) {
            // Drop the foreign key on device_id (we'll re-add it later)
            $table->dropForeign(['device_id']);
        });

        // Drop the unique index (now it will work)
        DB::statement('ALTER TABLE device_appliances DROP INDEX device_appliances_unique_device_type');

        // Add the new unique constraint on (device_id, key)
        Schema::table('device_appliances', function (Blueprint $table) {
            $table->unique(['device_id', 'key'], 'device_appliances_unique_device_key');

            // Re-add the foreign key on device_id
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('device_appliances', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
            $table->dropUnique('device_appliances_unique_device_key');
        });

        // Recreate the old unique index
        DB::statement('ALTER TABLE device_appliances ADD UNIQUE INDEX device_appliances_unique_device_type (device_id, type)');

        // Re-add original foreign key
        Schema::table('device_appliances', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }
};
