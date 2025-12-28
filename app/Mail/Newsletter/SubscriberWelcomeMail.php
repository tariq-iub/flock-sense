<?php

namespace App\Mail\Newsletter;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberWelcomeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function build()
    {
        $brand = config('flocksense.brand_name', 'FlockSense');

        return $this->subject("You're subscribed to {$brand} updates")
            ->view('emails.newsletter.welcome', [
                'subject' => "You're subscribed",
                'preheader' => "Subscription confirmed for {$brand} newsletter.",
                'subscriber' => $this->subscriber,
                'unsubscribeUrl' => route('newsletter.unsubscribe', $this->subscriber->unsubscribe_token),
            ]);
    }
}
