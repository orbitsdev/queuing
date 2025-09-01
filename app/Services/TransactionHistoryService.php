<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryService
{
    /**
     * Log a queue transaction
     *
     * @param Queue $queue The queue being processed
     * @param string $action The action being performed (called, serving, served, etc.)
     * @param string|null $statusBefore Previous status
     * @param string|null $statusAfter New status
     * @param array|null $metadata Additional data to store
     * @return TransactionHistory
     */
    public static function logQueueTransaction(
        Queue $queue,
        string $action,
        ?string $statusBefore = null,
        ?string $statusAfter = null,
        ?array $metadata = []
    ): TransactionHistory {
        return TransactionHistory::create([
            'queue_id' => $queue->id,
            'user_id' => Auth::id(),
            'counter_id' => $queue->counter_id,
            'branch_id' => $queue->branch_id,
            'service_id' => $queue->service_id,
            'ticket_number' => $queue->ticket_number,
            'raw_number' => $queue->number,
            'action' => $action,
            'status_before' => $statusBefore ?? $queue->getOriginal('status'),
            'status_after' => $statusAfter ?? $queue->status,
            'metadata' => $metadata,
            'transaction_time' => now(),
        ]);
    }

    /**
     * Log a counter status change
     *
     * @param \App\Models\Counter $counter
     * @param bool $isActive
     * @param string|null $breakMessage
     * @return TransactionHistory
     */
    public static function logCounterStatusChange(
        \App\Models\Counter $counter,
        bool $isActive,
        ?string $breakMessage = null
    ): TransactionHistory {
        return TransactionHistory::create([
            'user_id' => Auth::id(),
            'counter_id' => $counter->id,
            'branch_id' => $counter->branch_id,
            'action' => $isActive ? 'counter_active' : 'counter_break',
            'status_before' => $counter->getOriginal('active') ? 'active' : 'break',
            'status_after' => $isActive ? 'active' : 'break',
            'metadata' => [
                'break_message' => $breakMessage,
            ],
            'transaction_time' => now(),
        ]);
    }
}
