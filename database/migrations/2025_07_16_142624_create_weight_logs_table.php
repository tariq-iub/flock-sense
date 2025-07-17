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
        Schema::create('weight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_log_id')->constrained()->onDelete('restrict');
            $table->foreignId('flock_id')->constrained()->onDelete('restrict');
            $table->double('weighted_chickens_count', 10, 3);
            $table->double('total_weight', 10, 3);
            $table->double('avg_weight', 10, 3)->default(0);
            $table->double('avg_weight_gain', 10, 3)->default(0);
            $table->double('aggregated_total_weight', 10, 3)->default(0);
            $table->double('feed_efficiency', 10, 3)->default(0);
            $table->double('feed_conversion_ratio', 10, 3)->default(0);
            $table->double('adjusted_feed_conversion_ratio', 10, 3)->default(0);
            $table->double('fcr_standard_diff', 10, 3)->default(0);
            $table->double('standard_deviation', 10, 3)->default(0);
            $table->double('coefficient_of_variation', 10, 3)->default(0);
            $table->double('production_efficiency_factor', 10, 3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weight_logs');
    }
};
