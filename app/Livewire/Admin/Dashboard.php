<?php

namespace App\Livewire\Admin;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;

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
    public $totalQueues;
    public $activeCounters;
    public $totalServices;
    public $branches;
    public $queuesByStatus;


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
        $this->totalQueues = Queue::whereDate('created_at', today())->count();
        $this->activeCounters = Counter::where('active', true)->count();
        $this->totalServices = Service::count();
        $this->branches = Branch::withCount(['queues' => function($query) {
            $query->whereDate('created_at', today());
        }])->get();
        
        $this->queuesByStatus = Queue::whereDate('created_at', today())
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
