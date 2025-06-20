<?php

namespace App\Livewire\Counter;

use App\Models\Queue;
use App\Models\Setting;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
// use filament notficaiton
use Filament\Notifications\Notification;

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
    public $selectedHoldTicket = null;
    public $holdReason = '';

    public $showHoldModal = false;

    public $breakInputMessage = '';
public $showBreakModal = false;
public $queueCountToday = 0;

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

        // ✅ Mark as SERVING immediately
        $queue->update([
            'counter_id' => $this->counter->id,
            'user_id'    => auth()->id(),
            'status'     => 'serving',
            'called_at'  => now(),
            'serving_at' => now(),
        ]);

        auth()->user()->update([
            'queue_id'   => $queue->id,
            'counter_id' => $this->counter->id,
        ]);

        DB::commit();

        // $this->dialog()->success(
        //     title: 'Ticket Serving',
        //     description: "You are now serving ticket {$queue->ticket_number}."
        // );

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
        'acceptLabel' => 'Yes Continue ',
        'cancelLabel' => 'No',
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

        // ✅ Also clear the counter's user_id
        $this->counter->update([
            'user_id' => null,
        ]);

        // ✅ Clear user fields
        $user->update([
            'queue_id' => null,
            'counter_id' => null,
        ]);
    });

    $this->dialog()->success(
        title: 'Logged Out',
        description: 'You have been logged out from this counter.'
    );

    return redirect()->route('counter.select');
}


public function holdQueue()
{
    if (!$this->currentTicket) {
        return;
    }

    // Show modal (WireUI or custom)
    $this->holdReason = '';
    $this->showHoldModal = true;
}


public function triggerResumeSelectedHold()
{
    if (!$this->selectedHoldTicket) {
        return; // Do nothing if blank
    }

    // Check if user already has an active ticket
    if ($this->currentTicket) {
        $this->dialog()->error(
            title: 'Already Serving',
            description: 'Please complete or cancel your current ticket before resuming another.'
        );
        $this->selectedHoldTicket = null; // reset
        return;
    }

    // Ask confirmation
    $this->dialog()->confirm([
        'title' => 'Confirm Resume',
        'description' => 'Are you sure you want to resume this hold ticket?',
        'acceptLabel' => 'Yes, Resume',
        'method' => 'confirmResumeSelectedHold',
        'params' => $this->selectedHoldTicket,
    ]);
}
public function confirmResumeSelectedHold($queueId)
{
    DB::transaction(function () use ($queueId) {
        $queue = \App\Models\Queue::where('id', $queueId)
            ->where('status', 'held')
            ->where('counter_id', $this->counter->id)
            ->lockForUpdate()
            ->first();

        if (!$queue) {
            throw new \Exception('This hold ticket is no longer available.');
        }

        $queue->update([
            'status'      => 'serving',
            'called_at'   => now(),
            'serving_at'  => now(),
        ]);

        $user = auth()->user();
        $user->update([
            'queue_id' => $queue->id,
        ]);
    });

    $this->dialog()->success(
        title: 'Hold Resumed',
        description: 'The hold ticket has been resumed and is now serving.'
    );

    $this->selectedHoldTicket = null; // clear select
    $this->loadQueue();
}



public function confirmHoldQueueWithReason()
{
    DB::transaction(function () {
        $this->currentTicket->update([
            'status' => 'held',
            'hold_started_at' => now(),
            'hold_reason' => $this->holdReason ?? 'Held by staff',
        ]);

        auth()->user()->update([
            'queue_id' => null,
        ]);
    });

    $this->dialog()->success(
        title: 'Ticket On Hold',
        description: 'The ticket has been put on hold.'
    );

    $this->showHoldModal = false;
    $this->loadQueue();
}


// Method removed - using confirmHoldQueueWithReason() instead

