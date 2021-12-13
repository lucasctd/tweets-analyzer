<?php
// phpcs:ignore
namespace App\Providers;

use App\Interfaces\FilterInterface;
use App\Models\PreCandidato;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

// phpcs:ignore
class AppServiceProvider extends ServiceProvider
{

    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        FilterInterface::class => PreCandidato::class,
    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
