<?php

namespace App\Console\Commands;

use App\Jobs\Newsletter\PrepareNewsletterDeliveriesJob;
use App\Models\Newsletter;
use Illuminate\Console\Command;

class DispatchPendingNewsletters extends Command
{
    protected $signature = 'newsletters:dispatch-pending';
    protected $description = 'Dispatch pending newsletters scheduled to be sent';

    public function handle(): int
    {
        $items = Newsletter::whereIn('status', ['pending'])
            ->where(function ($q) {
                $q->whereNull('send_at')->orWhere('send_at', '<=', now());
            })
            ->limit(20)
            ->get();

        foreach ($items as $n) {
            PrepareNewsletterDeliveriesJob::dispatch($n->id);
        }

        $this->info("Dispatched: {$items->count()}");
        return self::SUCCESS;
    }
}
