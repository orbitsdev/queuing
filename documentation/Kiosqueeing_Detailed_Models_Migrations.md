
# 📑 Kiosqueeing Queuing System — Detailed Models & Migrations

**Prepared:** 2025-06-17 03:27:56

---

## ✅ 1️⃣ Project Overview

This documentation describes the complete database model and migration structure for the **Kiosqueeing Queuing System**. It explains each entity, its fields, data types, constraints, and relationships, with real-world examples showing how they connect to the actual queuing process in a government service center or similar environment.

---

## ✅ 2️⃣ Entity Relationship Diagram (ERD)

**Visual ERD:**

```
[ Branches ] ───< [ Services ] ───< [ Queues ] >─── [ Counters ]
                                     │
                                     └────────────> [ Users ]
```

- **Branches** have many **Services**, **Counters**, **Queues**, and **Users**.
- **Services** belong to a Branch and have many Queues.
- **Counters** belong to a Branch and can handle multiple queues.
- **Queues** link a Branch, Service, Counter (optional), and User (optional).

---

## ✅ 3️⃣ Detailed Models & Migrations

Below are the detailed models with fields, data types, purposes, and constraints.

### 🔹 Branches

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID for each branch | Auto Increment |
| name | VARCHAR | Name of the branch | Required |
| code | VARCHAR | Short code (e.g., HQ01) | Unique, Required |
| address | TEXT | Physical address | Optional |

**Example:** `Main City Hall, HQ01`

---

### 🔹 Services

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID for each service | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| name | VARCHAR | Service name (e.g., Cashier Payment) | Required |
| code | VARCHAR | Prefix for ticket numbers | Required |
| description | TEXT | Service details | Optional |
| last_ticket_number | INT | Last issued ticket number | Resets daily |

**Example:** `Cashier Payment`, prefix = C → tickets C001, C002, ...

---

### 🔹 Counters

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ID for each counter | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| name | VARCHAR | Counter name (e.g., Counter 1) | Required |
| is_priority | BOOLEAN | Allows manual pick | Default False |
| active | BOOLEAN | Indicates availability | True/False |
| break_message | TEXT | Custom message when on break | Optional |

**Example:** `Counter 4` is inactive with break message "On lunch until 1:00 PM".

---

### 🔹 Queues

| Field | Data Type | Purpose | Constraints |
|-------|------------|---------|--------------|
| id | INT (PK) | Unique ticket ID | Auto Increment |
| branch_id | INT (FK) | Link to Branch | Required |
| service_id | INT (FK) | Link to Service | Required |
| counter_id | INT (FK) | Assigned counter | Nullable |
| user_id | INT (FK) | Staff handling the ticket | Nullable |
| ticket_number | VARCHAR | The printed ticket number | Indexed |
| status | ENUM | waiting, called, held, served, skipped, expired | Required |
| hold_reason | VARCHAR | Reason for hold | Nullable |
| created_at | TIMESTAMP | Ticket creation time | Auto |
| called_at | TIMESTAMP | When called | Nullable |
| served_at | TIMESTAMP | When served | Nullable |
| hold_started_at | TIMESTAMP | Hold start time | Nullable |
| skipped_at | TIMESTAMP | When skipped | Nullable |

**Example:** Ticket `C012` is `waiting` → staff picks manually → status becomes `called` → customer served → status `served`.

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

**Example:** `Jane Doe` — `staff` for `Branch HQ01`

---

## ✅ 4️⃣ Migration Plan

- **One migration per table.**
- Foreign keys enforce data integrity.
- Indexes on `ticket_number`, `status`, `service_id`, and `counter_id` for fast lookup.
- Enum constraints validate allowed `status` and `role` values.
- Daily reset is handled by a Laravel Artisan command `queue:reset` that sets `last_ticket_number` to zero without touching historical data.

---


## ✅ 5️⃣ Real-World Example Scenario

**Walkthrough:**

1. **Customer Arrival:**  
   Maria arrives at City Hall. She uses the kiosk to select `Cashier Payment`.  
   The system generates ticket `C101` with status `waiting`. Maria waits in the lobby.

2. **Ticket Called:**  
   Cashier 1 finishes serving the previous customer and opens the dashboard.  
   They see all `waiting` tickets for Cashier Service.  
   They click **Call** for `C101`. The system updates status to `called` and assigns it to Counter 1.

3. **Serving:**  
   Maria sees her number on the monitor and walks to Counter 1.  
   When Maria sits down, the staff clicks **Start Serving** — status becomes `serving`.

4. **Complete Service:**  
   Staff completes the payment and clicks **Complete** — status becomes `completed`.

5. **Hold/Resume:**  
   If Maria forgot a document, staff clicks **Hold** — status `held`.  
   When she returns, staff clicks **Resume** — status goes back to `called`.  
   If Maria never returns, staff clicks **Skip** — status `skipped`.  
   If Maria requests to cancel entirely, staff clicks **Cancel** — status `cancelled`.

6. **Breaks:**  
   If Cashier 1 takes a break, they mark their counter as inactive and add a break message.  
   The system skips assigning tickets to this counter until they return.

7. **Reports:**  
   Admins can see how many tickets were `waiting`, `called`, `serving`, `completed`, `held`, `skipped`, `cancelled`, or `expired` — filterable by date, service, counter, and staff.

8. **Daily Reset:**  
   At midnight, each service's ticket number resets, but all ticket history stays available for audits and reports.

---

## ✅ 6️⃣ Conclusion

This `.md` is the final, updated, robust guide for developers to build and maintain the Kiosqueeing System confidently.

---

**End of file.**
