<?php
namespace App\Exceptions;

use Exception;

/**
 * Classe de customização das exceções da aplicação
 *
 * @category Exception
 * @package  App\Exceptions
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class AppException
{
    /**
     * Escreve toda a exceção no log da aplicação
     *
     * @param Exception $exception - Exceção capturada
     *
     * @return string
     */
    public static function getTraceAsString(Exception $exception): string
    {
        $rtn = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $current_file = "[internal function]";
            if (isset($frame['file'])) {
                $current_file = $frame['file'];
            }
            $current_line = "";
            if (isset($frame['line'])) {
                $current_line = $frame['line'];
            }
            $rtn .= sprintf(
                "#%s %s(%s): %s(%s)\n",
                $count,
                $current_file,
                $current_line,
                $frame['function'],
                $args
            );
            $count++;
        }
        return $rtn;
    }
}
