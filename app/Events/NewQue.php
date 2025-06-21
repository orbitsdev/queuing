<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewQue implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $queue = 'default'; 
    /**
     * Create a new event instance.


     */
    public function __construct(public $que)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('incoming-queues'),
        ];
    }

    public function broadcastWith(): array
    {
        return array(
            'queue' => $this->que,

        );
    }
     /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'queue.created';
    }
    /**
     * Determine if this event should broadcast.
     */



}
