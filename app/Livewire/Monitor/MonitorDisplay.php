<?php

namespace App\Livewire\Monitor;

use App\Models\Monitor;
use App\Models\Queue;
use App\Models\Counter;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class MonitorDisplay extends Component
{
    public Monitor $monitor;
    public $waitingQueues = [];
    public $servingCounters = [];
    public $refreshInterval = 5000; // 5 seconds refresh

    #[Title('Monitor Display')]

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
        $this->loadData();
    }

    public function loadData()
    {
        // Get the services associated with this monitor
        $serviceIds = $this->monitor->services->pluck('id')->toArray();
        
        // Get waiting queues for the services
        $this->waitingQueues = Queue::whereIn('service_id', $serviceIds)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->get()
            ->groupBy('service_id');
            
        // Get currently serving counters
        $this->servingCounters = Counter::where('branch_id', $this->monitor->branch_id)
            ->where('is_active', true)
            ->whereHas('currentQueue', function ($query) use ($serviceIds) {
                $query->whereIn('service_id', $serviceIds)
                    ->where('status', 'serving');
            })
            ->with(['currentQueue.service', 'user'])
            ->get();
    }

    public function getListeners()
    {
        return [
            'echo:queues,QueueStatusUpdated' => 'loadData',
            'refresh-data' => 'loadData',
        ];
    }

    public function render()
    {
        return view('livewire.monitor.monitor-display')
            ->layout('layouts.monitor');
    }
}
