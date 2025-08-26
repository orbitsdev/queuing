# Database Optimization Documentation

## Overview

This document outlines the database optimization features implemented in the KiosQueuing system to improve performance, especially when handling large numbers of queue records.

## 1. Database Indexing

The following indexes have been added to the `queues` table to optimize query performance:

```php
$table->index('number');
$table->index('ticket_number');
$table->index('status');
$table->index('service_id');
$table->index('counter_id');
$table->index('branch_id');
$table->index('created_at');
$table->index(['branch_id', 'status']);
$table->index(['branch_id', 'created_at']);
$table->index(['branch_id', 'service_id', 'status']);
```

### Benefits

- Faster filtering by branch, service, and status
- Improved performance for date-based queries
- Optimized sorting operations
- Enhanced performance for compound conditions

## 2. Batch Processing Methods

The Queue model now includes methods for batch processing multiple queues efficiently:

### `batchUpdateStatus()`

Updates multiple queues to a new status in a single database transaction.

```php
Queue::batchUpdateStatus(
    [1, 2, 3],           // Queue IDs
    'served',            // New status
    'served_at',         // Timestamp field to update
    ['additional' => 'data'], // Additional data
    true                 // Whether to broadcast events
);
```

### `batchComplete()` and `batchSkip()`

Convenience methods for common batch operations.

```php
// Complete multiple queues
Queue::batchComplete([1, 2, 3]);

// Skip multiple queues
Queue::batchSkip([1, 2, 3]);
```

### `bulkProcessByStatus()`

Process queues matching specific criteria.

```php
Queue::bulkProcessByStatus(
    'waiting',           // From status
    'expired',           // To status
    null,                // Timestamp field
    1,                   // Branch ID
    2,                   // Service ID
    Carbon::now()->subHours(24), // Older than
    true                 // Broadcast events
);
```

## 3. Settings Caching

The Setting model now implements caching to reduce database queries:

```php
// Get cached global settings (30-minute TTL)
$settings = Setting::global();

// Get cached branch settings with fallback to global
$branchSettings = Setting::forBranch($branch);

// Force bypass cache if needed
$freshSettings = Setting::global(false);
$freshBranchSettings = Setting::forBranch($branch, false);

// Cache is automatically cleared when settings are updated
```

## 4. Queue Statistics

New methods to efficiently calculate queue statistics:

```php
// Get comprehensive statistics for a branch
$stats = Queue::getBranchStats($branchId);

// Statistics include:
// - Counts by status (waiting, serving, completed, etc.)
// - Average wait time
// - Average service time
```

## 5. Automatic Queue Expiration

A scheduled command to automatically expire old waiting queues:

```bash
# Run manually
php artisan app:expire-old-queues --hours=24

# Options
--hours=24    # Number of hours after which to expire queues
--branch=1    # Limit to a specific branch
--quiet       # Don't output messages
```

The command is scheduled to run hourly in the Console Kernel.

## Implementation Notes

- All batch operations use database transactions for data integrity
- Events are broadcast for real-time updates when specified
- Caching includes automatic cache invalidation when data changes
- All methods are designed to be efficient with large datasets
