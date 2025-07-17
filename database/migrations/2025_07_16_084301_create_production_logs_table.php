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
            $table->dateTime('production_log_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('age')->default(0);

            // Count Attributes
            $table->integer('day_mortality_count')->default(0);
            $table->integer('night_mortality_count')->default(0);
            $table->integer('net_count')->default(0);
            $table->double('livability', 10, 3)->default(0);

            // Feed Attributes
            $table->double('day_feed_consumed', 10, 2)->default(0);
            $table->double('night_feed_consumed', 10, 2)->default(0);
            $table->double('avg_feed_consumed')->storedAs('CASE WHEN net_count > 0 THEN (day_feed_consumed + night_feed_consumed) / net_count ELSE 0 END');

            // Water Attributes
            $table->double('day_water_consumed', 10, 2)->default(0);
            $table->double('night_water_consumed', 10, 2)->default(0);
            $table->double('avg_water_consumed')->storedAs('CASE WHEN net_count > 0 THEN (day_water_consumed + night_water_consumed) / net_count ELSE 0 END');

            // Vaccination Attributes
            $table->boolean('is_vaccinated')->default(false);
            $table->string('day_medicine')->nullable();
            $table->string('night_medicine')->nullable();

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
