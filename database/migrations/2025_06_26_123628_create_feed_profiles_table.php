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
        Schema::create('feed_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id');
            /*
             * Feed Brands:
             * Popular broiler feed brands in some regions include
             * Asia Poultry Feeds, Paragons, Saffron, and Al-Noor.
             */
            $table->string('brand')->nullable();
            /*
             * Fermented Feed:
             * Fermented chicken feed can enhance nutrient absorption and may be particularly
             * beneficial for broilers.
             * Custom Feed:
             * Some farmers formulate their own feed, using ingredients like corn, soybean meal,
             * fish meal, and various additives.
             */
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_profiles');
    }
};
