@if(auth()->user()->pricing)
    <div class="mb-1">
        <strong>Current Plan:</strong> {{ auth()->user()->pricing->name }}
    </div>
@endif

@if(auth()->user()->activeSubscription())
    <div class="mb-1">
        <strong>Next Billing Date:</strong> {{ auth()->user()->activeSubscription()->asStripeSubscription()->current_period_end ? \Carbon\Carbon::createFromTimestamp(auth()->user()->activeSubscription()->asStripeSubscription()->current_period_end)->toFormattedDateString() : '-' }}
    </div>
@endif
