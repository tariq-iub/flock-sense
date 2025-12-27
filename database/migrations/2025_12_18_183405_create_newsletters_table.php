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
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();

            $table->string('subject');
            $table->text('preview_text')->nullable();

            // Store as HTML (from a rich editor) + optional plain text
            $table->longText('content_html');
            $table->longText('content_text')->nullable();

            // draft | pending | sending | sent | failed
            $table->string('status', 20)->default('draft');

            // scheduling
            $table->timestamp('send_at')->nullable();

            // metrics
            $table->unsignedInteger('target_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('last_error')->nullable();

            $table->foreignId('created_by')->nullable();

            $table->timestamps();
            $table->index(['status', 'send_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};
