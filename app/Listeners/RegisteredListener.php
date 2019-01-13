<?php

namespace App\Listeners;

use App\Services\VerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisteredListener implements ShouldQueue
{
    use InteractsWithQueue;

    private $verificationService;

    /**
     * Create the event listener.
     *
     * @param VerificationService $verificationService
     */
    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService=$verificationService;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //
        $this->verificationService->send($event->user);
    }

    public function failed(Registered $event, $exception)
    {

    }
}
