<?php

namespace App\Models;

use App\Models\Monitor;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class MonitorService extends Model
{

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
