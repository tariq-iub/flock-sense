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
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->string('currency', 10)->default('USD');
            $table->enum('billing_interval', ['monthly', 'yearly', 'weekly', 'one_time'])->default('monthly');
            $table->integer('trial_period_days')->default(0);

            // Poultry farm SaaS tiered limits
            $table->unsignedInteger('max_farms')->default(1);
            $table->unsignedInteger('max_sheds')->default(1);
            $table->unsignedInteger('max_flocks')->default(1);
            $table->unsignedInteger('max_devices')->default(1);
            $table->unsignedInteger('max_users')->default(1);

            // Feature-based access control
            $table->json('feature_flags')->nullable(); // e.g., { "auto_control": true, "reporting": true, "analytics": false }
            $table->json('meta')->nullable(); // for future extensibility (e.g., coupons, add-ons, etc.)
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
