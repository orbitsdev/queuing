<?php

namespace App\Livewire\Monitor;

use App\Models\Branch;
use App\Models\Counter;
use App\Models\Monitor;
use App\Models\Que;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class DisplayPage extends Component
{
    /**
     * The monitor instance for this display page.
     */
    public Monitor $monitor;
    public $branch;
    public $services;
    public $servingQueues;
    public $waitingQueues;
    public $lastEchoUpdateTime = null;
    public $pollingActive = false;
    public $pollingInterval = 30000; // 30 seconds fallback polling interval

    /**
     * Initialize the component with the monitor.
     */
    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->branch = Branch::findOrFail($this->monitor->branch_id);
        $this->services = Service::whereIn('id', $this->monitor->services->pluck('id'))->get();
        $this->lastEchoUpdateTime = now()->timestamp;
        $this->loadQueues();
    }


    #[On('echo:incoming-queue.{monitor.branch_id}.*,.queue.updated')]
    public function handleQueueUpdate($event)
    {

        if (isset($event['service_id'])) {
            if (in_array($event['service_id'], $this->monitor->services->pluck('id')->toArray())) {
                Log::info('Queue update received via Echo', [
                    'monitor_id' => $this->monitor->id,
                    'queue_id' => $event['id'] ?? null,
                    'status' => $event['status'] ?? null,
                    'channel' => 'incoming-queue.' . $this->monitor->branch_id . '.' . $event['service_id']
                ]);


                $this->refreshFromEcho($event);
            }
        }
    }

    #[On('refreshFromEcho')]
    public function refreshFromEcho($event = null)
    {
        Log::info('Refresh from Echo event received', [
            'monitor_id' => $this->monitor->id,
            'event_data' => $event
        ]);


        $this->lastEchoUpdateTime = now()->timestamp;
        $this->pollingActive = false;

        $this->loadQueues();
    }

    public function checkPollingStatus()
    {
        $currentTime = now()->timestamp;
        $timeSinceLastUpdate = $currentTime - ($this->lastEchoUpdateTime ?? $currentTime);

        // If no Echo updates for 2 minutes, activate polling
        if ($timeSinceLastUpdate > 120) {
            $this->pollingActive = true;
            $this->loadQueues();
        }
    }

    /**
     * Load the latest queue data.
     */
    public function loadQueues()
    {
        $serviceIds = $this->monitor->services->pluck('id');

        // Get currently serving queues for this monitor's services
        $this->servingQueues = Queue::whereIn('service_id', $serviceIds)
            ->where('status', 'serving')
            ->with('counter')
            ->orderBy('called_at')
            ->get();

        // Get waiting queues for this monitor's services (limited to 10)
        $this->waitingQueues = Queue::whereIn('service_id', $serviceIds)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->take(10)
            ->get();
    }

    /**
     * Render the component with the latest queue data.
     */
    public function render()
    {
        $this->checkPollingStatus();

        return view('livewire.monitor.display-page', [
            'servingQueues' => $this->servingQueues,
            'waitingQueues' => $this->waitingQueues,
        ]);
    }
}
