<?php
namespace App\Http\Controllers;

use App\Jobs\LoadUsersDataJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use App\Jobs\LoadSentimentsJob;
use App\Models\PreCandidato;
use App\Jobs\UpdateOwnersLocationJob;
use App\Models\Tweet;
use App\Interfaces\FilterInterface;

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
     * @return Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }
}
