<?php

namespace App\Events;

use App\Model\RunResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JudgeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var RunResult
     */
    private $result;

    /**
     * Create a new event instance.
     *
     * @param RunResult $result
     */
    public function __construct(RunResult $result)
    {
        //
        $this->result = $result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
