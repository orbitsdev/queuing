<?php

namespace App\Livewire\Monitor;

use App\Models\Queue;
use App\Models\Monitor;
use Livewire\Component;

class DisplayPage extends Component
{
    public Monitor $monitor;

    public function mount(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function render()
    {
        $serviceIds = $this->monitor->services->pluck('id');

        // ✅ Now Serving Tickets — include counter info
        $servingQueues = Queue::whereIn('service_id', $serviceIds)
            ->where('status', 'serving')
            ->with('counter')
            ->orderBy('called_at')
            ->get();

        // ✅ Waiting Tickets - limit to 9 tickets
        $waitingQueues = Queue::whereIn('service_id', $serviceIds)
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->take(10)
            ->get();

        return view('livewire.monitor.display-page', [
            'servingQueues' => $servingQueues,
            'waitingQueues' => $waitingQueues,
        ]);
    }
}
