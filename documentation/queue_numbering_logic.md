
# 📑 Queue Numbering Logic — Kiosqueeing System

## 🎯 Purpose

This document explains how the queue numbering works for the Kiosqueeing Queuing System to ensure clear, consistent ticket numbers for both customers and staff.

---

## ✅ How the Number is Structured

| Field | Description |
|-------|--------------|
| `number` | Raw integer counter (e.g. 51) — for system use, sorting, and displays |
| `ticket_number` | Formatted string with prefix (e.g. QUE-51 or A-101) — for printing and display to customers |

---

## ✅ How the Number is Generated

Each time a customer takes a ticket:

1️⃣ **Get the base value:**  
  `queue_number_base` is stored in the `settings` table for each branch.

2️⃣ **Count today’s tickets:**  
  Query: `whereDate('created_at', today())`

3️⃣ **Calculate next number:**  
  `nextNumber = base + count`

4️⃣ **Store both:**  
  - `number = nextNumber`  
  - `ticket_number = prefix + nextNumber`

Example:
```php
$base = $settings->queue_number_base;  // e.g. 1
$todayCount = Queue::where('branch_id', $branch->id)
    ->whereDate('created_at', today())
    ->count();
$nextNumber = $base + $todayCount;
```

---

## ✅ How Daily Reset Works

- The system **does NOT delete old tickets**.
- It simply queries tickets **for TODAY**.
- So each new day naturally starts at `base` (usually 1).

No manual action needed for normal daily reset.

---

## ✅ How Manual Reset Works (Optional)

If the admin wants to force-reset numbering mid-day:

- They can update `queue_number_base` in the `settings` table (e.g. set to `1` or `500`).
- Next ticket uses new base plus count.

Example scenario:
| Before |  Base: 1, Today’s count: 50 → nextNumber: 51 |
| After Admin reset base to 100 | Base: 100, Today’s count: 50 → nextNumber: 150 |

No data is deleted.

---

## ✅ Why This is Good

- Customers see easy-to-understand numbers.
- Staff see raw number and formatted version.
- No confusion, no lost history.
- Flexible: daily reset by query, manual reset by base value.

---

## ✅ Pro Tip

Never delete queue data daily. Keep history for audit and reporting.

Use `whereDate` for daily reset and `queue_number_base` for forced admin resets.

✅ **Production ready — bank-grade design.**
