
# 📑 Kiosqueeing Queuing System — Detailed Models & Migrations

**Prepared:** 2025-06-17 (Updated)

---

## ✅ 1️⃣ Project Overview

This documentation describes the complete database model and migration structure for the **Kiosqueeing Queuing System**. It includes updated statuses, settings, and clear examples.

---

## ✅ 2️⃣ Entity Relationship Diagram (ERD)

```
[ Branches ] ───< [ Services ] ───< [ Queues ] >─── [ Counters ]
                                     │
                                     └────────────> [ Users ]
```

---

## ✅ 3️⃣ Detailed Models & Migrations

### 🔹 Branches

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID for each branch | Auto Increment |
| name | VARCHAR | Branch name | Required |
| code | VARCHAR | Short code | Unique, Required |
| address | TEXT | Physical address | Optional |

---

### 🔹 Services

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| name | VARCHAR | Service name | Required |
| code | VARCHAR | Ticket prefix | Required |
| description | TEXT | Details | Optional |
| last_ticket_number | INT | Tracks last issued number | Resets daily |

---

### 🔹 Counters

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| name | VARCHAR | Counter label | Required |
| is_priority | BOOLEAN | Can manually pick | Default False |
| active | BOOLEAN | Is available | True/False |
| break_message | TEXT | Message if on break | Optional |

---

### 🔹 Queues

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ticket ID | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| service_id | INT (FK) | Link to Service | Required |
| counter_id | INT (FK) | Assigned counter | Nullable |
| user_id | INT (FK) | Staff handling ticket | Nullable |
| ticket_number | VARCHAR | Printed ticket number | Indexed |
| status | ENUM | waiting, called, serving, held, completed, skipped, expired, cancelled | Required |
| hold_reason | VARCHAR | Reason for hold | Nullable |
| created_at | TIMESTAMP | Ticket created | Auto |
| called_at | TIMESTAMP | When called | Nullable |
| served_at | TIMESTAMP | When served | Nullable |
| hold_started_at | TIMESTAMP | Hold start time | Nullable |
| skipped_at | TIMESTAMP | When skipped | Nullable |

**Example:** Ticket `C012` is `waiting` → staff clicks **Call** → status `called` → customer arrives → status `serving` → service done → status `completed`. If customer cancels, mark as `cancelled`.

---

### 🔹 Users

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | User ID | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| name | VARCHAR | Full name | Required |
| email | VARCHAR | Login email | Unique, Required |
| password | STRING (HASHED) | Encrypted password | Required |
| role | ENUM | admin, staff, coordinator | Required |

---

### 🔹 Settings

| Field | Data Type | Purpose |
|-------|------------|---------|
| id | INT (PK) | Unique ID |
| key | VARCHAR | Config key |
| value | TEXT | Value |
| timestamps | TIMESTAMP | Track changes |

**Example settings:**  
- `ticket_prefix_style` = `branch_code`  
- `print_logo` = `true`  
- `announcement_voice` = `en_female`  
- `max_hold_time_minutes` = `5`

---

## ✅ 4️⃣ Migration Plan

- One migration per table.
- Foreign keys enforce integrity.
- Indexes on `ticket_number`, `status`, `service_id`, `counter_id`.
- Enum constraints for `status` and `role`.
- Daily reset with `queue:reset` Artisan command.

---

## ✅ 5️⃣ Real-World Scenarios



## ✅ 5️⃣ Real-World Example Scenario

**Walkthrough:**

1. **Customer Arrival:**  
   Maria arrives at City Hall. She uses the kiosk to select `Cashier Payment`.  
   The system generates ticket `C101` with status `waiting`. Maria waits in the lobby.

2. **Counter Staff:**  
   Cashier 1 finishes serving and opens the dashboard.  
   They see all `waiting` tickets for Cashier Service.  
   They manually pick `C101`. The system updates status to `called` and assigns `Counter 1`.

3. **Service:**  
   Maria goes to Counter 1. Staff completes the payment. They click `Serve` → status becomes `served`.

4. **Hold/Skip:**  
   If Maria forgot a document, staff clicks `Hold` → status `held`.  
   When she returns, staff clicks `Resume` → status back to `called`.  
   If Maria never returns, staff clicks `Skip` → status `skipped`.

5. **Breaks:**  
   If Cashier 1 takes a break, they mark their counter as inactive and add a message.  
   The system skips assigning tickets to them until they return.

6. **Reports:**  
   Admins can see how many tickets were served, held, skipped, or pending by date, service, counter, and staff.

7. **Daily Reset:**  
   At midnight, ticket numbers reset for each service. Historical records stay forever for auditing.

---

## ✅ 6️⃣ Conclusion

This `.md` file provides a **clear, real-world-ready model and migration guide**, with a clean ERD, field definitions, constraints, and use cases — ensuring your Kiosqueeing System is robust and audit-proof.

---

**End of file.**


- Customers pick service, get ticket.
- Staff calls ticket (`called`), customer walks to counter.
- Staff starts serving (`serving`).
- Service completes → ticket is `completed`.
- If paused → `held`; if skipped → `skipped`; if cancelled → `cancelled`.

---

## ✅ 6️⃣ Conclusion

This `.md` is the final, aligned, robust guide for developers building the Kiosqueeing system.

---

**End of file.**
