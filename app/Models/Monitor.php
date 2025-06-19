<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Service;
use App\Models\MonitorService;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)
                    ->withPivot('sort_order')
                    ->withTimestamps();
    }
    public function monitorService()
{
    return $this->hasMany(MonitorService::class);
}
}