public function completeQueue()
{
    if (!$this->currentTicket) {
        // Nothing to complete
        return;
    }

    $this->dialog()->confirm([
        'title'       => 'Confirm Complete',
        'description' => 'Are you sure you want to mark this ticket as served and free this counter?',
        'acceptLabel' => 'Yes, Complete',
        'method'      => 'confirmCompleteQueue',
    ]);
}
public function confirmCompleteQueue()
{
    DB::transaction(function () {
        // ✅ Mark as SERVED
        $this->currentTicket->update([
            'status'     => 'served',
            'served_at'  => now(),
        ]);

        auth()->user()->update([
            'queue_id' => null,
        ]);
    });

    $this->dialog()->success(
        title: 'Ticket Completed',
        description: 'The ticket has been marked as served. Ready for the next one!'
    );

    $this->loadQueue();
}

public function loadQueue()
{
    // ✅ Always get fresh allowed services for this counter
    $allowedServiceIds = $this->counter->services()->pluck('services.id');

    // ✅ 1️⃣ Current ticket for this counter
    if (auth()->user()->queue_id) {
        $this->currentTicket = Queue::find(auth()->user()->queue_id);
    } else {
        $this->currentTicket = null;
    }

    // ✅ 2️⃣ Next tickets matching allowed services
    $this->nextTickets = Queue::todayQueues()
        ->where('branch_id', $this->counter->branch_id)
        ->where('status', 'waiting')
        ->whereIn('service_id', $allowedServiceIds)
        ->whereNull('counter_id')
        ->orderBy('created_at')
        ->take(3)
        ->get();

    // ✅ 3️⃣ Hold tickets (always valid, tied to this counter)
    $this->holdTickets = Queue::todayQueues()
        ->where('counter_id', $this->counter->id)
        ->where('status', 'held')
        ->orderBy('hold_started_at')
        ->get();

    // ✅ 4️⃣ Tickets served by other counters (no filter)
    $this->others = Queue::todayQueues()
        ->where('branch_id', $this->counter->branch_id)
        ->whereIn('status', ['called', 'serving'])
        ->where('counter_id', '!=', $this->counter->id)
        ->with('counter')
        ->get();

    // ✅ 5️⃣ Count all waiting tickets for allowed services
    $this->queueCountToday = Queue::todayQueues()
        ->where('branch_id', $this->counter->branch_id)
        ->where('status', 'waiting')
        ->whereIn('service_id', $allowedServiceIds)
        ->count();
}





    // Method removed - using selectQueue flow instead

    // Method removed - using holdQueue() and confirmHoldQueueWithReason() instead

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

    // Method removed - using triggerResumeSelectedHold() and confirmResumeSelectedHold() instead
    public function startBreak()
    {
        // Use existing counter message or fallback to branch setting
        $default = $this->counter->break_message;

        if (!$default) {
            $default = Setting::where('branch_id', $this->counter->branch_id)
                ->orWhereNull('branch_id')
                ->value('default_break_message') ?? 'Not available';
        }

        $this->breakInputMessage = $default;

        $this->showBreakModal = true;
    }
    public function confirmStartBreak()
    {
        $this->counter->update([
            'active' => false,
            'break_message' => $this->breakInputMessage,
        ]);

        $this->status = 'break';
        $this->breakMessage = $this->breakInputMessage;

        $this->showBreakModal = false;

        $this->notification()->success('Break started.');
    }
    public function resumeWork()
    {
        $this->dialog()->confirm([
            'title'       => 'Resume Work',
            'description' => 'Are you sure you want to resume work? You will be available to select or call new tickets.',
            'acceptLabel' => 'Yes, Resume',
            'method'      => 'confirmResumeWork',
        ]);
    }

    public function confirmResumeWork()
{
    $this->counter->update([
        'active' => true,
        'break_message' => null,
    ]);

    $this->status = 'active';
    $this->breakMessage = null;
$this->notification()->success('Work resumed.');
}



    public function render()
    {
        return view('livewire.counter.counter-transaction-page');
    }
}
