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
        Schema::create('chart_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_id')->constrained('charts')->onDelete('restrict');
            $table->string('type');
            $table->integer('day');
            $table->integer('weight');
            $table->float('daily_gain')->nullable();
            $table->float('avg_daily_gain')->nullable();
            $table->float('daily_intake')->nullable();
            $table->float('cum_intake')->nullable();
            $table->float('fcr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_data');
    }
};
