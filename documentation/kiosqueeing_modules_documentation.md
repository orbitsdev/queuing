# Kiosqueeing System - Implemented Modules Documentation

## Overview

The Kiosqueeing system is a comprehensive queue management solution built with Laravel 12, Livewire 3, and Wire UI. This document provides detailed information about all the modules that have been implemented in the system.

## Table of Contents

1. [System Architecture](#system-architecture)
2. [Route Structure](#route-structure)
3. [Admin Modules](#admin-modules)
   - [Branch Management](#branch-management)
   - [Service Management](#service-management)
   - [Counter Management](#counter-management)
   - [User Management](#user-management)
   - [Queue Management](#queue-management)
   - [Settings Management](#settings-management)
   - [Branch Queue Settings](#branch-queue-settings)
   - [Monitor Management](#monitor-management)
4. [Staff Modules](#staff-modules)
   - [Counter Selection](#counter-selection)
   - [Counter Transaction](#counter-transaction)
5. [Public Modules](#public-modules)
   - [Monitor Display](#monitor-display)
6. [Test Functionality](#test-functionality)
7. [Next Steps](#next-steps)

## System Architecture

The Kiosqueeing system follows a branch-centric architecture where most entities are associated with branches. The system is built with the following components:

- **Laravel 12**: Backend framework
- **Livewire 3**: For interactive components
- **Wire UI**: For UI components
- **Filament Tables and Forms**: For admin interfaces
- **TailwindCSS**: For styling
- **Vite**: Build tool

The system uses role-based access control with the following roles:
- **Superadmin**: Full system access
- **Admin**: Branch-specific administration
- **Staff**: Counter operations

## Route Structure

The system has the following route structure:

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
    Route::get('branch-queue-settings/{branch}', BranchQueueSettings::class)->name('admin.branch-queue-settings');
});

// Staff Routes
Route::middleware(['auth', 'verified', 'can:staff'])->prefix('counter')->group(function () {
    Route::get('select', SelectCounter::class)->name('counter.select');
    Route::get('transaction', CounterTransactionPage::class)->middleware(['counter.assigned'])->name('counter.transaction');
});

// Public Display Routes
Route::get('/display/{monitor}', DisplayPage::class)->name('display.show');

// Test Queue Creation Route
Route::get('/create-test-queue/{branch?}/{service?}', function($branchId = null, $serviceId = null) {
    // Implementation for creating test queue tickets
})->name('test.create-queue');
```

## Admin Modules

### Branch Management

**Component**: `App\Livewire\Admin\Branches`  
**View**: `resources\views\livewire\admin\branches.blade.php`  
**Route**: `/admin/branches`

**Features**:
- Full CRUD operations for branches
- Branch statistics (services, counters, queues)
- Safety checks for associated records when deleting
- Branch settings management
- Queue settings management

**Implementation Details**:
- Uses Filament Tables for branch listing
- Shows statistics with counts of related services, counters, and queues
- Create/Edit forms with validation for name, code, and address
- Delete action with safety checks to prevent removing branches with associated data
- Links to branch settings and queue settings

### Service Management

**Component**: `App\Livewire\Admin\Services`  
**View**: `resources\views\livewire\admin\services.blade.php`  
**Route**: `/admin/services`

**Features**:
- Branch-specific services with unique codes
- Service grouping by branch
- Full CRUD operations
- Service description and code management

**Implementation Details**:
- Uses Filament Tables with grouping by branch
- Create/Edit forms with validation for name, code, description, and branch
- Services are grouped by branch in the UI
- Branch filter for easy navigation

### Counter Management

**Component**: `App\Livewire\Admin\Counters`  
**View**: `resources\views\livewire\admin\counters.blade.php`  
**Route**: `/admin/counters`

**Features**:
- Counter creation with name and branch assignment
- Service assignment to counters
- Toggle counter status (active/inactive)
- Priority status for special counters
- Break message management
- Branch-specific counter configuration

**Implementation Details**:
- Uses Filament Tables with grouping by branch
- Create/Edit forms with validation for name, branch, services
- Toggle controls for active status and priority status
- Break message input when counter is inactive
- Service selection with branch filtering

### User Management

**Component**: `App\Livewire\Admin\Users`  
**View**: `resources\views\livewire\admin\users.blade.php`  
**Route**: `/admin/users`

**Features**:
- User creation with name, email, and role
- Role-based access (admin, staff, coordinator)
- Branch-specific user management
- Password management (creation and reset)
- Delete users with safety checks
- Protection against self-deletion

**Implementation Details**:
- Uses Filament Tables for user listing
- Create/Edit forms with validation for name, email, role, and branch
- Password management with secure hashing
- Role selection with appropriate permissions
- Branch assignment based on role

### Queue Management

**Component**: `App\Livewire\Admin\Queues`  
**View**: `resources\views\livewire\admin\queues.blade.php`  
**Route**: `/admin/queues`

**Features**:
- Comprehensive status management (waiting → called → serving → completed)
- Support for additional states (hold, skip, cancel, expire)
- Real-time status updates
- Counter reassignment
- Hold queue with reason
- Status change with dropdown actions
- Queue tracking with timestamps

**Implementation Details**:
- Uses Filament Tables for queue listing
- Status filtering and management
- Timestamp tracking for each status change
- Counter reassignment functionality
- Hold queue with reason input

### Settings Management

**Component**: `App\Livewire\Admin\ListSettings` and `App\Livewire\Admin\Settings`  
**View**: `resources\views\livewire\admin\list-settings.blade.php` and `resources\views\livewire\admin\settings.blade.php`  
**Routes**: `/admin/branch-settings` and `/admin/settings/{branch}`

**Features**:
- Branch-specific settings with fallback to global defaults
- Queue number formatting with prefixes
- Queue reset time configuration
- Human-readable time formats (12-hour AM/PM)
- Clear indicators for global vs. branch settings

**Implementation Details**:
- Branch settings list with status indicators
- Settings form with validation
- Time format selection
- Queue prefix configuration
- Default break message configuration

### Branch Queue Settings

**Component**: `App\Livewire\Admin\BranchQueueSettings`  
**View**: `resources\views\livewire\admin\branch-queue-settings.blade.php`  
**Route**: `/admin/branch-queue-settings/{branch}`

**Features**:
- Queue number base configuration
- Reset functionality for today's queues
- Reset base to 1 functionality
- Improved validation and default value handling
- Empty value handling by defaulting to 1

**Implementation Details**:
- Queue base number configuration
- Reset buttons with confirmation dialogs
- Today's queue count display
- Queue number preview

### Monitor Management

**Component**: `App\Livewire\Monitor\BranchesListForMonitorMangement` and `App\Livewire\Monitor\Monitors`  
**View**: `resources\views\livewire\monitor\branches-list-for-monitor-mangement.blade.php` and `resources\views\livewire\monitor\monitors.blade.php`  
**Routes**: `/admin/branches-for-monitor-management` and `/admin/monitors/{branch}`

**Features**:
- Branch selection for monitor management
- Monitor creation and configuration
- Service assignment to monitors
- Service ordering for display

**Implementation Details**:
- Branch selection interface
- Monitor CRUD operations
- Service assignment with ordering
- Monitor display configuration

## Staff Modules

### Counter Selection

**Component**: `App\Livewire\Counter\SelectCounter`  
**View**: `resources\views\livewire\counter\select-counter.blade.php`  
**Route**: `/counter/select`

**Features**:
- Staff can select which counter they will operate
- Counter selection is persisted in the user's profile
- Visual indicators for counter status
- Service badges for counter services

**Implementation Details**:
- Counter selection interface
- Status indicators (active, occupied)
- Service badges with improved visibility
- Counter search functionality

### Counter Transaction

**Component**: `App\Livewire\Counter\CounterTransactionPage`  
**View**: `resources\views\livewire\counter\counter-transaction-page.blade.php`  
**Route**: `/counter/transaction`

**Features**:
- Queue management (call, serve, complete, hold, skip)
- Counter status management (active/break)
- Visual indicators for queue status
- Proper display of queue numbers
- Hold queue with reason input

**Implementation Details**:
- Now serving panel with current queue details
- Waiting queue list with call buttons
- Queue action buttons (serve, complete, hold, skip)
- Counter status toggle with break message
- Database transactions for concurrency control
- Real-time queue updates

## Public Modules

### Monitor Display

**Component**: `App\Livewire\Monitor\DisplayPage`  
**View**: `resources\views\livewire\monitor\display-page.blade.php`  
**Route**: `/display/{monitor}`

**Features**:
- Display of currently serving queues
- Waiting queue list in grid format (4 columns)
- Support for multiple counters simultaneously
- Stacked layout for multiple active counters
- Light blue color scheme with high contrast
- Pulse animation on "Now Serving" counter group
- Time display showing hours and minutes

**Implementation Details**:
- Now serving section with counter and queue information
- Waiting queue grid with ticket numbers
- Real-time updates using Livewire
- Responsive design for different screen sizes
- Visual enhancements for better readability

## Test Functionality

**Route**: `/create-test-queue/{branch?}/{service?}`

**Features**:
- Generate test queue tickets
- Specify branch and service (optional)
- Automatic queue number generation
- Proper ticket number formatting with prefix

**Implementation Details**:
```php
// Get the branch settings
$setting = \App\Models\Setting::where('branch_id', $branch->id)->first();
$base = $setting ? $setting->queue_number_base : 1;
$prefix = $setting ? $setting->ticket_prefix : 'QUE';

// Count today's issued tickets for this branch
$todayCount = \App\Models\Queue::where('branch_id', $branch->id)
    ->whereDate('created_at', today())
    ->count();

// Calculate next number
$nextNumber = $base + $todayCount;

// Format ticket number with prefix
$formattedTicketNumber = $prefix . $nextNumber;

// Create the queue
$queue = \App\Models\Queue::create([
    'branch_id' => $branch->id,
    'service_id' => $service->id,
    'number' => $nextNumber,
    'ticket_number' => $formattedTicketNumber,
    'status' => 'waiting',
]);
```

## Next Steps

### Kiosk Implementation

The system is now ready for kiosk implementation. The existing test route (`/create-test-queue/{branch?}/{service?}`) provides a foundation for the kiosk functionality, which will allow customers to:

1. Select a service
2. Generate a queue ticket
3. Receive a printed or digital ticket with their queue number

**Planned Features**:
- Touchscreen-friendly interface for service selection
- Ticket generation and printing functionality
- QR code generation for mobile tracking
- Multi-language support
- Accessibility features

### API Development

To support the kiosk and potential mobile applications:

- Expand the existing ApiResponse helper for consistent API responses
- Create endpoints for kiosk-to-server communication
- Implement security measures for public-facing API

### Integration Testing

To ensure system reliability:

- Test the complete flow from ticket generation to counter service
- Verify real-time updates across all interfaces
- Ensure proper queue number generation and display

### Deployment Planning

For successful implementation:

- Hardware requirements for kiosks and display monitors
- Network configuration for multi-branch deployment
- Training materials for staff and administrators
