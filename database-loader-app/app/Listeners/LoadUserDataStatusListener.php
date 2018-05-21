<?php

namespace App\Listeners;

use App\Events\LoadUserDataStatusEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoadUserDataStatusListener
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
     * @param  LoadUserDataStatusEvent  $event
     * @return void
     */
    public function handle(LoadUserDataStatusEvent $event)
    {
        //
    }
}
