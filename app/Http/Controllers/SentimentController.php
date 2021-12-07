<?php

namespace App\Http\Controllers;

use App\Jobs\LoadSentimentsJob;
use App\Models\Tweet;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

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
     * @return JsonResponse
     */
    public function analyzeSentiments(Request $request): JsonResponse
    {
        try {
            $count = 0;
            $tweets = Tweet::doesntHave('sentiment')->take($request->take)->get();
            $chunks = $tweets->chunk($request->chunk);
            foreach ($chunks as $chunk) {
                LoadSentimentsJob::dispatch($chunk, $count + 1)->delay(now()->addMinutes($count));
                $count++;
            }
            $jobs = [];
            for ($i = 1; $i <= $count; $i++) {
                $jobs[] = ['id' => $i, 'status' => 'Your request is being processed.', 'percent' => 0];
            }
            return response()->json(['message' => 'Your request is being processed.', 'jobs' => $jobs]);
        } catch (Throwable) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * Método responsável pelo retorno da view de carregamento de sentimentos do GNL
     *
     * @Get("/load")
     *
     * @link   https://cloud.google.com/natural-language/docs/quickstart-client-libraries
     * @return View
     */
    public function showStandartPage(): View
    {
        return view('sentiment.load');
    }
}
