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
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['broiler', 'layer', 'dual-purpose'])->default('broiler');
            $table->text('origin')->nullable();
            $table->text('features')->nullable();
            $table->string('weight_range')->nullable();
            $table->string('maturity_age')->nullable();
            $table->integer('avg_egg_production')->default(0);
            $table->double('avg_egg_weight')->default(0.0);
            $table->integer('hatching_period')->nullable();
            $table->double('hatchability')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeds');
    }
};
