<?php

namespace App\Events;

use App\Models\Queue;
use Illuminate\Broadcasting\Channel;



use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallNumber implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $queue = 'default';
    /**
     * Create a new event instance.
     */
    public function __construct(public Queue $que)
    {
          logger()->info('QueueStatusChanged event created', [
            'queue_id' => $this->que->id,
            'status' => $this->que->status,
            'branch_id' => $this->que->branch_id,
            'service_id' => $this->que->service_id
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('called-queue.'.$this->que->branch_id.'.'.$this->que->service_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->que->id,
            'branch_id' => $this->que->branch_id,
            'service_id' => $this->que->service_id,
            'number' => $this->que->number,
            'ticket_number' => $this->que->ticket_number,
            'status' => $this->que->status,
            'counter_id' => $this->que->counter_id,
            'counter_name' => $this->que->counter ? $this->que->counter->name : null,
            'service_name' => $this->que->service ? $this->que->service->name : null,
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue.callnumber';
    }
}
