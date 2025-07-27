# KiosQueeing System - Database Documentation

## Overview

This document provides a comprehensive overview of the database structure for the KiosQueeing System, a Laravel 12 + Livewire 3 queuing application. It details all model relationships and database schema to ensure accurate implementation and prevent potential issues.

## Models and Relationships

### Branch Model

**Model**: `App\Models\Branch`

**Relationships**:
- `services()`: One-to-Many relationship with Service model
  ```php
  return $this->hasMany(Service::class);
  ```
- `counters()`: One-to-Many relationship with Counter model
  ```php
  return $this->hasMany(Counter::class);
  ```
- `queues()`: One-to-Many relationship with Queue model
  ```php
  return $this->hasMany(Queue::class);
  ```
- `users()`: One-to-Many relationship with User model
  ```php
  return $this->hasMany(User::class);
  ```
- `setting()`: One-to-One relationship with Setting model
  ```php
  return $this->hasOne(Setting::class);
  ```
- `monitors()`: One-to-Many relationship with Monitor model
  ```php
  return $this->hasMany(Monitor::class);
  ```

**Additional Features**:
- Uses `BranchObserver` for model events
- Has computed attribute `adminCount` that returns the count of admin users in the branch

### Counter Model

**Model**: `App\Models\Counter`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```
- `queues()`: One-to-Many relationship with Queue model
  ```php
  return $this->hasMany(Queue::class);
  ```
- `user()`: Belongs-To relationship with User model
  ```php
  return $this->belongsTo(User::class);
  ```
- `services()`: Many-to-Many relationship with Service model
  ```php
  return $this->belongsToMany(Service::class, 'counter_service', 'counter_id', 'service_id');
  ```
- `counterServices()`: One-to-Many relationship with CounterService model
  ```php
  return $this->hasMany(CounterService::class);
  ```

**Additional Features**:
- Has accessor `getQueueCountAttribute()` that returns the count of queues for the counter
- Has scope `scopeCurrentBranch($query)` to filter counters by the authenticated user's branch

### CounterService Model

**Model**: `App\Models\CounterService`

**Relationships**:
- `counter()`: Belongs-To relationship with Counter model
  ```php
  return $this->belongsTo(Counter::class);
  ```
- `service()`: Belongs-To relationship with Service model
  ```php
  return $this->belongsTo(Service::class);
  ```

### Monitor Model

**Model**: `App\Models\Monitor`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```
- `services()`: Many-to-Many relationship with Service model
  ```php
  return $this->belongsToMany(Service::class)
              ->withPivot('sort_order')
              ->withTimestamps();
  ```
- `monitorService()`: One-to-Many relationship with MonitorService model
  ```php
  return $this->hasMany(MonitorService::class);
  ```

**Additional Features**:
- Has scope `scopeCurrentBranch($query)` to filter monitors by the authenticated user's branch
- Has scope `scopeBranchOf($query, $branch_id)` to filter monitors by a specific branch ID

### MonitorService Model

**Model**: `App\Models\MonitorService`

**Relationships**:
- `monitor()`: Belongs-To relationship with Monitor model
  ```php
  return $this->belongsTo(Monitor::class);
  ```
- `service()`: Belongs-To relationship with Service model
  ```php
  return $this->belongsTo(Service::class);
  ```

**Additional Features**:
- Uses custom table name `monitor_service`
- Has fillable fields: `monitor_id`, `service_id`, `sort_order`

### Queue Model

**Model**: `App\Models\Queue`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```
- `service()`: Belongs-To relationship with Service model
  ```php
  return $this->belongsTo(Service::class);
  ```
- `counter()`: Belongs-To relationship with Counter model
  ```php
  return $this->belongsTo(Counter::class);
  ```
- `user()`: Belongs-To relationship with User model
  ```php
  return $this->belongsTo(User::class);
  ```

**Additional Features**:
- Has scope `scopeTodayQueues($query)` to filter queues created today
- Has scope `scopeCurrentBranch($query)` to filter queues by the authenticated user's branch
- Has fillable fields for tracking queue status and timestamps

### Service Model

**Model**: `App\Models\Service`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```
- `queues()`: One-to-Many relationship with Queue model
  ```php
  return $this->hasMany(Queue::class);
  ```
- `counters()`: Many-to-Many relationship with Counter model
  ```php
  return $this->belongsToMany(Counter::class, 'counter_service', 'service_id', 'counter_id');
  ```
- `counterServices()`: One-to-Many relationship with CounterService model
  ```php
  return $this->hasMany(CounterService::class);
  ```
- `monitors()`: Many-to-Many relationship with Monitor model
  ```php
  return $this->belongsToMany(Monitor::class)
              ->withPivot('sort_order')
              ->withTimestamps();
  ```
