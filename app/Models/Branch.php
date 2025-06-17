<?php

namespace App\Models;

use App\Models\User;
use App\Models\Queue;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

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
    
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
