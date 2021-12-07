<?php
namespace App\Interfaces;

/**
 * Interface obrigatória nos jobs da aplicação
 *
 * @category Interface
 * @package  App\Interfaces
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
interface JobInterface
{
    /**
     * Dispara evento do job
     *
     * @param string $status - Status do Job
     *
     * @return void
     */
    public function fireEvent(string $status) : void;
}
