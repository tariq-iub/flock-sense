@if(auth()->user()->isOnTrial())
    <div class="alert alert-info mb-2">
        <strong>Trial:</strong> {{ auth()->user()->trialDaysLeft() }} days remaining.
    </div>
@elseif(auth()->user()->subscription_status === 'active')
    <div class="alert alert-success mb-2">
        <strong>Subscription Active</strong>
    </div>
@elseif(auth()->user()->subscription_status === 'past_due')
    <div class="alert alert-warning mb-2">
        <strong>Subscription Past Due</strong>
    </div>
@elseif(auth()->user()->subscription_status === 'cancelled')
    <div class="alert alert-danger mb-2">
        <strong>Subscription Cancelled</strong>
    </div>
@else
    <div class="alert alert-secondary mb-2">
        <strong>No Active Subscription</strong>
    </div>
@endif
