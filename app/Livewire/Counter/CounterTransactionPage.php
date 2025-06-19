<?php

namespace App\Livewire\Counter;

use App\Models\Queue;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;

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

    public function selectQueue($queueId)
{
    if ($this->currentTicket) {
        $this->dialog()->error(
            title: 'Already Serving',
            description: 'You are already handling a ticket. Please finish it first.'
        );
        return;
    }

    DB::beginTransaction();

try {
    $queue = Queue::where('id', $queueId)
        ->where('status', 'waiting')
        ->whereNull('counter_id')
        ->lockForUpdate()
        ->first();

    if (!$queue) {
        DB::rollBack();
        $this->dialog()->error(
            title: 'Ticket Unavailable',
            description: 'This ticket was already selected by another counter.'
        );
        $this->loadQueue();
        return;
    }

    $queue->update([
        'counter_id' => $this->counter->id,
        'user_id' => auth()->id(),
        'status' => 'called',
        'called_at' => now(),
    ]);

    auth()->user()->update([
        'queue_id' => $queue->id,
        'counter_id' => $this->counter->id
    ]);

    DB::commit();

    $this->dialog()->success(
        title: 'Ticket Assigned',
        description: "You are now serving ticket {$queue->ticket_number}."
    );

    $this->loadQueue();

} catch (\Throwable $e) {
    DB::rollBack();
    report($e);

    $this->dialog()->error(
        title: 'System Error',
        description: 'Something went wrong. Please try again.'
    );
}


}

public function cancelSelectedQueue()
{
    if (!$this->currentTicket) {
        // Nothing to cancel
        return;
    }

    $this->dialog()->confirm([
        'title'       => 'Confirm Cancel',
        'description' => 'Are you sure you want to cancel this selection? This will free your counter to select another queue.',
        'acceptLabel' => 'Yes, Cancel',
        'method'      => 'confirmCancelSelectedQueue',
    ]);
}

public function confirmCancelSelectedQueue()
{
    DB::beginTransaction();
try {
    $this->currentTicket->update([
        'counter_id' => null,
        'user_id' => null,
        'status' => 'waiting',
    ]);

    auth()->user()->update([
        'queue_id' => null,
    ]);

    DB::commit();

    $this->dialog()->success(
        title: 'Cancelled',
        description: 'You can now select another queue.'
    );

    $this->loadQueue();

} catch (\Throwable $e) {
    DB::rollBack();
    report($e);
    $this->dialog()->error(
        title: 'System Error',
        description: 'Something went wrong. Please try again.'
    );
}

}

public function logoutCounter()
{
    $this->dialog()->confirm([
        'title'       => 'Confirm Logout',
        'description' => 'Are you sure you want to logout from this counter? Any assigned queue will be released.',
        'acceptLabel' => 'Yes, Logout',
        'method'      => 'confirmLogoutCounter',
    ]);
}

public function confirmLogoutCounter()
{
    DB::transaction(function () {
        $user = auth()->user();

        // If user has a queue, reset it
        if ($user->queue_id) {
            $queue = \App\Models\Queue::find($user->queue_id);
            if ($queue) {
                $queue->update([
                    'counter_id' => null,
                    'user_id' => null,
                    'status' => 'waiting',
                ]);
            }
        }

        // Clear user counter & queue
        $user->update([
            'queue_id' => null,
            'counter_id' => null,
        ]);
    });

    auth()->logout();
    return redirect()->route('counter.select');
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

    public function selectQue(){
        //check if not selected by other counter
        //chekc if current user doesnt have selected ticket
        //check if ticket is not expired
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
