<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    // Show plans, handle upgrade/downgrade
    public function showPlans()
    {
        $plans = Pricing::active()
            ->orderBy('sort_order')
            ->get();

        return view('subscription.plans', compact('plans'));
    }

    // Start trial or create Stripe checkout session
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $plan = Pricing::findOrFail($request->input('plan_id'));

        // Start trial if eligible
        if ($plan->trial_period_days > 0 && !$user->isOnTrial()) {
            $user->trial_ends_at = now()->addDays($plan->trial_period_days);
            $user->pricing_id = $plan->id;
            $user->subscription_status = 'trial';
            $user->save();

            // Optionally: create free subscription record in Cashier
            return redirect()
                ->route('dashboard')
                ->with('success', "Trial started! You have {$plan->trial_period_days} days.");
        }

        // Create Stripe checkout session
        $checkoutSession = $user->newSubscription('default', $plan->stripe_price_id)
            ->checkout([
                'success_url' => route('subscription.success'),
                'cancel_url' => route('subscription.cancelled'),
            ]);

        return redirect($checkoutSession->url);
    }

    // Cancel
    public function cancel()
    {
        $user = Auth::user();
        if ($user->activeSubscription()) {
            $user->subscription('default')->cancel();
            $user->subscription_status = 'cancelled';
            $user->save();
        }
        return back()->with('success', 'Subscription cancelled.');
    }

    // Resume
    public function resume()
    {
        $user = Auth::user();
        if ($user->subscription('default')->onGracePeriod()) {
            $user->subscription('default')->resume();
            $user->subscription_status = 'active';
            $user->save();
        }
        return back()->with('success', 'Subscription resumed.');
    }
}
