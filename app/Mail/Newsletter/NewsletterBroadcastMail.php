<?php

namespace App\Mail\Newsletter;

use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterBroadcastMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Newsletter $newsletter,
        public NewsletterSubscriber $subscriber
    ) {}

    public function build()
    {
        return $this->subject($this->newsletter->subject)
            ->view('emails.newsletter.broadcast', [
                'subject' => $this->newsletter->subject,
                'preheader' => $this->newsletter->preview_text,
                'newsletter' => $this->newsletter,
                'subscriber' => $this->subscriber,
                'unsubscribeUrl' => route('newsletter.unsubscribe', $this->subscriber->unsubscribe_token),
            ]);
    }
}
