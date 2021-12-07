<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;

/**
 * Classe resposável pelos eventos relacionados ao carregamento de sentimentos
 *
 * @category Event
 * @package  App\Events
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class LoadSentimentsStatusEvent extends Event
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('sentiment-channel');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'load-sentiments-status-'.$this->id;
    }
}
