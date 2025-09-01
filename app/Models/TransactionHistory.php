<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $fillable = [
        'queue_id',
        'user_id',
        'counter_id',
        'branch_id',
        'service_id',
        'ticket_number',
        'raw_number',
        'action',
        'status_before',
        'status_after',
        'metadata',
        'transaction_time'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'transaction_time' => 'datetime'
    ];
    
    /**
     * Get the queue associated with this transaction (if it still exists)
     */
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
    
    /**
     * Get the user who performed this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the counter associated with this transaction
     */
    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
    
    /**
     * Get the branch associated with this transaction
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    /**
     * Get the service associated with this transaction
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    /**
     * Scope a query to only include transactions from today
     */
    public function scopeTodayTransactions($query)
    {
        return $query->whereDate('transaction_time', today());
    }
    
    /**
     * Scope a query to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_time', [$startDate, $endDate]);
    }
}
