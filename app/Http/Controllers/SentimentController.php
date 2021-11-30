<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\LoadSentimentsJob;
use App\Models\Tweet;

/**
 * Classe resposável por receber as requisições relacionadas aos sentimentos
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 *
 * @Controller(prefix="sentiment")
 */
class SentimentController extends Controller
{
    /**
     * Método responsável pela análise de sentimentos
     *
     * @param Request $request - Requisição feita
     *
     * @Post("/analyze")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeSentiments(Request $request)
    {
        try {
            $count = 0;
            $tweets = Tweet::doesntHave('sentiment')->take($request->take)->get();
            $chunks = $tweets->chunk($request->chunk);
            foreach ($chunks as $chunk) {
                LoadSentimentsJob::dispatch($chunk, $count + 1)->delay(now()->addMinutes($count * 1));
                $count++;
            }
            $jobs = [];
            for ($i = 1; $i <= $count; $i++) {
                $jobs[] = ['id' => $i, 'status' => 'Your request is being processed.', 'percent' => 0];
            }
            return response()->json(['message' => 'Your request is being processed.', 'jobs' => $jobs], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * Método responsável pelo retorno da view de carregamento de sentimentos do GNL
     *
     * @Get("/load")
     *
     * @link   https://cloud.google.com/natural-language/docs/quickstart-client-libraries
     * @return \Illuminate\View\View
     */
    public function showStandartPage()
    {
        return view('sentiment.load');
    }
}
