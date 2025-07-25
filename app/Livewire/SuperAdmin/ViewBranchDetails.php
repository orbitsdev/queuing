<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;

class ViewBranchDetails extends Component
{
    public $branch;
    public function mount($branch)
    {
        $this->branch = $branch;
    }
    public function render()
    {
        return view('livewire.super-admin.view-branch-details');
    }
}
