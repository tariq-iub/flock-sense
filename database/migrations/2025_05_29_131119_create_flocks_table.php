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
        Schema::create('flocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('shed_id')->constrained();
            $table->foreignId('breed_id')->constrained();
            $table->date('start_date');
            $table->integer('initial_quantity');
            $table->integer('current_quantity');
            $table->enum('status', ['active', 'sold', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flocks');
    }
};
