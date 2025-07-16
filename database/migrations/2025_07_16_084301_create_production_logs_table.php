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
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained()->onDelete('restrict');
            $table->foreignId('flock_id')->constrained()->onDelete('restrict');

            $table->integer('chicken_count')->default(0);
            $table->integer('age')->default(0);
            $table->integer('mortality_count')->default(0);

            // Calculated column (net_count = chicken_count - mortality_count)
            $table->integer('net_count')->storedAs('chicken_count - mortality_count');

            $table->double('total_weight', 10, 2)->default(0);

            // avg_weight = total_weight / net_count
            $table->double('avg_weight')->storedAs('CASE WHEN net_count > 0 THEN total_weight / net_count ELSE 0 END');

            $table->double('water_consumed', 10, 2)->default(0);
            // avg_water_consumed = water_consumed / net_count
            $table->double('avg_water_consumed')->storedAs('CASE WHEN net_count > 0 THEN water_consumed / net_count ELSE 0 END');

            $table->double('feed_consumed', 10, 2)->default(0);
            // avg_feed_consumed = feed_consumed / net_count
            $table->double('avg_feed_consumed')->storedAs('CASE WHEN net_count > 0 THEN feed_consumed / net_count ELSE 0 END');

            $table->double('day_lowest_temperature', 10, 2)->nullable();
            $table->dateTime('day_lowest_temperature_time')->nullable();

            $table->double('day_peak_temperature', 10, 2)->nullable();
            $table->dateTime('day_peak_temperature_time')->nullable();

            $table->double('day_lowest_humidity', 10, 2)->nullable();
            $table->dateTime('day_lowest_humidity_time')->nullable();

            $table->double('day_peak_humidity', 10, 2)->nullable();
            $table->dateTime('day_peak_humidity_time')->nullable();

            $table->double('fcr', 10, 3)->default(0);
            $table->double('fcr_standard_diff', 10, 3)->default(0);

            $table->boolean('vet_visited')->default(false);
            $table->boolean('is_vaccinated')->default(false);

            $table->foreignId('user_id')->constrained()->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_logs');
    }
};
