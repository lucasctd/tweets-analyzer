<?php

namespace App\Http\Controllers;

use App\Jobs\LoadDataJob;
use App\Jobs\LoadUsersDataJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

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
        if($request->has('query') && $request->has('count')){
            LoadDataJob::dispatch($request->get('query'), $request->get('count'), $requestId);
            return response()->json(['message' => 'Your request is being processed.', 'eventId' => $requestId], 200);
        }else{
            return response()->json(['error' => 'At least one parameter is missing, please, provide both of them (query and count'], 401);
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
}
