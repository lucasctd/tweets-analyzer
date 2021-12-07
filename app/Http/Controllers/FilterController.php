<?php

namespace App\Http\Controllers;

use App\Interfaces\FilterInterface;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
 * @Resource("filter")
 */
class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request -
     *
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id - Id do filtro
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        return resolve(FilterInterface::class)->find($id);
    }

    /**
     * Display the specified resource.
     *
     * @Get("/filter/all")
     *
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {
        try {
            return resolve(FilterInterface::class)->all();
        } catch (Throwable) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id - Id do filtro
     *
     * @return void
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return void
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return void
     */
    public function destroy(int $id)
    {
        //
    }
}
