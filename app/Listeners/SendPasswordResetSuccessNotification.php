<?php

namespace App\Listeners;

use App\Notifications\PasswordResetSuccess;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordResetSuccessNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event) : void
    {
        $event->user->notify(new PasswordResetSuccess());
    }
}
