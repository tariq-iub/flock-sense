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
        Schema::create('farm_staff', function (Blueprint $table) {
            $table->foreignId('farm_id')->constrained('farms');
            $table->foreignId('worker_id')->constrained('users', 'id');
            $table->dateTime('link_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_staff');
    }
};
