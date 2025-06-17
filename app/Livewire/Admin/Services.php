<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

class Services extends Component
{
    use WithPagination;

    #[Title('Service Management')]
    
    public $showModal = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedServiceId;
    public $selectedBranch = null;
    
    #[Rule('required|min:3|max:255')]
    public $name = '';
    
    #[Rule('required|min:1|max:10')]
    public $code = '';
    
    #[Rule('nullable|max:500')]
    public $description = '';
    
    #[Rule('required|exists:branches,id')]
    public $branch_id = '';

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
        $this->reset(['name', 'code', 'description', 'isEditing', 'selectedServiceId']);
        $this->branch_id = $this->selectedBranch;
        $this->showModal = true;
    }

    public function edit(Service $service)
    {
        $this->selectedServiceId = $service->id;
        $this->name = $service->name;
        $this->code = $service->code;
        $this->description = $service->description;
        $this->branch_id = $service->branch_id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $service = Service::find($this->selectedServiceId);
            $this->validate([
                'code' => 'required|min:1|max:10|unique:services,code,' . $service->id . ',id,branch_id,' . $this->branch_id
            ]);
            
            $service->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'branch_id' => $this->branch_id
            ]);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'Service updated successfully'
            );
        } else {
            $this->validate([
                'code' => 'required|min:1|max:10|unique:services,code,NULL,id,branch_id,' . $this->branch_id
            ]);
            
            Service::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'branch_id' => $this->branch_id,
                'last_ticket_number' => 0
            ]);
            
            $this->notification()->success(
                $title = 'Success',
                $description = 'Service created successfully'
            );
        }

        $this->reset(['showModal', 'name', 'code', 'description', 'isEditing', 'selectedServiceId']);
    }

    public function confirmDelete(Service $service)
    {
        $this->selectedServiceId = $service->id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $service = Service::find($this->selectedServiceId);
        
        if ($service->queues()->exists()) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Cannot delete service with existing queues'
            );
            return;
        }

        $service->delete();
        $this->notification()->success(
            $title = 'Success',
            $description = 'Service deleted successfully'
        );
        $this->reset(['confirmingDeletion', 'selectedServiceId']);
    }

    public function resetTicketNumber(Service $service)
    {
        $service->update(['last_ticket_number' => 0]);
        $this->notification()->success(
            $title = 'Success',
            $description = 'Ticket number reset successfully'
        );
    }

    public function render()
    {
        return view('livewire.admin.services', [
            'branches' => Branch::orderBy('name')->get(),
            'services' => Service::when($this->selectedBranch, function($query) {
                    $query->where('branch_id', $this->selectedBranch);
                })
                ->withCount('queues')
                ->with('branch')
                ->latest()
                ->paginate(10)
        ]);
    }
}
