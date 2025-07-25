<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use Livewire\Component;

class Dashboard extends Component
{

    // public $totalServices;
    public $totalBranches;
    // public $totalMonitors;
    public $totalUsers;

      public function mount()
    {
        $this->loadDashboardStats();
    }

    public function loadDashboardStats()
    {
        // $this->totalServices = Service::count();
        $this->totalBranches = Branch::count();
        // $this->totalMonitors = Counter::count();
        $this->totalUsers = User::notSuperAdmin()->count();
    }
    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}
