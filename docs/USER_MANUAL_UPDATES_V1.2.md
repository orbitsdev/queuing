# User Manual Updates - Version 1.2

This document contains all the updates needed for the QUEWIE User Manual.
Copy and paste the relevant sections into your Word document.

---

## 1. COVER PAGE UPDATE

**Location:** Page 1

**Change:** Update version number

**OLD:**
```
Version 1.1
```

**NEW:**
```
Version 1.2
```

---

## 2. LAST UPDATED DATE

**Location:** Page 29 (Footer)

**OLD:**
```
Last updated: October 2025
```

**NEW:**
```
Last updated: February 2026
```

---

## 3. SYSTEM FEATURES UPDATE (Section 1.5)

**Location:** Page 4-5

**ADD** the following new features to the bullet list:

```
▪ Queue Forward/Transfer – Allows staff to transfer a client's ticket to a different
  service without requiring the client to get a new queue number. This is useful when
  a client needs to be redirected to another department.

▪ Service Indicator on Monitor – The monitor display now shows which services are
  currently being displayed in the header, helping staff and clients identify which
  queues are being served on each screen.

▪ Real-Time Admin Updates – When administrators delete a queue from the admin panel,
  all connected monitors automatically update to reflect the change.

▪ Counter Assignment Protection – The system now prevents multiple staff from
  selecting the same counter simultaneously, ensuring proper counter allocation.

▪ Duplicate Ticket Prevention – Enhanced queue numbering system with database-level
  locking to prevent duplicate ticket numbers even during high-traffic periods.
```

---

## 4. TICKET FORMAT UPDATE (Section 11.0 Settings Management)

**Location:** Page 21-22

**UPDATE** the Options section:

**OLD:**
```
Options
• Ticket Prefix – Example: QUE → QUE-001.
• Starting Number – Defines where ticket counting begins.
• Queue Reset Time – Default automatic reset: 4:00 AM.
```

**NEW:**
```
Options
• Ticket Prefix – The prefix added before ticket numbers. Can be set to empty for
  simple numeric tickets (e.g., 1, 2, 3) or include text (e.g., "QUE" → QUE-001).
  For elderly-friendly displays, it is recommended to use empty prefix for simple
  numbers.
• Starting Number – Defines where ticket counting begins (default: 1).
• Queue Reset Time – Default automatic reset: 4:00 AM.
```

---

## 5. MONITOR DISPLAY UPDATE (Section 8.0 & 13.0)

**Location:** Page 17-18 and Page 26-27

**ADD** to Section 8.0 Monitor Management Note:

```
Note:
Monitors automatically update queue information in real time through the local network.
The system's voice call-out feature announces the next ticket number whenever staff
initiate a call. For easier identification and maintenance, it is recommended to use
descriptive monitor names based on their physical location.

NEW: The monitor header now displays which services are being shown on that specific
monitor. Service codes (e.g., BOBP, NBA, AT) appear in the header bar next to the
branch name and time. Hovering over a service code shows the full service name.
```

**UPDATE** Figure 23 caption or add note:

```
Figure 23. – Display View

Note: The monitor header now shows the assigned services (e.g., "Services: [BOBP] [NBA]
[AT]") to help identify which queues are displayed on each screen.
```

---

## 6. NEW SECTION: QUEUE FORWARD/TRANSFER FEATURE

**Location:** Add as Section 12.3 (after Section 12.2 Serving Clients)

**ADD** the following new section:

```
12.3 Forwarding/Transferring Queues

The Forward Queue feature allows staff to transfer a client's ticket to a different
service without requiring the client to obtain a new queue number. This is particularly
useful when:

• A client was directed to the wrong service initially
• A transaction requires processing by another department
• The client needs additional services after the initial transaction

Steps to Forward a Queue:

1. While serving a client, click the Forward button on the counter interface.
2. Select the target service from the dropdown list.
3. Optionally, enter a reason for the transfer (e.g., "Requires building permit review").
4. Click Confirm Forward to complete the transfer.
5. The ticket will automatically appear in the queue list of the target service.
6. The original service and transfer reason are recorded in the transaction history.

Note:
Forwarded tickets retain their original queue number and are marked with a "Forwarded"
indicator. The transaction history logs both the original service and the forwarding
staff member for audit purposes. Staff at the receiving counter will see the forwarded
ticket in their queue list with the transfer reason displayed.
```

