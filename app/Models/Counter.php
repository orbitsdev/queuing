<?php

namespace App\Models;

use App\Models\User;
use App\Models\Queue;
use App\Models\Branch;
use App\Models\Service;
use App\Models\CounterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function getQueueCountAttribute()
    {
        return $this->queues()->count();
    }

   public function user()
{
    return $this->belongsTo(User::class);
}



    public function services()
{
    return $this->belongsToMany(Service::class, 'counter_service', 'counter_id', 'service_id');
}

public function counterServices()
{
    return $this->hasMany(CounterService::class);
}

public function scopeCurrentBranch($query)
{
    return $query->where('branch_id', Auth::user()->branch_id);
}



}
