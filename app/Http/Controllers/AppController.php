<?php
namespace App\Http\Controllers;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Illuminate\Contracts\View\View;

/**
 * Classe resposável por receber as requisições da aplicação
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class AppController extends Controller
{
    /**
     * Página inicial da aplicação
     *
     * @Get("/")
     *
     * @return View
     */
    public function index(): View
    {
        return view('home');
    }
}
