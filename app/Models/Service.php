<?php

namespace App\Models;

use App\Models\Queue;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\CounterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
    public function counters()
{
    return $this->belongsToMany(Counter::class, 'counter_service', 'service_id', 'counter_id');
}

public function counterServices()
{
    return $this->hasMany(CounterService::class);
}

//scope branch
public function scopeCurrentBranch($query)
{
    return $query->where('branch_id', Auth::user()->branch_id);
}

}