- `monitorService()`: One-to-Many relationship with MonitorService model
  ```php
  return $this->hasMany(MonitorService::class);
  ```

**Additional Features**:
- Has scope `scopeCurrentBranch($query)` to filter services by the authenticated user's branch

### Setting Model

**Model**: `App\Models\Setting`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```

**Additional Features**:
- Uses HasFactory trait
- Has static method `global()` to get global settings (where branch_id is null)
- Has static method `forBranch(Branch $branch)` to get settings for a specific branch with fallback to global
- Has type casting for boolean and integer fields

### User Model

**Model**: `App\Models\User`

**Relationships**:
- `branch()`: Belongs-To relationship with Branch model
  ```php
  return $this->belongsTo(Branch::class);
  ```
- `queues()`: One-to-Many relationship with Queue model
  ```php
  return $this->hasMany(Queue::class);
  ```
- `counter()`: Belongs-To relationship with Counter model
  ```php
  return $this->belongsTo(Counter::class);
  ```

**Additional Features**:
- Uses HasFactory and Notifiable traits
- Has method `initials()` to get user's initials from name
- Has scope `scopeNotSuperAdmin()` to filter out superadmin users
- Has scope `scopeCurrentBranch($query)` to filter users by the authenticated user's branch
- Has scope `scopeNotDefaultAdmin($query)` to filter out the default admin user

## Database Schema

### branches Table

```php
Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->text('address')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `name`: varchar(255), branch name
- `code`: varchar(255), unique branch code
- `address`: text, nullable, branch address
- `is_active`: boolean, default true, branch status
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date

### counters Table

```php
Schema::create('counters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('name');
    $table->boolean('is_priority')->default(false);
    $table->boolean('active')->default(true);
    $table->text('break_message')->nullable();
    $table->timestamps();
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `branch_id`: bigint, unsigned, foreign key to branches.id with cascade delete
- `user_id`: bigint, unsigned, nullable, staff assigned to counter
- `name`: varchar(255), counter name
- `is_priority`: boolean, default false, priority counter flag
- `active`: boolean, default true, counter status
- `break_message`: text, nullable, message displayed when counter is on break
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date

### users Table

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
    $table->enum('role', ['superadmin', 'admin', 'staff']);
    $table->string('password');
    $table->unsignedBigInteger('counter_id')->nullable();
    $table->unsignedBigInteger('queue_id')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `name`: varchar(255), user's full name
- `email`: varchar(255), unique, user's email address
- `email_verified_at`: timestamp, nullable, email verification timestamp
- `branch_id`: bigint, unsigned, nullable, foreign key to branches.id with cascade delete
- `role`: enum, one of 'superadmin', 'admin', 'staff'
- `password`: varchar(255), hashed password
- `counter_id`: bigint, unsigned, nullable, counter assigned to user
- `queue_id`: bigint, unsigned, nullable, queue being served by user
- `remember_token`: varchar(100), nullable, for "remember me" functionality
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date

### services Table

```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('code')->unique();
    $table->text('description')->nullable();
    $table->integer('last_ticket_number')->default(0);
    $table->timestamps();
    $table->index('branch_id');
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `branch_id`: bigint, unsigned, foreign key to branches.id with cascade delete
- `name`: varchar(255), service name
- `code`: varchar(255), unique, service code
- `description`: text, nullable, service description
- `last_ticket_number`: integer, default 0, tracks last issued ticket number
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date
- Index on `branch_id` for faster queries

### queues Table

