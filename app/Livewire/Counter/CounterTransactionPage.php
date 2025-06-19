<?php

namespace App\Livewire\Counter;

use Livewire\Component;
use App\Models\Queue;
use WireUi\Traits\WireUiActions;

class CounterTransactionPage extends Component
{
    use WireUiActions;

    public $counter;
    public $currentTicket;
    public $nextTickets = [];
    public $holdTickets = [];
    public $others = [];
    public $status = 'active';
    public $breakMessage = '';
    public $selectedHoldTicket;

    public function mount()
    {
        $this->counter = auth()->user()->counter;

        if (!$this->counter) {
            return redirect()->route('counter.select');
        }

        $this->status = $this->counter->active ? 'active' : 'break';
        $this->breakMessage = $this->counter->break_message;

        $this->loadQueue();
    }

    public function loadQueue()
    {
        // Now Serving for this counter
        $this->currentTicket = Queue::where('counter_id', $this->counter->id)
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        // Next tickets: waiting, unassigned
        $this->nextTickets = Queue::where('branch_id', $this->counter->branch_id)
            ->where('status', 'waiting')
            ->whereNull('counter_id')
            ->orderBy('created_at')
            ->take(3)
            ->get();

        // Hold tickets for this counter
        $this->holdTickets = Queue::where('counter_id', $this->counter->id)
            ->where('status', 'held')
            ->orderBy('hold_started_at')
            ->get();

        // Others: tickets serving by other counters
        $this->others = Queue::where('branch_id', $this->counter->branch_id)
            ->whereIn('status', ['called', 'serving'])
            ->where('counter_id', '!=', $this->counter->id)
            ->with('counter')
            ->get();
    }

    public function callNext()
    {
        $next = Queue::where('branch_id', $this->counter->branch_id)
            ->where('status', 'waiting')
            ->whereNull('counter_id')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->first();

        if ($next) {
            $next->update([
                'counter_id' => $this->counter->id,
                'status' => 'called',
                'called_at' => now(),
            ]);
            $this->loadQueue();
            $this->notification()->success('Next ticket called.');
        } else {
            $this->notification()->info('No waiting tickets.');
        }
    }

    public function serveCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update([
                'status' => 'served',
                'served_at' => now(),
            ]);
            $this->notification()->success('Ticket served.');
            $this->loadQueue();
        }
    }

    public function holdCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update([
                'status' => 'held',
                'hold_started_at' => now(),
                'hold_reason' => 'Held by staff',
            ]);
            $this->notification()->success('Ticket put on hold.');
            $this->loadQueue();
        }
    }

    public function skipCurrent()
    {
        if ($this->currentTicket) {
            $this->currentTicket->update([
                'status' => 'skipped',
                'skipped_at' => now(),
            ]);
            $this->notification()->success('Ticket skipped.');
            $this->loadQueue();
        }
    }

    public function toggleBreak()
    {
        $newStatus = !$this->counter->active;
        $this->counter->update([
            'active' => $newStatus,
            'break_message' => $newStatus ? 'On break, back soon.' : null,
        ]);
        $this->status = $newStatus ? 'break' : 'active';
        $this->breakMessage = $this->counter->break_message;
    }

    public function resumeHold()
    {
        $ticket = Queue::find($this->selectedHoldTicket);
        if ($ticket && $ticket->status === 'held') {
            $ticket->update([
                'status' => 'called',
                'called_at' => now(),
            ]);
            $this->notification()->success('Hold ticket resumed.');
            $this->loadQueue();
        }
    }


    public function render()
    {
        return view('livewire.counter.counter-transaction-page');
    }
}
