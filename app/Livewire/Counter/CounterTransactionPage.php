<?php

namespace App\Livewire\Counter;

use Livewire\Component;
use App\Models\Queue;
use WireUi\Traits\WireUiActions;

class CounterTransactionPage extends Component
{
    use WireUiActions;

    public $currentTicket;
    public $nextTickets = [];
    public $status = 'active'; // active or break
    public $breakMessage = '';

    public function mount()
    {
        $this->loadQueue();
    }

    public function loadQueue()
    {
        // Example logic — adjust as needed
        $this->currentTicket = Queue::where('status', 'called')->first();
        $this->nextTickets = Queue::where('status', 'waiting')->orderBy('created_at')->take(3)->get();
    }

    public function callNext()
    {
        $next = Queue::where('status', 'waiting')->orderBy('created_at')->first();
        if ($next) {
            $next->update(['status' => 'called']);
            $this->loadQueue();
            $this->notification()->success('Next ticket called.');
        } else {
            $this->notification()->info('No waiting tickets.');
        }
    }

    public function serveCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update(['status' => 'served']);
            $this->notification()->success('Ticket served.');
            $this->loadQueue();
        }
    }

    public function holdCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update(['status' => 'held']);
            $this->notification()->success('Ticket put on hold.');
            $this->loadQueue();
        }
    }

    public function skipCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update(['status' => 'skipped']);
            $this->notification()->success('Ticket skipped.');
            $this->loadQueue();
        }
    }

    public function toggleBreak()
    {
        if ($this->status === 'active') {
            $this->status = 'break';
            $this->breakMessage = 'On break, back in 5 mins.';
        } else {
            $this->status = 'active';
            $this->breakMessage = '';
        }
    }

    public function render()
    {
        return view('livewire.counter.counter-transaction-page');
    }
}
