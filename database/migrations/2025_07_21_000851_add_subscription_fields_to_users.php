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
        Schema::table('users', function (Blueprint $table) {
            // Track the user's current plan, trial, etc.
            $table->unsignedBigInteger('pricing_id')->nullable()->after('id'); // Current selected pricing plan
            $table->dateTime('trial_ends_at')->nullable()->after('pricing_id');
            $table->string('subscription_status')->nullable()->after('trial_ends_at'); // active, cancelled, past_due, trial, expired
            $table->string('stripe_customer_id')->nullable()->after('subscription_status');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pricing_id', 'trial_ends_at', 'subscription_status', 'stripe_customer_id', 'stripe_subscription_id']);
        });
    }
};
