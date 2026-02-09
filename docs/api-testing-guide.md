# API Testing Guide

Quick reference for testing the Kiosk API endpoints.

---

## Test Data Reference

### Branch
| ID | Code | Name |
|----|------|------|
| 1 | `HQ01` | Main City Hall |

### Services (Branch ID: 1)
| ID | Code | Name |
|----|------|------|
| 1 | BOBP | Business Online Billing and Payment |
| 2 | NBA | New Business Application |
| 3 | RBA | Renew Business Application |
| 4 | RTBP | Realty Tax Online Billing and Payment |
| 5 | OPO | Online Payment Order |
| 6 | BPR | Building Permit Requirements |
| 7 | COR | Certificate of Occupancy Requirements |
| 8 | AT | Application Tracking |
| 9 | BPA | Building Permit Application |
| 10 | OSCP | OSCP Online Billing and Payment |
| 11 | COA | Certificate of Occupancy Application |
| 12 | PPTR | Pay PTR (Professional Tax Receipt) |
| 13 | RP | Register Professional |
| 14 | UP | Update Professional |

---

## API Endpoints

### 1. Create Queue Ticket (Main Endpoint)

**POST** `/api/kiosk/queue`

**Required Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `branch_code` | string | Branch code (e.g., "HQ01") |
| `service_id` | integer | Service ID (1-14) |

**Example Request:**
```bash
curl -X POST http://queuing.test/api/kiosk/queue \
  -H "Content-Type: application/json" \
  -d '{"branch_code":"HQ01","service_id":1}'
```

**Success Response:**
```json
{
  "success": true,
  "message": "Queue ticket created successfully",
  "data": {
    "id": 1,
    "number": 1,
    "ticket_number": "1",
    "status": "waiting",
    "service": {
      "id": 1,
      "name": "Business Online Billing and Payment"
    },
    "branch": {
      "id": 1,
      "name": "Main City Hall"
    }
  }
}
```

---

### 2. Get Services by Branch

**GET** `/api/kiosk/services/{branchCode}`

**Example:**
```bash
curl http://queuing.test/api/kiosk/services/HQ01
```

---

### 3. Check Branch

**POST** `/api/kiosk/branch/check`

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `code` | string | Branch code |

**Example:**
```bash
curl -X POST http://queuing.test/api/kiosk/branch/check \
  -H "Content-Type: application/json" \
  -d '{"code":"HQ01"}'
```

---

### 4. Get Branch Details

**GET** `/api/kiosk/branch/{code}`

**Example:**
```bash
curl http://queuing.test/api/kiosk/branch/HQ01
```

---

## Quick Test Commands

### Test 1: Create Single Queue
```bash
curl -X POST http://queuing.test/api/kiosk/queue \
  -H "Content-Type: application/json" \
  -d '{"branch_code":"HQ01","service_id":1}'
```

### Test 2: Create Multiple Queues (Different Services)
```bash
# Service 1 - Business Online Billing
curl -X POST http://queuing.test/api/kiosk/queue -H "Content-Type: application/json" -d '{"branch_code":"HQ01","service_id":1}'

# Service 2 - New Business Application
curl -X POST http://queuing.test/api/kiosk/queue -H "Content-Type: application/json" -d '{"branch_code":"HQ01","service_id":2}'

# Service 8 - Application Tracking
curl -X POST http://queuing.test/api/kiosk/queue -H "Content-Type: application/json" -d '{"branch_code":"HQ01","service_id":8}'
```

### Test 3: Concurrent Queue Creation (Duplicate Test)
```bash
# Run 5 requests at the same time - all should get unique numbers
for i in 1 2 3 4 5; do
  curl -s -X POST http://queuing.test/api/kiosk/queue \
    -H "Content-Type: application/json" \
    -d '{"branch_code":"HQ01","service_id":1}' &
done
wait
```

### Test 4: Invalid Branch Code
```bash
curl -X POST http://queuing.test/api/kiosk/queue \
  -H "Content-Type: application/json" \
  -d '{"branch_code":"INVALID","service_id":1}'
```

Expected: `{"success":false,"message":"Branch not found"}`

### Test 5: Invalid Service ID
```bash
curl -X POST http://queuing.test/api/kiosk/queue \
  -H "Content-Type: application/json" \
  -d '{"branch_code":"HQ01","service_id":999}'
```

Expected: Validation error

---

## Web Test Route

For quick browser testing without curl:

**GET** `/create-test-queue?service_id=1`

Opens in browser: `http://queuing.test/create-test-queue?service_id=1`

---

## PowerShell Commands (Windows)

```powershell
# Create queue
Invoke-RestMethod -Uri "http://queuing.test/api/kiosk/queue" -Method Post -ContentType "application/json" -Body '{"branch_code":"HQ01","service_id":1}'

# Get services
Invoke-RestMethod -Uri "http://queuing.test/api/kiosk/services/HQ01" -Method Get
```

---

## Expected Behavior

1. **Queue numbers are sequential** - Each new queue gets the next number
2. **No duplicates** - Even with concurrent requests, numbers are unique
3. **Ticket format** - Numbers only (1, 2, 3...) since prefix is empty
4. **Real-time updates** - Monitor displays update via WebSocket when queue is created

---

## Troubleshooting

### "Branch not found"
- Check branch code is correct: `HQ01`
- Verify branch exists in database

### "Service not found for this branch"
- Check service_id is valid (1-14)
- Verify service belongs to the branch

### Duplicate Numbers
- This is fixed with database locking
- If still happening, check queue worker is running: `php artisan queue:work`
