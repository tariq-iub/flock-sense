<?php

namespace App\Observers;

use App\Models\Farm;

class FarmObserver
{
    /**
     * Handle the Farm "created" event.
     */
    public function created(Farm $farm): void
    {
        //
    }

    /**
     * Handle the Farm "updated" event.
     */
    public function updated(Farm $farm): void
    {
        //
    }

    /**
     * Handle the Farm "deleted" event.
     */
    public function deleted(Farm $farm): void
    {
        //
    }

    /**
     * Handle the Farm "restored" event.
     */
    public function restored(Farm $farm): void
    {
        //
    }

    /**
     * Handle the Farm "force deleted" event.
     */
    public function forceDeleted(Farm $farm): void
    {
        //
    }
}
