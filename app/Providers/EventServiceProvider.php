<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'Illuminate\Auth\Events\Registered'=>[
            'App\Listeners\RegisteredListener'
        ],
        'App\Events\JudgeEvent'=>[
            'App\Listeners\JudgeListener'
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
