<?php

namespace App\Models;

use App\Models\User;
use App\Models\Queue;
use App\Models\Branch;
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


    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', Auth::user()->branch_id);
    }

}
