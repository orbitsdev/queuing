# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Kiosqueeing is a queue management system built with Laravel 12, Livewire 3, and Filament 3.3. It manages queues across multiple branches with real-time updates via Laravel Reverb (WebSockets).

## Commands

### Development
```bash
composer run dev          # Runs server, queue listener, and Vite concurrently
php artisan reverb:start  # WebSocket server for real-time updates (run separately)
```

### Individual Services
```bash
php artisan serve         # Laravel dev server (port 8000)
php artisan queue:listen  # Queue worker
npm run dev               # Vite dev server
```

### Testing & Linting
```bash
composer test             # Run Pest tests (clears config first)
php artisan test tests/Feature/ExampleTest.php  # Single test file
./vendor/bin/pint         # PHP code style fixer
```

### Build
```bash
npm run build             # Production frontend build
php artisan migrate       # Run migrations
```

## Architecture

### Three Main Interfaces
1. **Admin Dashboard** (`/admin/*`) - Branch managers manage queues, counters, services, monitors
2. **Staff Interface** (`/counter/*`) - Counter employees process customers
3. **Monitor Display** (`/display/{monitor}`) - Public LCD screens showing queue status

### Core Data Flow
- **Branch** is the central entity - everything belongs to a branch
- **Services** offered at branches, assigned to **Counters** and **Monitors** via pivot tables
- **Queue** records track customers through states: `waiting` → `called` → `serving` → `completed/skipped/cancelled`
- **Settings** per branch control ticket prefix, queue numbering base, reset time

### Queue Numbering Logic
- Formula: `settings.queue_number_base + count_of_queues_created_today`
- Formatted with prefix from settings (e.g., "QUE1", "QUE2")
- Resets daily at configurable time

### Real-time Updates
- Events (`QueueStatusChanged`, `NewQue`, `CallNumber`) broadcast via Reverb WebSockets
- All interfaces listen for updates to refresh automatically

### Authorization
Gates defined in `AppServiceProvider`:
- `superadmin` - Full system access
- `admin` / `superadmin_or_admin` - Branch-specific admin
- `staff` - Counter operations only

Middleware: `counter.assigned` ensures staff has selected a counter before accessing transaction page.

## Key Directories

```
app/Livewire/Admin/     # Admin dashboard Livewire components
app/Livewire/Counter/   # Staff counter interface components
app/Livewire/Monitor/   # Display screen components
app/Events/             # Broadcast events for real-time updates
app/Observers/          # Model observers (BranchObserver auto-creates settings)
app/Services/           # Business logic (TransactionHistoryService)
app/Helpers/            # ApiResponse for consistent API responses
```

## Tech Stack
- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Livewire 3, Volt, Flux, Wire UI, TailwindCSS 3.4
- **Admin UI**: Filament 3.3
- **Real-time**: Laravel Reverb (WebSockets)
- **Build**: Vite 6
- **Testing**: Pest 3.8
- **Database**: SQLite (default), supports MySQL/PostgreSQL

## Database Notes
- All models use mass assignment (`Model::unguard()` in AppServiceProvider)
- Pivot tables: `counter_service`, `monitor_service` (includes `sort_order`)
- Queue status enum: `waiting`, `called`, `serving`, `held`, `served`, `skipped`, `cancelled`, `expired`, `completed`
