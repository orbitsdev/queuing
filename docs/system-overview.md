# Queuing System Documentation

## System Overview

The Queuing System is a comprehensive solution for managing queues, counters, services, and monitors across multiple branches. It provides role-based access for administrators and staff, with real-time queue management and display capabilities.

## Database Structure

### Models and Relationships

#### Branch
- Central entity that organizes the entire system
- Has many: Services, Counters, Queues, Users, Settings, Monitors
- Observed by BranchObserver (creates default settings when a branch is created)

#### Service
- Represents a service offered at a branch
- Belongs to: Branch
- Has many: Queues
- Many-to-many: Counters (through CounterService), Monitors (through MonitorService)

#### Counter
- Physical or virtual service point where staff serve customers
- Belongs to: Branch, User (optional)
- Many-to-many: Services (through CounterService)

#### Queue
- Represents a customer in line for service
- Belongs to: Branch, Service
- Has states: waiting, serving, served, no_show, cancelled

#### User
- System user with role-based access (superadmin, admin, staff)
- Belongs to: Branch (except superadmin)
- Has one: Counter (for staff)

#### Setting
- Configuration settings for branches
- Belongs to: Branch
- Includes: queue prefix, reset time, etc.
- Supports global defaults with branch-specific overrides

#### Monitor
- Display screen showing queue status
- Belongs to: Branch
- Many-to-many: Services (through MonitorService with sort_order)
- Used for public display of queue information

#### MonitorService
- Pivot table for Monitor-Service relationship
- Includes sort_order for display sequence

#### CounterService
- Pivot table for Counter-Service relationship
- Determines which services a counter can process

### Migrations

#### 1. `create_branches_table`
- `id` - bigint unsigned, auto-increment primary key
- `name` - string, branch name
- `code` - string, unique branch code
- `address` - text, nullable
- `is_active` - boolean, default true
- `timestamps` - created_at, updated_at

#### 2. `create_counters_table`
- `id` - bigint unsigned, auto-increment primary key
- `branch_id` - foreign key to branches table
- `user_id` - unsigned bigint, nullable (staff assigned to counter)
- `name` - string, counter name
- `is_priority` - boolean, default false
- `active` - boolean, default true
- `break_message` - text, nullable
- `timestamps` - created_at, updated_at

#### 3. `create_users_table`
- `id` - bigint unsigned, auto-increment primary key
- `name` - string, user's full name
- `email` - string, unique email address
- `email_verified_at` - timestamp, nullable
- `password` - string, hashed password
- `role` - enum ('superadmin', 'admin', 'staff')
- `branch_id` - foreign key to branches table, nullable
- `counter_id` - foreign key to counters table, nullable
- `remember_token` - string, nullable
- `timestamps` - created_at, updated_at

#### 4. `create_services_table`
- `id` - bigint unsigned, auto-increment primary key
- `branch_id` - foreign key to branches table
- `name` - string, service name
- `code` - string, service code (unique per branch)
- `description` - text, nullable
- `is_active` - boolean, default true
- `current_number` - integer, default 0 (for ticket numbering)
- `timestamps` - created_at, updated_at

#### 5. `create_queues_table`
- `id` - bigint unsigned, auto-increment primary key
- `branch_id` - foreign key to branches table, cascade on delete
- `service_id` - foreign key to services table, cascade on delete
- `counter_id` - foreign key to counters table, nullable, null on delete
- `user_id` - foreign key to users table, nullable, null on delete
- `number` - unsigned integer, raw ticket number
- `ticket_number` - string, formatted ticket number (with prefix)
- `status` - enum ('waiting', 'called', 'serving', 'held', 'served', 'skipped', 'cancelled', 'expired', 'completed'), default 'waiting'
- `hold_reason` - string, nullable
- `timestamps` - created_at, updated_at
- `called_at` - timestamp, nullable
- `serving_at` - timestamp, nullable
- `served_at` - timestamp, nullable
- `hold_started_at` - timestamp, nullable
- `skipped_at` - timestamp, nullable
- `cancelled_at` - timestamp, nullable
- Indexes on: number, ticket_number, status, service_id, counter_id

#### 6. `create_settings_table`
- `id` - bigint unsigned, auto-increment primary key
- `branch_id` - foreign key to branches table, nullable, cascade on delete (null for global settings)
- `ticket_prefix` - string, default 'QUE'
- `print_logo` - boolean, default true
- `queue_reset_daily` - boolean, default true
- `queue_reset_time` - time, default '00:00'
- `queue_number_base` - unsigned integer, default 1
- `default_break_message` - string, default 'On break, please proceed to another counter.'
- `timestamps` - created_at, updated_at
- Unique constraint on branch_id (each branch can only have one settings record)

#### 7. `update_settings_table_structure`
- Modified settings table to improve branch-specific settings with global defaults

