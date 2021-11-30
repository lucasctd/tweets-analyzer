<?php

namespace App\Listeners;

use App\Events\LoadSentimentsStatusEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoadSentimentsStatusListener
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
     * @param  LoadSentimentsStatusEvent  $event
     * @return void
     */
    public function handle(LoadSentimentsStatusEvent $event)
    {
        //
    }
}
