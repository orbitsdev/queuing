<?php

namespace App\Observers;

use App\Models\Branch;
use App\Models\Setting;

class BranchObserver
{
    /**
     * Handle the Branch "created" event.
     * 
     * Clone global settings to the newly created branch
     */
    public function created(Branch $branch): void
    {
        // Get all global settings (where branch_id is null)
        $globalSettings = Setting::whereNull('branch_id')->get();
        
        // Clone each global setting to the new branch
        foreach ($globalSettings as $globalSetting) {
            Setting::create([
                'branch_id' => $branch->id,
                'key' => $globalSetting->key,
                'value' => $globalSetting->value,
            ]);
        }
    }
}
