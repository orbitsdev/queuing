<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
