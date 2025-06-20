<?php

namespace App\Models;

use App\Models\Monitor;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MonitorService extends Model
{
    protected $fillable = [
        'monitor_id',
        'service_id',
        'sort_order',
    ];
    
    protected $table = 'monitor_service';

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
