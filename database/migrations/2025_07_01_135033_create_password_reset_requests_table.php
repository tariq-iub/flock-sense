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
        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp'); // hashed
            $table->boolean('is_verified')->default(false);
            $table->datetime('verified_at')->nullable();
            $table->datetime('reset_at')->nullable();
            $table->integer('attempts')->default(3);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->datetime('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
