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
        Schema::create('farm_managers', function (Blueprint $table) {
            $table->foreignId('farm_id')->constrained('farms');
            $table->foreignId('manager_id')->constrained('users', 'id');
            $table->timestamp('link_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_managers');
    }
};
