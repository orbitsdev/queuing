# Deployment Guide - Updating Existing System

This guide helps you safely update the existing production system with data.

---

## Quick Update (Copy-Paste Commands)

For fast deployment, run these commands in order:

```bash
# 1. Backup database first!
mysqldump -u root -p queue-gensan > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Pull latest code
git pull origin main

# 3. Install dependencies & build
composer install --no-dev --optimize-autoloader
npm install
npm run build

# 4. Run migrations (if any)
php artisan migrate

# 5. Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
sudo supervisorctl restart kiosqueeing-queue:*
sudo supervisorctl restart kiosqueeing-reverb
```

---

## Pre-Deployment Checklist

Before updating, ensure you have:
- [ ] Database backup
- [ ] Note current branch: `git branch`
- [ ] No uncommitted changes: `git status`

---

## Step 1: Backup (CRITICAL)

```bash
# Backup database
mysqldump -u root -p queue-gensan > backup_$(date +%Y%m%d_%H%M%S).sql

# Or for SQLite
cp database/database.sqlite database/database.sqlite.backup
```

---

## Step 2: Pull Updates

```bash
# Check current status
git status

# Pull latest changes
git pull origin main

# If you have local changes, stash them first
git stash
git pull origin main
git stash pop
```

---

## Step 3: Install Dependencies & Build

```bash
# PHP dependencies
composer install --no-dev --optimize-autoloader

# Node dependencies and build frontend
npm install
npm run build
```

---

## Step 4: Run Migrations (SAFE)

The migration for forward feature is safe - it only ADDS columns, doesn't modify existing data.

```bash
# Check pending migrations first
php artisan migrate:status

# Run migrations (will show what will be run)
php artisan migrate

# If using MySQL, you can add --pretend to see SQL without running
php artisan migrate --pretend
```

**Expected migration:** `add_forward_columns_to_queues_table`
- Adds: `previous_service_id`, `forwarded_by`, `is_forwarded`, `forward_reason`
- Safe: All nullable, won't affect existing data

---

## Step 5: Clear Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 6: Restart Services

```bash
# Restart queue worker (processes background jobs like broadcasting)
sudo supervisorctl restart kiosqueeing-queue:*

# Restart Reverb (WebSocket server for real-time updates)
sudo supervisorctl restart kiosqueeing-reverb

# Verify services are running
sudo supervisorctl status
```

---

## Test Accounts

| Role | Email | Password | Access |
|------|-------|----------|--------|
| **Super Admin** | superadmin@kiosqueeing.local | password | Full system |
| **Admin** | admin@kiosqueeing.local | password | Branch admin |
| **Staff** | staff@kiosqueeing.local | password | Counter only |

> Note: Change default passwords in production!

---

## System Flow

### 1. Admin Setup Flow
```
Login (Admin) → Dashboard → Services → Counters → Users → Monitors → Settings
```

### 2. Staff Flow
```
Login (Staff) → Select Counter → Transaction Page → Serve Customers
```

### 3. Customer Flow (Kiosk)
```
Kiosk Screen → Select Service → Get Ticket Number → Wait → Called to Counter
```

### 4. Monitor Display Flow
```
/display/{monitor_id} → Shows current serving + waiting queue
```

---

## Testing Steps

### Test 1: Queue Creation (API)
```bash
# Create a queue ticket
curl -X POST http://YOUR_DOMAIN/api/kiosk/queue \
  -H "Content-Type: application/json" \
  -d '{"branch_code":"HQ01","service_id":1}'
```

Expected: Returns ticket with sequential number

### Test 2: Counter Operations
1. Login as **staff@kiosqueeing.local**
2. Select a counter
3. Click on a waiting ticket → Should show "Serving"
4. Complete/Skip/Hold → Should update status
5. Forward → Should move to another service

### Test 3: Monitor Display
1. Open `/display/1` in browser
2. Create a queue via API or test route
3. Ticket should appear in "Waiting" section
4. When staff calls ticket → Moves to "Now Serving"

### Test 4: Concurrent Queue Creation
```bash
# Run this to test no duplicate numbers
for i in 1 2 3 4 5; do
  curl -s -X POST http://YOUR_DOMAIN/api/kiosk/queue \
    -H "Content-Type: application/json" \
    -d '{"branch_code":"HQ01","service_id":1}' &
done
wait
```
Expected: All tickets have unique sequential numbers

---

## Rollback (If Something Goes Wrong)

```bash
# Restore database
mysql -u YOUR_USER -p YOUR_DATABASE < backup_YYYYMMDD_HHMMSS.sql

# Or for SQLite
cp database/database.sqlite.backup database/database.sqlite

# Rollback last migration
php artisan migrate:rollback --step=1

# Go back to previous commit
git checkout HEAD~1
```

---

## Change Ticket Format (Simple Numbers)

To change from "QUE1" to just "1":

### Option 1: Admin Panel
1. Login as Admin
2. Go to **Settings** → Select Branch
3. Change **Ticket Prefix** from "QUE" to "" (empty)
4. Save

### Option 2: Database
```sql
UPDATE settings SET ticket_prefix = '' WHERE branch_id = 1;
```

### Option 3: Artisan
```bash
php artisan tinker
>>> App\Models\Setting::where('branch_id', 1)->update(['ticket_prefix' => '']);
```

---

## URLs Reference

| URL | Description |
|-----|-------------|
| `/` | Home (redirects to dashboard) |
| `/login` | Login page |
| `/admin/dashboard` | Admin dashboard |
| `/admin/services` | Manage services |
| `/admin/counters` | Manage counters |
| `/admin/users` | Manage users |
| `/admin/queues` | View all queues |
| `/admin/settings/{branch}` | Branch settings |
| `/counter/select` | Staff counter selection |
| `/counter/transaction` | Staff transaction page |
| `/display/{monitor}` | Public monitor display |
| `/api/kiosk/queue` | API: Create queue |
| `/api/kiosk/services/{branchCode}` | API: Get services |
| `/create-test-queue` | Test: Create queue |

---

## Troubleshooting

### Duplicate Queue Numbers
Fixed in this update. If you see old duplicates:
```sql
-- Check for duplicates
SELECT ticket_number, COUNT(*)
FROM queues
WHERE DATE(created_at) = CURDATE()
GROUP BY ticket_number
HAVING COUNT(*) > 1;
```

### Counter Already Occupied
Fixed in this update. Counter now properly locks before assignment.

### Migration Failed
```bash
# Check migration status
php artisan migrate:status

# If stuck, check if columns exist
php artisan tinker
>>> Schema::hasColumn('queues', 'is_forwarded');
```

### WebSocket Not Working
```bash
# Check Reverb is running
sudo supervisorctl status kiosqueeing-reverb

# Check logs
tail -f storage/logs/reverb.log
```
