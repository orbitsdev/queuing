<?php

namespace App\Models;

use App\Models\User;
use App\Models\Branch;
use App\Models\Counter;
use App\Models\Service;
use App\Events\QueueStatusChanged;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'branch_id',
        'service_id',
        'counter_id',
        'user_id',
        'ticket_number',
        'status',
        'called_at',
        'serving_at',
        'served_at',
        'skipped_at',
        'hold_started_at',
        'cancelled_at',
        'hold_reason',
    ];


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

    public function scopeTodayQueues($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeCurrentBranch($query)
    {
        return $query->where('branch_id', Auth::user()->branch_id);
    }
    
    /**
     * Batch update multiple queues to a new status
     * 
     * @param array $queueIds Array of queue IDs to update
     * @param string $status New status to set
     * @param string|null $timestampField Timestamp field to update with current time
     * @param array $additionalData Additional data to update
     * @param bool $broadcast Whether to broadcast events for each queue
     * @return int Number of updated records
     */
    public static function batchUpdateStatus(array $queueIds, string $status, ?string $timestampField = null, array $additionalData = [], bool $broadcast = true): int
    {
        if (empty($queueIds)) {
            return 0;
        }
        
        return DB::transaction(function () use ($queueIds, $status, $timestampField, $additionalData, $broadcast) {
            $updateData = ['status' => $status];
            
            if ($timestampField) {
                $updateData[$timestampField] = now();
            }
            
            if (!empty($additionalData)) {
                $updateData = array_merge($updateData, $additionalData);
            }
            
            $count = self::whereIn('id', $queueIds)->update($updateData);
            
            // Broadcast events if requested
            if ($broadcast) {
                $queues = self::whereIn('id', $queueIds)->get();
                foreach ($queues as $queue) {
                    event(new QueueStatusChanged($queue));
                }
            }
            
            return $count;
        });
    }
    
    /**
     * Batch complete multiple queues
     * 
     * @param array $queueIds Array of queue IDs to complete
     * @param bool $broadcast Whether to broadcast events
     * @return int Number of updated records
     */
    public static function batchComplete(array $queueIds, bool $broadcast = true): int
    {
        return self::batchUpdateStatus($queueIds, 'served', 'served_at', [], $broadcast);
    }
    
    /**
     * Batch skip multiple queues
     * 
     * @param array $queueIds Array of queue IDs to skip
     * @param bool $broadcast Whether to broadcast events
     * @return int Number of updated records
     */
    public static function batchSkip(array $queueIds, bool $broadcast = true): int
    {
        return self::batchUpdateStatus($queueIds, 'skipped', 'skipped_at', [], $broadcast);
    }
    
    /**
     * Bulk process queues by status and date range
     * 
     * @param string $fromStatus Current status to match
     * @param string $toStatus New status to set
     * @param string|null $timestampField Timestamp field to update
     * @param int|null $branchId Optional branch ID filter
     * @param int|null $serviceId Optional service ID filter
     * @param \Carbon\Carbon|null $olderThan Optional date to filter queues older than
     * @param bool $broadcast Whether to broadcast events
     * @return int Number of updated records
     */
    public static function bulkProcessByStatus(
        string $fromStatus, 
        string $toStatus, 
        ?string $timestampField = null,
        ?int $branchId = null,
        ?int $serviceId = null,
        ?\Carbon\Carbon $olderThan = null,
        bool $broadcast = false
    ): int {
        return DB::transaction(function () use ($fromStatus, $toStatus, $timestampField, $branchId, $serviceId, $olderThan, $broadcast) {
            $query = self::where('status', $fromStatus);
            
            if ($branchId !== null) {
                $query->where('branch_id', $branchId);
            }
            
            if ($serviceId !== null) {
                $query->where('service_id', $serviceId);
            }
            
            if ($olderThan !== null) {
                $query->where('created_at', '<', $olderThan);
            }
            
            // Get IDs for broadcasting if needed
            $queueIds = $broadcast ? $query->pluck('id')->toArray() : [];
            
            $updateData = ['status' => $toStatus];
            if ($timestampField) {
                $updateData[$timestampField] = now();
            }
            
            $count = $query->update($updateData);
            
            // Broadcast events if requested
            if ($broadcast && !empty($queueIds)) {
                $queues = self::whereIn('id', $queueIds)->get();
                foreach ($queues as $queue) {
                    event(new QueueStatusChanged($queue));
                }
            }
            
            return $count;
        });
    }
    
    /**
     * Expire waiting queues that are older than the specified time
     * 
     * @param \Carbon\Carbon $olderThan Expire queues older than this time
     * @param int|null $branchId Optional branch ID filter
     * @param bool $broadcast Whether to broadcast events
     * @return int Number of expired queues
     */
    public static function expireOldQueues(\Carbon\Carbon $olderThan, ?int $branchId = null, bool $broadcast = true): int
    {
        return self::bulkProcessByStatus('waiting', 'expired', null, $branchId, null, $olderThan, $broadcast);
    }
    
    /**
     * Get queue statistics for a branch
     * 
     * @param int $branchId Branch ID
     * @param \Carbon\Carbon|null $date Optional date filter, defaults to today
     * @return array Array of statistics
     */
    public static function getBranchStats(int $branchId, ?\Carbon\Carbon $date = null): array
    {
        $date = $date ?? now();
        $query = self::where('branch_id', $branchId)
            ->whereDate('created_at', $date->toDateString());
            
        $stats = [
            'total' => $query->count(),
            'waiting' => (clone $query)->where('status', 'waiting')->count(),
            'serving' => (clone $query)->where('status', 'serving')->count(),
            'completed' => (clone $query)->where('status', 'served')->count(),
            'skipped' => (clone $query)->where('status', 'skipped')->count(),
            'held' => (clone $query)->where('status', 'held')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'expired' => (clone $query)->where('status', 'expired')->count(),
            'average_wait_time' => self::calculateAverageWaitTime($branchId, $date),
            'average_service_time' => self::calculateAverageServiceTime($branchId, $date),
        ];
        
        return $stats;
    }
    
    /**
     * Calculate average wait time for a branch on a specific date
     * 
     * @param int $branchId Branch ID
     * @param \Carbon\Carbon $date Date to calculate for
     * @return float|null Average wait time in minutes or null if no data
     */
    protected static function calculateAverageWaitTime(int $branchId, \Carbon\Carbon $date): ?float
    {
        $avgWaitTime = self::where('branch_id', $branchId)
            ->whereDate('created_at', $date->toDateString())
            ->whereNotNull('called_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, called_at)) as avg_wait_time'))
            ->first()
            ->avg_wait_time;
            
        return $avgWaitTime ? round($avgWaitTime / 60, 2) : null;
    }
    
    /**
     * Calculate average service time for a branch on a specific date
     * 
     * @param int $branchId Branch ID
     * @param \Carbon\Carbon $date Date to calculate for
     * @return float|null Average service time in minutes or null if no data
     */
    protected static function calculateAverageServiceTime(int $branchId, \Carbon\Carbon $date): ?float
    {
        $avgServiceTime = self::where('branch_id', $branchId)
            ->whereDate('created_at', $date->toDateString())
            ->whereNotNull('serving_at')
            ->whereNotNull('served_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, serving_at, served_at)) as avg_service_time'))
            ->first()
            ->avg_service_time;
            
        return $avgServiceTime ? round($avgServiceTime / 60, 2) : null;
    }
}
