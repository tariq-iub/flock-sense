<?php

namespace App\Jobs\Newsletter;

use App\Models\Newsletter;
use App\Models\NewsletterDelivery;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrepareNewsletterDeliveriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $newsletterId) {}

    public function handle(): void
    {
        $newsletter = Newsletter::findOrFail($this->newsletterId);

        // avoid duplicate preparation
        if (in_array($newsletter->status, ['sending','sent'])) {
            return;
        }

        $newsletter->update([
            'status' => 'sending',
            'started_at' => now(),
            'last_error' => null,
        ]);

        $query = NewsletterSubscriber::active()->select(['id']);

        $total = 0;
        $query->chunkById(1000, function ($chunk) use ($newsletter, &$total) {
            $rows = [];
            foreach ($chunk as $s) {
                $rows[] = [
                    'newsletter_id' => $newsletter->id,
                    'subscriber_id' => $s->id,
                    'status' => 'queued',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // insert ignore-like behavior: use upsert to avoid duplicates
            NewsletterDelivery::upsert(
                $rows,
                ['newsletter_id','subscriber_id'],
                ['status','updated_at']
            );

            $total += count($rows);
        });

        $newsletter->update(['target_count' => $total]);

        // dispatch send job in chunks
        SendQueuedNewsletterJob::dispatch($newsletter->id);
    }
}
