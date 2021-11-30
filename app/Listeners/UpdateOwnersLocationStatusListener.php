<?php

namespace App\Listeners;

use App\Events\UpdateOwnersLocationStatusEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOwnersLocationStatusListener
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
     * @param  UpdateOwnersLocationStatusEvent  $event
     * @return void
     */
    public function handle(UpdateOwnersLocationStatusEvent $event)
    {
        //
    }
}
