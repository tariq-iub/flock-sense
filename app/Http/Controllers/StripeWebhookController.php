<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentLog;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    public function handleInvoicePaymentSucceeded($payload)
    {
        $user = User::where('stripe_id', $payload['data']['object']['customer'])->first();
        if ($user) {
            $user->subscription_status = 'active';
            $user->save();

            PaymentLog::create([
                'user_id' => $user->id,
                'stripe_payment_id' => $payload['data']['object']['id'],
                'amount' => $payload['data']['object']['amount_paid'] / 100,
                'currency' => $payload['data']['object']['currency'],
                'status' => 'success',
                'payload' => $payload,
            ]);
        }
        return response('Webhook Handled', 200);
    }

    public function handleInvoicePaymentFailed($payload)
    {
        $user = User::where('stripe_id', $payload['data']['object']['customer'])->first();
        if ($user) {
            $user->subscription_status = 'past_due';
            $user->save();

            PaymentLog::create([
                'user_id' => $user->id,
                'stripe_payment_id' => $payload['data']['object']['id'],
                'amount' => $payload['data']['object']['amount_due'] / 100,
                'currency' => $payload['data']['object']['currency'],
                'status' => 'failed',
                'payload' => $payload,
            ]);
        }
        return response('Webhook Handled', 200);
    }

    // Optionally override more handlers: handleCustomerSubscriptionDeleted, etc.
}