---

## 7. COUNTER SELECTION UPDATE (Section 12.1)

**Location:** Page 23

**UPDATE** the Note section:

**OLD:**
```
Note:
Only active counters appear in the selection list. Each counter is configured with
specific services defined by the administrator, and staff must verify the assigned
counter name before confirming. Once a counter is selected, the system automatically
loads the queue list for that counter.
```

**NEW:**
```
Note:
Only active counters appear in the selection list. Each counter is configured with
specific services defined by the administrator, and staff must verify the assigned
counter name before confirming. Once a counter is selected, the system automatically
loads the queue list for that counter.

IMPORTANT: The system now includes counter assignment protection. If another staff
member selects a counter at the same moment, one will receive an error message stating
"This counter was just taken by someone else." In this case, refresh the page and
select a different available counter.
```

---

## 8. QUEUE MANAGEMENT UPDATE (Section 9.0)

**Location:** Page 19

**UPDATE** the Statuses list:

**OLD:**
```
Statuses:
• Waiting – Ticket has been issued but not yet called.
• Called – Ticket has been announced and awaiting service.
• Serving – Transaction in progress.
• Completed – Service completed successfully.
• Skipped / Cancelled – Ticket manually handled by staff.
```

**NEW:**
```
Statuses:
• Waiting – Ticket has been issued but not yet called.
• Called – Ticket has been announced and awaiting service.
• Serving – Transaction in progress.
• Held – Ticket temporarily paused, will appear in Resume Hold panel.
• Completed – Service completed successfully.
• Skipped – Client did not respond when called.
• Cancelled – Ticket marked as invalid.
• Forwarded – Ticket transferred to another service (new in v1.2).

Note: Administrators can now delete queues directly from the Queue Management page.
When a queue is deleted, all connected monitors automatically update to remove the
ticket from display.
```

---

## 9. TROUBLESHOOTING UPDATE (Section 14.1)

**Location:** Page 28

**ADD** to Common Issues and Solutions:

```
Duplicate Ticket Numbers: This issue has been resolved in version 1.2. The system now
uses database-level locking to prevent duplicate numbers even when multiple kiosks
create tickets simultaneously. If you notice old duplicate entries, they are from
before the update and can be safely ignored.

Counter Already Taken: If you receive an error "This counter was just taken by someone
else" when selecting a counter, another staff member has already claimed that counter.
Refresh the page and select a different available counter.

Monitor Shows Duplicate Counters: This issue has been resolved in version 1.2. The
monitor now shows only one entry per counter in the "Now Serving" section. If you see
duplicates from older data, they will clear automatically at the next queue reset.

Queue Not Appearing After Admin Delete: Ensure the monitor is connected to the network.
When an admin deletes a queue, monitors should update automatically. If not, refresh
the monitor page.
```

---

## 10. TIPS AND BEST PRACTICES UPDATE (Section 14.2)

**Location:** Page 28

**ADD** the following tips:

```
• Use simple ticket numbers (no prefix) for better readability, especially for elderly
  clients. Configure this in Settings Management by leaving the Ticket Prefix field empty.

• When transferring/forwarding queues, always include a reason to help the receiving
  staff understand the client's needs.

• Before starting the day, ensure no stale "serving" queues exist from the previous
  day by checking the Queue Management page.

• The monitor now shows which services it displays in the header - use this to verify
  correct monitor configuration.
```

---

## 11. TABLE OF CONTENTS UPDATE

**Location:** Page 2-3

**ADD** new entry:

```
12.3 Forwarding/Transferring Queues.....................................................XX
```

---

## SUMMARY OF CHANGES FOR VERSION 1.2

| Feature | Description |
|---------|-------------|
| Simple Ticket Numbers | Tickets now display as simple numbers (1, 2, 3) instead of QUE-001 format |
| Queue Forward/Transfer | Staff can transfer tickets to different services |
| Services on Monitor | Monitor header shows which services are displayed |
| Counter Protection | Prevents multiple staff selecting same counter |
| Duplicate Prevention | Database locking prevents duplicate ticket numbers |
| Real-Time Admin Updates | Admin queue deletions reflect on monitors instantly |
| Improved Hold/Resume | Enhanced hold ticket workflow with reasons |

---

*Document prepared for QUEWIE Version 1.2 Update*
*February 2026*
