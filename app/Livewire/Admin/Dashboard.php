<?php

namespace App\Livewire\Admin;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use App\Models\User;

use Livewire\Attributes\Title;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Actions\Contracts\HasActions;

use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Dashboard extends Component  implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    #[Title('Admin Dashboard')]
    public $totalServices;
    public $totalBranches;
    public $totalMonitors;
    public $totalUsers;


    public function testAction(): Action
    {
        return Action::make('test')
            ->label('Test')
            ->requiresConfirmation()
            ->action(function () {
                dd('test');
              
            });
    }

    public function mount()
    {
        $this->loadDashboardStats();
    }

    public function loadDashboardStats()
    {
        $this->totalServices = Service::count();
        $this->totalBranches = Branch::count();
        $this->totalMonitors = Counter::count();
        $this->totalUsers = User::count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
