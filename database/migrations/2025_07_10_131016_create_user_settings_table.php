<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();

            $table->enum('security_level', ['low', 'medium', 'high'])->default('medium');
            $table->enum('backup_frequency', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->boolean('notifications_email')->default(true);
            $table->boolean('notifications_sms')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
}
