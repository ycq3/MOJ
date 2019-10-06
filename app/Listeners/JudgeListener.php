<?php

namespace App\Listeners;

use App\Events\JudgeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JudgeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  JudgeEvent  $event
     * @return void
     */
    public function handle(JudgeEvent $event)
    {
        //
    }
}
