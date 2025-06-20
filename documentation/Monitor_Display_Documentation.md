
# 📑 Kiosqueeing Queue Display — Monitor Module

## ✅ Overview

This is the confirmed production version for all branch monitor displays as of June 2025.

## ✅ Route
```
Route::get('/display/{monitor}', DisplayPage::class)->name('display.show');
```

## ✅ Component: `DisplayPage`
- Filters `servingQueues` and `waitingQueues` by monitor services.
- Includes counter info for serving.
- Limits waiting queue to 10 items for clean display.

## ✅ Layout

| Panel | Purpose |
|-------|---------|
| **Header** | Branch name, monitor name, live clock |
| **Left Panel (1/3)** | Now Serving list: rows of Counter Name + Ticket Number |
| **Right Panel (2/3)** | Responsive Waiting Queue grid, auto-resizes font and box by count |

## ✅ Colors
- **Base BG:** `#001a71` (dark blue)
- **Accent:** `#cee1ff` (light blue)
- **Text:** White and dark blue for high contrast

## ✅ Livewire Blade Notes
- Uses `wire:poll.5s` for auto updates.
- Left panel uses `flex` + `grid` for neat rows.
- Right panel uses dynamic Tailwind classes for responsive columns.

## ✅ Usage
- Place the Blade in `resources/views/livewire/monitor/display-page.blade.php`.
- Make sure MonitorSeeder, Branch, and Services are set up.
- Attach monitor to services in DB.

## ✅ Future
This serves as the baseline for any future display UI upgrades.
