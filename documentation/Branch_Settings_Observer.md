
# 📑 Kiosqueeing — Branch-Specific Settings Management with Observer

## ✅ Overview
This document describes how the Kiosqueeing Queuing System ensures **branch-specific system settings** using a robust, automated approach based on **Eloquent Observers**. Each branch maintains its own copy of all system settings.

## ✅ Goal
- Each branch can have different settings (e.g., ticket format, reset time).
- Defaults are automatically created when a new branch is added — no manual step required.

## ✅ Table Structure

**`settings` Table:**

| Column | Type | Purpose |
|--------|------|---------|
| id | bigint | Primary key |
| branch_id | foreign key | Which branch this setting belongs to |
| key | string | Name of the setting (e.g., `ticket_prefix_style`) |
| value | text | The setting value |
| timestamps | auto | Laravel created_at & updated_at |

✅ **Unique key:** `(branch_id, key)`

---

## ✅ Default Settings

| Key | Example Value |
|-----|----------------|
| `ticket_prefix_style` | `{branch}-{number}` |
| `print_logo` | `true` |
| `queue_reset_daily` | `true` |
| `queue_reset_time` | `00:00` |
| `default_break_message` | `On break, please proceed to another counter.` |

Defaults are stored either in:
- **Config file:** `config/kiosqueeing.php`
- Or directly in the Observer class.

---

## ✅ Seeder

When deploying the system, the Seeder creates defaults for all existing branches:

```php
$branches = Branch::all();
$defaults = config('kiosqueeing.default_settings');

foreach ($branches as $branch) {
    foreach ($defaults as $setting) {
        Setting::updateOrCreate([
            'branch_id' => $branch->id,
            'key' => $setting['key'],
        ], [
            'value' => $setting['value'],
        ]);
    }
}
```

---

## ✅ Observer — Automatic on Branch Creation

**BranchObserver:**

```php
namespace App\Observers;

use App\Models\Branch;
use App\Models\Setting;

class BranchObserver
{
    public function created(Branch $branch): void
    {
        $defaults = config('kiosqueeing.default_settings');

        foreach ($defaults as $setting) {
            Setting::create([
                'branch_id' => $branch->id,
                'key' => $setting['key'],
                'value' => $setting['value'],
            ]);
        }
    }
}
```

✅ **Registered in AppServiceProvider:**

```php
use App\Models\Branch;
use App\Observers\BranchObserver;

public function boot(): void
{
    Branch::observe(BranchObserver::class);
}
```

---

## ✅ Livewire Settings Management

- `ListSettings` → Lists settings for the **logged-in user's branch only**.
- `Settings` → Edits settings for the **logged-in user's branch only**.

**Query Example:**

```php
Setting::where('branch_id', auth()->user()->branch_id)
```

✅ Edits do not affect other branches.

---

## ✅ Routes

```php
Route::get('list-settings', ListSettings::class)->name('admin.list-settings');
Route::get('settings', Settings::class)->name('admin.settings');
```

---

## ✅ Final Flow

| Event | What Happens |
|-------|-------------------------------|
| **New Branch Created** | Observer clones default settings automatically |
| **Admin Views Settings** | Sees only their branch's settings |
| **Admin Updates Settings** | Updates only their branch's settings |
| **No `group` or `description`** | Labels handled in UI code |

✅ **Robust, simple, and zero risk of missing branch settings!**

---

## ✅ Developer Notes

- Observer ensures branch safety.
- Config-driven default list is recommended.
- Seeder ensures legacy branches have defaults.
- Unique index on `(branch_id, key)` required.

---

**End of Document — Ready for Developer Handoff 🚀**
