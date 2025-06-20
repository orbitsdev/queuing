<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use App\Models\Setting;
use App\Models\Queue;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;

class BranchQueueSettings extends Component
{
    use WireUiActions;

    public Branch $branch;
    public $base;
    public $todayCount;
    public $nextNumber;
    public $lastQueueNumber = null;

    // Add property updated hook for base
    public function updatedBase($value)
    {
        // Validate the input
        if (!is_numeric($value) || $value < 0) {
            return;
        }
        
        // Recalculate next number whenever base changes
        $this->nextNumber = (int)$value + $this->todayCount;
    }

    public function mount(Branch $branch)
    {
        $this->branch = $branch;

        // Get branch setting or create if not exists
        $setting = Setting::where('branch_id', $branch->id)->first();
        
        // Load current base or default
        if ($setting && isset($setting->queue_number_base)) {
            $this->base = $setting->queue_number_base;
        }

        // Count today's issued tickets for this branch
        $this->todayCount = Queue::where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->count();
            
        // Get the last queue number issued today
        $lastQueue = Queue::where('branch_id', $branch->id)
            ->whereDate('created_at', today())
            ->latest('created_at')
            ->first();
            
        $this->lastQueueNumber = $lastQueue ? $lastQueue->ticket_number : null;
            
        // Calculate next number
        $this->nextNumber = $this->base + $this->todayCount;
    }

    public function confirmSave()
    {
        // Validate input
        $this->validate([
            'base' => 'required|integer|min:0',
        ]);
        
        // Get or create setting
        $setting = Setting::updateOrCreate(
            ['branch_id' => $this->branch->id],
            ['queue_number_base' => $this->base]
        );

        // Recalculate next number
        $this->nextNumber = $this->base + $this->todayCount;

        $this->dialog()->success(
            title: 'Queue Base Updated',
            description: 'The base number has been saved. New tickets will start from ' . $this->nextNumber . '.'
        );
    }
    
    public function save()
    {
        $this->dialog()->confirm([
            'title'       => 'Confirm Save',
            'description' => "Are you sure you want to set the queue base number to {$this->base}? Next queue will be {$this->nextNumber}.",
            'acceptLabel' => 'Yes, Save',
            'method'      => 'confirmSave',
        ]);
    }

    public function resetTodayQueues()
    {
        // Count queues that will be affected
        $queueCount = Queue::where('branch_id', $this->branch->id)
            ->whereDate('created_at', today())
            ->count();
            
        // Delete all queues for today for this branch
        Queue::where('branch_id', $this->branch->id)
            ->whereDate('created_at', today())
            ->delete();
            
        // Reset today's count
        $this->todayCount = 0;
        
        // Recalculate next number
        $this->nextNumber = $this->base + $this->todayCount;
        
        $this->dialog()->success(
            title: 'Queues Reset',
            description: $queueCount . ' queues have been deleted for today.'
        );
    }
    
    public function resetBaseToOne()
    {
        // Set base to 1
        $this->base = 1;
        
        // Save the setting
        $setting = Setting::updateOrCreate(
            ['branch_id' => $this->branch->id],
            ['queue_number_base' => $this->base]
        );
        
        // Recalculate next number
        $this->nextNumber = $this->base + $this->todayCount;
        
        $this->dialog()->success(
            title: 'Queue Base Reset',
            description: 'The base number has been reset to 1. Next queue number will be ' . $this->nextNumber . '.'
        );
    }
    
    public function render()
    {
        return view('livewire.admin.branch-queue-settings');
    }
}