```php
Schema::create('queues', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->foreignId('counter_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->unsignedInteger('number');
    $table->string('ticket_number');
    $table->enum('status', ['waiting', 'called', 'serving', 'held', 'served', 'skipped', 'cancelled', 'expired', 'completed'])->default('waiting');
    $table->string('hold_reason')->nullable();
    $table->timestamps();
    $table->timestamp('called_at')->nullable();
    $table->timestamp('serving_at')->nullable();
    $table->timestamp('served_at')->nullable();
    $table->timestamp('hold_started_at')->nullable();
    $table->timestamp('skipped_at')->nullable();
    $table->timestamp('cancelled_at')->nullable();
    $table->index('number');
    $table->index('ticket_number');
    $table->index('status');
    $table->index('service_id');
    $table->index('counter_id');
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `branch_id`: bigint, unsigned, foreign key to branches.id with cascade delete
- `service_id`: bigint, unsigned, foreign key to services.id with cascade delete
- `counter_id`: bigint, unsigned, nullable, foreign key to counters.id with null on delete
- `user_id`: bigint, unsigned, nullable, foreign key to users.id with null on delete
- `number`: unsigned integer, raw queue number
- `ticket_number`: varchar(255), formatted ticket number with prefix
- `status`: enum, one of 'waiting', 'called', 'serving', 'held', 'served', 'skipped', 'cancelled', 'expired', 'completed', default 'waiting'
- `hold_reason`: varchar(255), nullable, reason for holding a queue
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date
- `called_at`: timestamp, nullable, when queue was called
- `serving_at`: timestamp, nullable, when queue started being served
- `served_at`: timestamp, nullable, when queue was served
- `hold_started_at`: timestamp, nullable, when queue was put on hold
- `skipped_at`: timestamp, nullable, when queue was skipped
- `cancelled_at`: timestamp, nullable, when queue was cancelled
- Indexes on `number`, `ticket_number`, `status`, `service_id`, `counter_id` for faster queries

### settings Table

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
    // Ticket Settings
    $table->string('ticket_prefix')->default('QUE');
    $table->boolean('print_logo')->default(true);
    // Queue Settings
    $table->boolean('queue_reset_daily')->default(true);
    $table->time('queue_reset_time')->default('00:00');
    $table->unsignedInteger('queue_number_base')->default(1);
    $table->string('default_break_message')->default('On break, please proceed to another counter.');
    $table->timestamps();
    // Each branch can only have one settings record
    $table->unique('branch_id');
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `branch_id`: bigint, unsigned, nullable, foreign key to branches.id with cascade delete
- `ticket_prefix`: varchar(255), default 'QUE', prefix for ticket numbers
- `print_logo`: boolean, default true, whether to print logo on tickets
- `queue_reset_daily`: boolean, default true, whether to reset queue numbers daily
- `queue_reset_time`: time, default '00:00', time to reset queue numbers
- `queue_number_base`: unsigned integer, default 1, starting number for queue
- `default_break_message`: varchar(255), default message for counters on break
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date
- Unique constraint on `branch_id` to ensure one settings record per branch

### counter_service Table (Pivot)

```php
Schema::create('counter_service', function (Blueprint $table) {
    $table->id();
    $table->foreignId('counter_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['counter_id', 'service_id']); // prevent duplicates
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `counter_id`: bigint, unsigned, foreign key to counters.id with cascade delete
- `service_id`: bigint, unsigned, foreign key to services.id with cascade delete
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date
- Unique constraint on `counter_id` and `service_id` to prevent duplicate assignments

### monitors Table

```php
Schema::create('monitors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('location')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `branch_id`: bigint, unsigned, foreign key to branches.id with cascade delete
- `name`: varchar(255), monitor name
- `location`: varchar(255), nullable, physical location of monitor
- `description`: text, nullable, monitor description
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date

### monitor_service Table (Pivot)

```php
Schema::create('monitor_service', function (Blueprint $table) {
    $table->id();
    $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
    $table->foreignId('service_id')->constrained()->cascadeOnDelete();
    $table->integer('sort_order')->default(0);
    $table->timestamps();
    $table->unique(['monitor_id', 'service_id']);
});
```

**Fields**:
- `id`: bigint, unsigned, auto-increment, primary key
- `monitor_id`: bigint, unsigned, foreign key to monitors.id with cascade delete
- `service_id`: bigint, unsigned, foreign key to services.id with cascade delete
- `sort_order`: integer, default 0, order to display services on monitor
- `created_at`: timestamp, creation date
- `updated_at`: timestamp, last update date
- Unique constraint on `monitor_id` and `service_id` to prevent duplicate assignments

## Entity Relationship Diagram (ERD)

```
Branch 1 --- * Service
Branch 1 --- * Counter
Branch 1 --- * Queue
Branch 1 --- * User
Branch 1 --- 1 Setting
Branch 1 --- * Monitor

Service * --- * Counter (through counter_service)
Service 1 --- * Queue
Service * --- * Monitor (through monitor_service)

Counter 1 --- * Queue
Counter 1 --- 1 User

User 1 --- * Queue

Monitor * --- * Service (through monitor_service)
```

## Key Database Constraints

1. Each branch can have only one settings record (unique constraint on `branch_id` in settings table)
2. Each counter can only be assigned to one service once (unique constraint on `counter_id` and `service_id` in counter_service table)
3. Each monitor can only display one service once (unique constraint on `monitor_id` and `service_id` in monitor_service table)
4. Service codes must be unique across the system
5. Branch codes must be unique across the system
6. User emails must be unique across the system
7. When a branch is deleted, all related records (services, counters, queues, settings, monitors) are deleted (cascade delete)
8. When a counter or user is deleted, related queues are preserved but their references are set to null (null on delete)

## Special Notes

1. The Queue model has comprehensive status tracking with timestamps for each status change
2. The Setting model has a fallback mechanism to use global settings when branch-specific settings are not available
3. The User model has role-based access control with three roles: superadmin, admin, and staff
4. Several models have scopes to filter by the current user's branch for security and data isolation
5. The queuing system uses a combination of raw numbers and formatted ticket numbers with prefixes
