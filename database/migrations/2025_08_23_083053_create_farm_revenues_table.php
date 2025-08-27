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
        Schema::create('farm_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('shed_id')->nullable();
            $table->unsignedBigInteger('flock_id')->nullable();
            $table->date('revenue_date');
            $table->string('category', 50); // e.g., live_sale, byproduct, manure, other
            $table->string('description')->nullable();

            $table->decimal('quantity', 12, 3)->nullable();   // e.g., 1500.000 kg live
            $table->string('unit', 20)->nullable();            // e.g., kg
            $table->decimal('unit_price', 14, 2)->nullable();  // PKR/kg
            $table->decimal('amount', 14, 2)->nullable();      // PKR (if null, derive quantity*unit_price)

            $table->char('currency', 3)->default('PKR');
            $table->string('buyer')->nullable();
            $table->string('invoice_no')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('farm_id')->references('id')->on('farms')->cascadeOnDelete();
            $table->foreign('shed_id')->references('id')->on('sheds')->nullOnDelete();
            $table->foreign('flock_id')->references('id')->on('flocks')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['farm_id', 'revenue_date']);
            $table->index(['shed_id', 'revenue_date']);
            $table->index(['flock_id', 'revenue_date']);
            $table->index(['farm_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_revenues');
    }
};
