<?php

namespace App\Listeners;

use App\Events\LoadDataStatusEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoadDataStatusListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoadDataStatusEvent  $event
     * @return void
     */
    public function handle(LoadDataStatusEvent $event)
    {
        //
    }
}
