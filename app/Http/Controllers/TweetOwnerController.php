<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateOwnersLocationJob;

/**
 * Classe resposável por receber as requisições relacionadas aos donos de tweets
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 *
 * @Controller(prefix="tweet-owner")
 */
class TweetOwnerController extends Controller
{
    /**
     * Método responsável por atualizar a localização dos usuários
     *
     * @Post("/update-location")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOwnersLocation()
    {
        try {
            UpdateOwnersLocationJob::dispatch();
            return response()->json(['message' => 'Your request is being processed.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * Método responsável pelo retorno da view responsável pela atualização da localização dos usuários
     *
     * @Get("/update-location")
     *
     * @link   https://cloud.google.com/natural-language/docs/quickstart-client-libraries
     * @return \Illuminate\View\View
     */
    public function showUpdateLocationPage()
    {
        return view('tweet-owner.update-location');
    }
}
