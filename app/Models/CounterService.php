<?php

namespace App\Models;

use App\Models\Counter;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class CounterService extends Model
{

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

