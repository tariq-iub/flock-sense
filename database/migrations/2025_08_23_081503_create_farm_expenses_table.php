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
        Schema::create('farm_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farm_id');
            $table->unsignedBigInteger('shed_id')->nullable();
            $table->unsignedBigInteger('flock_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->date('expense_date');
            $table->string('description')->nullable();

            // Either provide amount directly OR quantity*unit_cost
            $table->decimal('quantity', 12, 3)->nullable();     // e.g., 125.000
            $table->string('unit', 20)->nullable();              // e.g., kg, L, pack
            $table->decimal('unit_cost', 14, 2)->nullable();     // PKR
            $table->decimal('amount', 14, 2)->nullable();        // PKR (if null, derive = quantity*unit_cost)

            $table->char('currency', 3)->default('PKR');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('reference_no')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('farm_id')->references('id')->on('farms')->cascadeOnDelete();
            $table->foreign('shed_id')->references('id')->on('sheds')->nullOnDelete();
            $table->foreign('flock_id')->references('id')->on('flocks')->nullOnDelete();
            $table->foreign('expense_id')->references('id')->on('expenses')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
//            $table->foreign('vendor_id')->references('id')->on('vendors')->nullOnDelete();

            $table->index(['farm_id', 'expense_date']);
            $table->index(['shed_id', 'expense_date']);
            $table->index(['flock_id', 'expense_date']);
            $table->index(['farm_id', 'expense_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_expenses');
    }
};
