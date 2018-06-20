<?php

namespace App\Http\Controllers;

use App\Jobs\LoadDataJob;
use App\Jobs\LoadUsersDataJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Google\Cloud\Language\LanguageClient;
use Illuminate\Support\Facades\Log;
use App\Jobs\LoadSentimentsJob;
use App\Models\PreCandidato;

class AppController extends Controller
{

    /**
     * @Get("/")
     */
    public function index(){
        return view('loader');
    }

    /**
     * @Post("/load-data")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadData(Request $request){
        $requestId = hexdec(uniqid());
        if($request->has('count') && (($request->has('premium') && $request->has('fromDate') && $request->has('toDate'))  ||  !$request->has('premium'))){
            LoadDataJob::dispatchNow($request, $requestId);
            return response()->json(['message' => 'Your request is being processed.', 'eventId' => $requestId], 200);
        }else{
            return response()->json(['error' => 'At least one parameter is missing, please, provide both of them (\'count\', \'fromDate\' and \'toDate\''], 401);
        }
	}

    /**
     * @Post("/load-users-data")
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadUsersData(){
        try{
            $users = DB::table('tweet')->select('tweet_owner')->distinct()->get();
            LoadUsersDataJob::dispatch($users);
            return response()->json(['message' => 'Your request is being processed.'], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * @Post("/analyze-sentiment")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeSentiments(Request $request){
        try{
            LoadSentimentsJob::dispatch();
            return response()->json(['message' => 'Your request is being processed.'], 200);
        }catch (Exception $e){
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * @Get("/precandidatos")
     * @return \Illuminate\Http\JsonResponse
     */
    public function findPrecandidatos(){
        try{
            return PreCandidato::all();
        }catch (Exception $e){
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }
}
