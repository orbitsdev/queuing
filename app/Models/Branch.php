<?php

namespace App\Models;

use App\Models\User;
use App\Models\Queue;
use App\Models\Counter;
use App\Models\Monitor;
use App\Models\Service;
use App\Models\Setting;
use App\Observers\BranchObserver;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ObservedBy([BranchObserver::class])]
class Branch extends Model
{


    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function counters()
    {
        return $this->hasMany(Counter::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }
    public function monitors()
    {
        return $this->hasMany(Monitor::class);
    }


    public function adminCount(): Attribute
    {
        return Attribute::get(fn() => $this->users()->where('role', 'admin')->count());
    }
    // scpope by branch
   
}
