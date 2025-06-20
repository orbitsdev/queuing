<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'print_logo' => 'boolean',
        'queue_reset_daily' => 'boolean',
        'queue_reset_time' => 'string',
        'queue_number_base' => 'integer',
    ];

    /**
     * Get the branch that owns the settings.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get global settings (where branch_id is null)
     */
    public static function global()
    {
        return static::whereNull('branch_id')->first() ?? new static();
    }

    /**
     * Get settings for a specific branch with fallback to global
     */
    public static function forBranch(Branch $branch)
    {
        return static::where('branch_id', $branch->id)->first()
            ?? static::global();
    }
}
