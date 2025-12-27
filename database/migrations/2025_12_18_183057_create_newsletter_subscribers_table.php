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
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();

            // subscribed | unsubscribed | bounced | complained
            $table->string('status', 20)->default('subscribed');

            $table->timestamp('confirmed_at')->nullable();
            $table->string('unsubscribe_token', 64)->unique();

            // optional meta
            $table->string('source', 50)->nullable();     // e.g. "landing", "footer"
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->dateTime('last_sent_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