#### 8. `add_counter_user_foreign_keys`
- Added foreign key constraints between counters and users tables

#### 9. `create_counter_service_table` (Pivot Table)
- `id` - bigint unsigned, auto-increment primary key
- `counter_id` - foreign key to counters table, cascade on delete
- `service_id` - foreign key to services table, cascade on delete
- `timestamps` - created_at, updated_at
- Unique constraint on ['counter_id', 'service_id']

#### 10. `create_monitors_table`
- `id` - bigint unsigned, auto-increment primary key
- `branch_id` - foreign key to branches table
- `name` - string, monitor name
- `location` - string, nullable
- `description` - text, nullable
- `timestamps` - created_at, updated_at

#### 11. `create_monitor_services_table` (Pivot Table)
- `id` - bigint unsigned, auto-increment primary key
- `monitor_id` - foreign key to monitors table, cascade on delete
- `service_id` - foreign key to services table, cascade on delete
- `sort_order` - integer, default 0, for ordering services on display
- `timestamps` - created_at, updated_at
- Unique constraint on ['monitor_id', 'service_id']

## Application Structure

### Routes

```php
// Main Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Role-based dashboard redirect
Route::get('dashboard', function() {
    switch (Auth::user()->role) {
        case 'superadmin':
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'staff':
            return redirect()->route('counter.transaction');
    }
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'can:superadmin_or_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('branches', Branches::class)->name('admin.branches');
    Route::get('services', Services::class)->name('admin.services');
    Route::get('counters', Counters::class)->name('admin.counters');
    Route::get('users', Users::class)->name('admin.users');
    Route::get('queues', Queues::class)->name('admin.queues');
    Route::get('branch-settings', ListSettings::class)->name('admin.branch-settings');
    Route::get('settings/{branch}', Settings::class)->name('admin.settings');
    Route::get('branches-for-monitor-management', BranchesListForMonitorMangement::class)->name('admin.branches-for-monitor-management');
    Route::get('monitors/{branch}', Monitors::class)->name('admin.monitors');
});

// Staff Routes
Route::middleware(['auth', 'verified', 'can:staff'])->prefix('counter')->group(function () {
    Route::get('select', SelectCounter::class)->name('counter.select');
    Route::get('transaction', CounterTransactionPage::class)->middleware(['counter.assigned'])->name('counter.transaction');
});

// Public Display Routes
Route::get('/display/{monitor}', DisplayPage::class)->name('display.show');
```

## Core Modules

### Admin Modules

1. **Dashboard**
   - Real-time statistics and branch overview
   - Key performance indicators for queue management

2. **Branches**
   - Full CRUD operations for branch management
   - Branch statistics and status tracking

3. **Services**
   - Branch-specific service management
   - Service grouping and organization

4. **Counters**
   - Counter management with status toggles
   - Counter-service assignments
   - Break message management

5. **Users**
   - User management with role-based access
   - Branch assignments for users

6. **Queues**
   - Comprehensive queue tracking
   - Status management (waiting, serving, served, etc.)
   - Queue history and analytics

7. **Settings**
   - Branch-specific settings with global defaults
   - Queue prefix, reset time, and other configurations

8. **Monitors**
   - Monitor management with service integration
   - Service ordering for display
   - Display customization options

### Counter Module

1. **Counter Selection**
   - Staff can select which counter to operate
   - Counter status management

2. **Transaction Page**
   - Queue processing interface
   - Call next, mark as served, no-show, or cancel
   - Service switching

### Display System

1. **Monitor Display**
   - Shows now serving and waiting queues
   - Light blue color scheme with high contrast
   - Clean, organized layout with good spacing
   - Supports multiple counters serving simultaneously
   - Customizable display options

## Technical Implementation

### Frontend

- Laravel Blade templates with Livewire components
- Wire UI for consistent UI components
- Filament Forms and Tables for admin interfaces
- Responsive design for various screen sizes
- Real-time updates using Livewire

### Backend

- Laravel 12 framework
- Livewire 3 for interactive components
- Eloquent ORM for database interactions
- Role-based authorization
- Observer pattern for model events

### Key Features

- Branch-centric architecture
- Real-time queue management
- Role-based access control
- Customizable settings per branch
- Monitor display system with service filtering
- Counter management with service assignments

## User Roles

1. **Superadmin**
   - Full system access
   - Can manage all branches and settings

2. **Admin**
   - Branch-specific administration
   - Manages services, counters, and users within their branch

3. **Staff**
   - Counter operations
   - Queue processing and management

## Workflow

1. Admin creates branches, services, counters, and users
2. Admin assigns services to counters and monitors
3. Staff selects a counter and processes queues
4. Customers view their queue status on monitor displays
5. Admin monitors performance through the dashboard

## Future Enhancements

- Mobile app for queue notifications
- SMS notifications for customers
- Advanced analytics and reporting
- Self-service kiosk integration
- API for third-party integrations
