<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Counter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

class Counters extends Component
{
    use WithPagination;

    #[Title('Counter Management')]
    
    public $showModal = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedCounterId;
    public $selectedBranch = null;
    
    #[Rule('required|min:2|max:255')]
    public $name = '';
    
    #[Rule('required|exists:branches,id')]
    public $branch_id = '';
    
    #[Rule('boolean')]
    public $is_priority = false;
    
    #[Rule('boolean')]
    public $active = true;
    
    #[Rule('nullable|max:500')]
    public $break_message = '';

    public function mount()
    {
        // Set default branch if none selected
        if (!$this->selectedBranch) {
            $this->selectedBranch = Branch::first()?->id;
        }
    }

    public function updatedSelectedBranch($value)
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'is_priority', 'active', 'break_message', 'isEditing', 'selectedCounterId']);
        $this->branch_id = $this->selectedBranch;
        $this->showModal = true;
    }

    public function edit(Counter $counter)
    {
        $this->selectedCounterId = $counter->id;
        $this->name = $counter->name;
        $this->branch_id = $counter->branch_id;
        $this->is_priority = $counter->is_priority;
        $this->active = $counter->active;
        $this->break_message = $counter->break_message;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'branch_id' => $this->branch_id,
            'is_priority' => $this->is_priority,
            'active' => $this->active,
            'break_message' => $this->break_message
        ];

        if ($this->isEditing) {
            $counter = Counter::find($this->selectedCounterId);
            $counter->update($data);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'Counter updated successfully'
            );
        } else {
            Counter::create($data);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'Counter created successfully'
            );
        }

        $this->reset(['showModal', 'name', 'is_priority', 'active', 'break_message', 'isEditing', 'selectedCounterId']);
    }

    public function confirmDelete(Counter $counter)
    {
        $this->selectedCounterId = $counter->id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $counter = Counter::find($this->selectedCounterId);
        
        if ($counter->queues()->exists()) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Cannot delete counter with existing queues'
            );
            return;
        }

        $counter->delete();
        $this->notification()->success(
            $title = 'Success',
            $description = 'Counter deleted successfully'
        );
        $this->reset(['confirmingDeletion', 'selectedCounterId']);
    }

    public function toggleStatus(Counter $counter)
    {
        $counter->update(['active' => !$counter->active]);
        
        $status = $counter->active ? 'activated' : 'deactivated';
        $this->notification()->success(
            $title = 'Success',
            $description = "Counter {$status} successfully"
        );
    }

    public function updateBreakMessage(Counter $counter, $message)
    {
        $counter->update(['break_message' => $message ?: null]);
        
        $this->notification()->success(
            $title = 'Success',
            $description = 'Break message updated successfully'
        );
    }

    public function render()
    {
        return view('livewire.admin.counters', [
            'branches' => Branch::orderBy('name')->get(),
            'counters' => Counter::when($this->selectedBranch, function($query) {
                    $query->where('branch_id', $this->selectedBranch);
                })
                ->withCount('queues')
                ->with('branch')
                ->latest()
                ->paginate(10)
        ]);
    }
}
