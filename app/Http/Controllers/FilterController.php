<?php

namespace App\Http\Controllers;

use App\Interfaces\FilterInterface;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request -
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return resolve(FilterInterface::class)->find($id);
    }

    /**
     * Display the specified resource.
     *
     * @Get("/filter/all")
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        try {
            return resolve(FilterInterface::class)->all();
        } catch (Exception $e) {
            return response()->json(['error' => 'Something got wrong loading the tweets from the database. Please check the log files.'], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id - Id do filtro
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request -
     * @param int                      $id      -
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id -
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
