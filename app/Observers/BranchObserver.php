<?php

namespace App\Observers;

use App\Models\Branch;
use App\Models\Setting;

class BranchObserver
{

    public function creating(Branch $branch): void
    {
        // Auto-generate code if none provided
        if (empty($branch->code)) {
            $nextId = (Branch::max('id') ?? 0) + 1;
            $branch->code = 'BR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }
    }
    
    public function created(Branch $branch): void
    {
        // Get global settings
        $globalSettings = Setting::global();

        // Create settings for the new branch, copying values from global settings
        Setting::updateOrCreate(
            ['branch_id' => $branch->id],
            [
                'ticket_prefix' => $globalSettings->ticket_prefix ?? 'QUE',
                'print_logo' => $globalSettings->print_logo ?? true,
                'queue_reset_daily' => $globalSettings->queue_reset_daily ?? true,
                'queue_reset_time' => $globalSettings->queue_reset_time ?? '00:00',
                'default_break_message' => $globalSettings->default_break_message ?? 'On break, please proceed to another counter.',
                'queue_number_base' => $globalSettings->queue_number_base ?? 1,
            ]
        );
    }
}
