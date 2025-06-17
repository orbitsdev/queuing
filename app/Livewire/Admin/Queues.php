<?php

namespace App\Livewire\Admin;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

class Queues extends Component
{
    use WithPagination;

    #[Title('Queue Management')]
    
    public $selectedBranch = null;
    public $selectedService = null;
    public $selectedStatus = null;
    public $selectedDate = null;
    public $showModal = false;
    public $showReassignModal = false;
    public $selectedQueueId;

    #[Rule('required|exists:counters,id')]
    public $counter_id = '';

    #[Rule('nullable|string|max:255')]
    public $hold_reason = '';

    public $availableStatuses = [
        'waiting' => 'Waiting',
        'called' => 'Called',
        'serving' => 'Serving',
        'held' => 'On Hold',
        'completed' => 'Completed',
        'skipped' => 'Skipped',
        'expired' => 'Expired',
        'cancelled' => 'Cancelled'
    ];

    public function mount()
    {
        $this->selectedDate = now()->toDateString();
        if (!$this->selectedBranch) {
            $this->selectedBranch = Branch::first()?->id;
        }
    }

    public function updatedSelectedBranch()
    {
        $this->selectedService = null;
        $this->resetPage();
    }

    public function updatedSelectedService()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function showReassign(Queue $queue)
    {
        if (!in_array($queue->status, ['waiting', 'called', 'held'])) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Can only reassign queues that are waiting, called, or on hold'
            );
            return;
        }

        $this->selectedQueueId = $queue->id;
        $this->counter_id = $queue->counter_id;
        $this->showReassignModal = true;
    }

    public function reassign()
    {
        $this->validate();

        $queue = Queue::find($this->selectedQueueId);
        $oldCounter = $queue->counter_id;
        
        $queue->update([
            'counter_id' => $this->counter_id,
            'status' => 'called',
            'called_at' => now()
        ]);

        $this->notification()->success(
            $title = 'Success',
            $description = 'Queue reassigned successfully'
        );

        $this->reset(['showReassignModal', 'counter_id', 'selectedQueueId']);
    }

    public function updateStatus(Queue $queue, $newStatus)
    {
        // Validate status transitions
        $allowedTransitions = [
            'waiting' => ['called', 'cancelled'],
            'called' => ['serving', 'held', 'skipped'],
            'serving' => ['completed', 'held'],
            'held' => ['called'],
            'completed' => [],
            'skipped' => ['called'],
            'expired' => [],
            'cancelled' => []
        ];

        if (!in_array($newStatus, $allowedTransitions[$queue->status] ?? [])) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Invalid status transition'
            );
            return;
        }

        $data = ['status' => $newStatus];

        // Add timestamps based on status
        switch ($newStatus) {
            case 'called':
                $data['called_at'] = now();
                break;
            case 'serving':
                $data['served_at'] = now();
                break;
            case 'held':
                $data['hold_started_at'] = now();
                break;
            case 'skipped':
                $data['skipped_at'] = now();
                break;
        }

        $queue->update($data);

        $this->notification()->success(
            $title = 'Success',
            $description = 'Queue status updated successfully'
        );
    }

    public function setHoldReason(Queue $queue)
    {
        $this->selectedQueueId = $queue->id;
        $this->hold_reason = $queue->hold_reason;
        $this->showModal = true;
    }

    public function saveHoldReason()
    {
        $this->validate([
            'hold_reason' => 'nullable|string|max:255'
        ]);

        $queue = Queue::find($this->selectedQueueId);
        $queue->update(['hold_reason' => $this->hold_reason]);

        $this->notification()->success(
            $title = 'Success',
            $description = 'Hold reason updated successfully'
        );

        $this->reset(['showModal', 'hold_reason', 'selectedQueueId']);
    }

    public function getAvailableCounters()
    {
        return Counter::where('branch_id', $this->selectedBranch)
            ->where('active', true)
            ->get();
    }

    public function getStatusBadgeColor($status)
    {
        return match($status) {
            'waiting' => 'info',
            'called' => 'warning',
            'serving' => 'primary',
            'held' => 'warning',
            'completed' => 'positive',
            'skipped' => 'negative',
            'expired' => 'negative',
            'cancelled' => 'negative',
            default => 'secondary'
        };
    }

    public function render()
    {
        return view('livewire.admin.queues', [
            'branches' => Branch::orderBy('name')->get(),
            'services' => $this->selectedBranch ? Service::where('branch_id', $this->selectedBranch)->get() : collect(),
            'counters' => $this->getAvailableCounters(),
            'queues' => Queue::with(['branch', 'service', 'counter', 'user'])
                ->when($this->selectedBranch, fn($query) => 
                    $query->where('branch_id', $this->selectedBranch)
                )
                ->when($this->selectedService, fn($query) => 
                    $query->where('service_id', $this->selectedService)
                )
                ->when($this->selectedStatus, fn($query) => 
                    $query->where('status', $this->selectedStatus)
                )
                ->when($this->selectedDate, fn($query) => 
                    $query->whereDate('created_at', $this->selectedDate)
                )
                ->latest()
                ->paginate(15)
        ]);
    }
}
