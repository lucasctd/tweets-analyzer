<?php

namespace App\Http\Controllers;

use App\Jobs\LoadDataJob;
use Illuminate\Http\Request;

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
}
