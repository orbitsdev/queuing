<?php

namespace App\Livewire\Display;

use App\Models\Branch;
use App\Models\Monitor;
use Livewire\Component;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;

class MonitorSelection extends Component
{
    use WireUiActions;
    
    public $branchCode = '';
    public $branch = null;
    public $monitors = null;
    public $showMonitors = false;
    
    #[Title('Monitor Selection')]
    
    public function findBranch()
    {
        $this->validate([
            'branchCode' => 'required|string|min:2',
        ]);
        
        $branch = Branch::where('code', $this->branchCode)->first();
        
        if (!$branch) {
            $this->notification()->error(
                'Branch Not Found',
                'No branch found with the provided code. Please check and try again.'
            );
            $this->showMonitors = false;
            return;
        }
        
        $this->branch = $branch;
        $this->monitors = Monitor::where('branch_id', $branch->id)->with('services')->get();
        $this->showMonitors = true;
        
        if ($this->monitors->isEmpty()) {
            $this->notification()->warning(
                'No Monitors Found',
                'This branch has no configured monitors.'
            );
        } else {
            $this->notification()->success(
                'Branch Found',
                'Showing monitors for ' . $branch->name
            );
        }
    }
    
    public function render()
    {
        return view('livewire.display.monitor-selection');
    }
}
