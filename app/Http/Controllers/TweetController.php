<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\LoadTweetsJob;

/**
 * Classe resposável por receber as requisições relacionadas aos sentimentos
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 *
 * @Controller(prefix="tweet")
 */
class TweetController extends Controller
{
    /**
     * Método responsável pela busca e persistência dos tweets no banco de dados
     *
     * @param Request $request - Requisição feita
     *
     * @Post("/premium/load")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadTweetsPremium(Request $request)
    {
        $requestId = hexdec(uniqid());
        if ($request->has('count') && $request->has('fromDate') && $request->has('toDate') && $request->has('filterId')) {
            LoadTweetsJob::dispatch(false, $request->count, $request->filterId, $request->fromDate, $request->toDate, $requestId);
            return response()->json(['message' => 'Your request is being processed.', 'eventId' => $requestId], 200);
        }
        return response()->json(['error' => 'At least one parameter is missing, please, provide both of them (\'count\', \'fromDate\' and \'toDate\''], 401);
    }

    /**
     * Método responsável pela busca e persistência dos tweets no banco de dados
     *
     * @param Request $request - Requisição feita
     *
     * @Post("/standart/load")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadTweetsStandart(Request $request)
    {
        $requestId = hexdec(uniqid());
        if ($request->has('count') && $request->has('until') && $request->has('filterId')) {
            LoadTweetsJob::dispatch(false, $request->count, $request->filterId, null, $request->until, $requestId);
            return response()->json(['message' => 'Your request is being processed.', 'eventId' => $requestId], 200);
        }
        return response()->json(['error' => 'At least one parameter is missing, please, provide all of them (\'count\', \'until\' and \'filterId\''], 401);
    }

    /**
     * Método responsável pelo retorno da view de busca de tweets pela Premium API do Twitter
     *
     * @Get("/premium")
     *
     * @return \Illuminate\View\View
     */
    public function showPremiumPage()
    {
        return view('tweet.premium');
    }

    /**
     * Método responsável pelo retorno da view de busca de tweets pela Standart API do Twitter
     *
     * @Get("/standart")
     *
     * @return \Illuminate\View\View
     */
    public function showStandartPage()
    {
        return view('tweet.standart');
    }
}
