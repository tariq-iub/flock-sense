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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // For logical grouping/category (e.g., "company", "aws")
            $table->string('group')->nullable()->index();
            // Unique key within group (e.g., "company_name")
            $table->string('key');
            // Actual value (string, JSON, array, etc)
            $table->json('value')->nullable();
            // Data type: string, int, bool, json, file, etc.
            $table->string('type')->nullable();
            // Mark if value is encrypted (for sensitive data)
            $table->boolean('is_encrypted')->default(false);
            // Optional description/help text
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
