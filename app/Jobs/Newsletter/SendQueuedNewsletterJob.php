<?php

namespace App\Jobs\Newsletter;

use App\Mail\Newsletter\NewsletterBroadcastMail;
use App\Models\Newsletter;
use App\Models\NewsletterDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendQueuedNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $newsletterId) {}

    public function handle(): void
    {
        $newsletter = Newsletter::findOrFail($this->newsletterId);

        // send 500 per run (safe). Job can re-dispatch until done.
        $deliveries = NewsletterDelivery::where('newsletter_id', $newsletter->id)
            ->where('status', 'queued')
            ->with('subscriber')
            ->limit(500)
            ->get();

        if ($deliveries->isEmpty()) {
            // finalize
            $sentCount = NewsletterDelivery::where('newsletter_id', $newsletter->id)
                ->where('status', 'sent')
                ->count();

            $newsletter->update([
                'status' => 'sent',
                'sent_count' => $sentCount,
                'completed_at' => now(),
            ]);

            return;
        }

        foreach ($deliveries as $delivery) {
            $subscriber = $delivery->subscriber;

            // skip if unsubscribed mid-way
            if (! $subscriber || $subscriber->status !== 'subscribed') {
                $delivery->update(['status' => 'skipped']);

                continue;
            }

            try {
                Mail::to($subscriber->email)->queue(new NewsletterBroadcastMail($newsletter, $subscriber));

                $delivery->update(['status' => 'sent', 'sent_at' => now()]);
                $subscriber->update(['last_sent_at' => now()]);
            } catch (\Throwable $e) {
                $delivery->update(['status' => 'failed', 'error' => $e->getMessage()]);
                $newsletter->update(['last_error' => $e->getMessage()]);
            }
        }

        // keep sending remaining batches
        self::dispatch($newsletter->id)->delay(now()->addSeconds(10));
    }
}
