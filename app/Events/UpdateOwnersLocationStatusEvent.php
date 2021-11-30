<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;

/**
 * Classe resposável pelos eventos relacionados a atualização da localização dos usuários do twitter
 *
 * @category Event
 * @package  App\Events
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class UpdateOwnersLocationStatusEvent extends Event
{

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('location-channel');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'update-owners-location-status';
    }
}
