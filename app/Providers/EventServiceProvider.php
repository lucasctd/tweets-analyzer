<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\LoadDataStatusEvent' => [
            'App\Listeners\LoadDataStatusListener',
        ],
        'App\Events\LoadUserDataStatusEvent' => [
            'App\Listeners\LoadUserDataStatusListener',
        ],
        'App\Events\LoadSentimentsStatusEvent' => [
            'App\Listeners\LoadSentimentsStatusListener',
        ],
        'App\Events\UpdateOwnersLocationStatusEvent' => [
            'App\Listeners\UpdateOwnersLocationStatusListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
