<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shed_parameter_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained()->onDelete('cascade');
            $table->string('parameter_name');
            $table->string('unit')->nullable();
            $table->decimal('min_value', 8, 2)->nullable();
            $table->decimal('max_value', 8, 2)->nullable();
            $table->decimal('avg_value', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['shed_id', 'parameter_name']); // Prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shed_parameter_limits');
    }
};
