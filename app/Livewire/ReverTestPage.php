<?php

namespace App\Livewire;

use Livewire\Component;

class ReverTestPage extends Component
{
    public $lastEvent;

    public function getListeners()
    {
        return [
            'echo:incoming-queues,queue.created' => 'notifyNewQueue',
        ];
    }

   public function notifyNewQueue($data)
{
    $this->lastEvent = $data;
    $this->dispatch('queue-added', queue: $data);
}

    public function render()
    {
        return view('livewire.rever-test-page');
    }
}
