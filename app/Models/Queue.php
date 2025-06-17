<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Service;
use App\Models\Counter;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
   
    
    

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
