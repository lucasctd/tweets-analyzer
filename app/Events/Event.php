<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Classe resposável pelos eventos relacionados ao carregamento de sentimentos
 *
 * @category Event
 * @package  App\Events
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
abstract class Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status;
    public $id;

    /**
     * Create a new event instance.
     *
     * @param string $status - Mensagem de status do job
     * @param int    $id     - Id do Job
     */
    public function __construct($status, $id = null)
    {
        $this->status = $status .' ('.Carbon::now('America/Bahia').')';
        $this->id = $id;
    }
}
