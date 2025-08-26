<?php

namespace App\Models;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

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
     * 
     * @param bool $useCache Whether to use cache
     * @return \App\Models\Setting
     */
    public static function global(bool $useCache = true)
    {
        if (!$useCache) {
            return static::whereNull('branch_id')->first() ?? new static();
        }
        
        return Cache::remember('settings.global', now()->addMinutes(30), function () {
            return static::whereNull('branch_id')->first() ?? new static();
        });
    }

    /**
     * Get settings for a specific branch with fallback to global
     * 
     * @param \App\Models\Branch $branch
     * @param bool $useCache Whether to use cache
     * @return \App\Models\Setting
     */
    public static function forBranch(Branch $branch, bool $useCache = true)
    {
        if (!$useCache) {
            return static::where('branch_id', $branch->id)->first() ?? static::global($useCache);
        }
        
        $branchSettings = Cache::remember("settings.branch.{$branch->id}", now()->addMinutes(30), function () use ($branch) {
            return static::where('branch_id', $branch->id)->first();
        });
        
        return $branchSettings ?? static::global($useCache);
    }
    
    /**
     * Clear the settings cache for a specific branch
     * 
     * @param int|null $branchId Branch ID or null for global settings
     * @return void
     */
    public static function clearCache(?int $branchId = null): void
    {
        if ($branchId === null) {
            Cache::forget('settings.global');
        } else {
            Cache::forget("settings.branch.{$branchId}");
        }
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            self::clearCache($setting->branch_id);
        });
        
        static::deleted(function ($setting) {
            self::clearCache($setting->branch_id);
        });
    }
}
