<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Service;
use App\Models\MonitorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)
                    ->withPivot('sort_order')
                    ->withTimestamps();
    }
    public function monitorService()
{
    return $this->hasMany(MonitorService::class);
}

//scope current branch
  public function scopeCurrentBranch($query){
    $branchId = Auth::user()?->branch_id;
    if (!$branchId) {
        // Return empty result if no branch is set
        return $query->whereRaw('1 = 0');
    }
    return $query->where('branch_id', $branchId);
 }

 //scope branch pass data
 public function scopeBranchOf($query, $branch_id){
    return $query->where('branch_id', $branch_id);
 }

}
