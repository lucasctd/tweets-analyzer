<?php

namespace App\Http\Controllers;

use App\Jobs\LoadDataJob;
use App\Jobs\LoadUsersDataJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use App\Jobs\LoadSentimentsJob;
use App\Models\PreCandidato;
use App\Jobs\UpdateOwnersLocationJob;
use App\Models\Tweet;
/**
 * 
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license 
 * 
 * Classe resposável por receber as requisições da aplicação
 */
class AppController extends Controller
{
    /**
     * 
     * @Get("/")
     */
    public function index()
    {
        return view('loader');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @Post("/load-data")
     */
    public function loadData(Request $request)
    {
        $requestId = hexdec(uniqid());
        if ($request->has('count') && (($request->has('premium') && $request->has('fromDate') && $request->has('toDate')) || !$request->has('premium'))) {
            LoadDataJob::dispatch($request->premium, $request->count, $request->precandidato, $request->fromDate, $request->toDate, $requestId);

            return response()->json(['message' => 'Your request is being processed.', 'eventId' => $requestId], 200);
        } else {
            return response()->json(['error' => 'At least one parameter is missing, please, provide both of them (\'count\', \'fromDate\' and \'toDate\''], 401);
        }
    }

    /**
     * @Post("/load-users-data")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadUsersData()
    {
        try {
            $users = DB::table('tweet')->select('tweet_owner')->distinct()->get();
            LoadUsersDataJob::dispatch($users);

            return response()->json(['message' => 'Your request is being processed.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * @Post("/analyze-sentiment")
     *
     * @param Request $request
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
                LoadSentimentsJob::dispatch($chunk, $count)->delay(now()->addMinutes($count * 1));
                ++$count;
            }
            $jobs = [];
            for ($i = 1; $i < $count; ++$i) {
                $jobs[] = ['id' => $i, 'status' => '', 'count' => 0];
            }

            return response()->json(['message' => 'Your request is being processed.', 'jobs' => $jobs], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * @Get("/precandidatos")
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function findPrecandidatos()
    {
        try {
            return PreCandidato::all();
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * @Post("/update-owners-location")
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
}
