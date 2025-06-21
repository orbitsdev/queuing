<?php

namespace App\Events;

use App\Models\Queue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueStatusChanged implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Laravel queue name for the job
    public $queue = 'default';

    /**
     * Create a new event instance.
     */
    public function __construct(public Queue $que)
    {
        // Log when the event is constructed
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
        // Create a single channel with combined branch and service IDs
        return [
            new Channel('incoming-queue.'.$this->que->branch_id.'.'.$this->que->service_id),
        ];
    }

    /**
     * The data to broadcast with the event.
     */
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
            'created_at' => $this->que->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->que->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'queue.updated';
    }
}
