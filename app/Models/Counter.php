<?php

namespace App\Models;

use App\Models\Queue;
use App\Models\Branch;
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

}
