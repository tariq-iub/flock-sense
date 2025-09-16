<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Recipient
            $table->morphs('notifiable'); // Ties to any model (e.g., ProductionLog, Device)
            $table->foreignId('farm_id')->nullable()->constrained()->onDelete('cascade'); // Optional, for farm-specific notifications
            $table->string('type'); // e.g., 'report_submitted', 'device_failure'
            $table->string('title'); // e.g., "New Report Submitted"
            $table->text('message'); // e.g., "Report for Shed X submitted"
            $table->json('data')->nullable(); // Extra metadata (e.g., report details)
            $table->boolean('is_read')->default(false)->index(); // Fast unread queries
            $table->timestamps();

            $table->index(['user_id', 'type']); // For filtering by user and type
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
