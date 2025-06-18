<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Branches extends Component  implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Branch::query())
            ->columns([
                TextColumn::make('name')->searchable(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    


    #[Title('Branch Management')]
    
    public $showModal = false;
    public $isEditing = false;
    public $confirmingDeletion = false;
    public $selectedBranchId;
    
    #[Rule('required|min:3|max:255')]
    public $name = '';
    
    #[Rule('required|min:2|max:50|unique:branches,code')]
    public $code = '';
    
    #[Rule('nullable|max:500')]
    public $address = '';

    public function create()
    {
        $this->reset(['name', 'code', 'address', 'isEditing', 'selectedBranchId']);
        $this->showModal = true;
    }

    public function edit(Branch $branch)
    {
        $this->selectedBranchId = $branch->id;
        $this->name = $branch->name;
        $this->code = $branch->code;
        $this->address = $branch->address;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $branch = Branch::find($this->selectedBranchId);
            $this->validate([
                'code' => 'required|min:2|max:50|unique:branches,code,' . $branch->id
            ]);
            $branch->update([
                'name' => $this->name,
                'code' => $this->code,
                'address' => $this->address
            ]);
            $this->notification()->success(
                $title = 'Success',
                $description = 'Branch updated successfully'
            );
        } else {
            Branch::create([
                'name' => $this->name,
                'code' => $this->code,
                'address' => $this->address
            ]);
            $this->notification()->success(
                $title = 'Success',
                $description = 'Branch created successfully'
            );
        }

        $this->reset(['showModal', 'name', 'code', 'address', 'isEditing', 'selectedBranchId']);
    }

    public function confirmDelete(Branch $branch)
    {
        $this->selectedBranchId = $branch->id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $branch = Branch::find($this->selectedBranchId);
        
        if ($branch->queues()->exists() || $branch->services()->exists() || $branch->counters()->exists()) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'Cannot delete branch with associated records'
            );
            return;
        }

        $branch->delete();
        $this->notification()->success(
            $title = 'Success',
            $description = 'Branch deleted successfully'
        );
        $this->reset(['confirmingDeletion', 'selectedBranchId']);
    }

    public function render()
    {
        return view('livewire.admin.branches', [
            'branches' => Branch::withCount(['queues', 'services', 'counters'])
                ->latest()
                ->paginate(10)
        ]);
    }
}
