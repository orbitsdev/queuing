# User Manual Updates - Version 1.2

Updates organized by page order (top to bottom) for easy copy-paste into Word document.

---

## Page 1 - COVER PAGE

**Change:** Update version number

| OLD | NEW |
|-----|-----|
| Version 1.1 | Version 1.2 |

---

## Page 2-3 - TABLE OF CONTENTS

**ADD** new entry after "12.2 Serving Clients":

```
12.3 Forwarding/Transferring Queues.....................................................XX
```

---

## Page 4-5 - Section 1.5 System Features

**ADD** these new features to the existing bullet list:

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

## Page 17 - Section 8.0 Monitor Management

**ADD** this paragraph to the Note section:

```
NEW: The monitor header now displays which services are being shown on that specific
monitor. Service codes (e.g., BOBP, NBA, AT) appear in the header bar next to the
branch name and time. Hovering over a service code shows the full service name.
```

---

## Page 19 - Section 9.0 Queue Management

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
```

**ADD** to the Note section:

```
Administrators can now delete queues directly from the Queue Management page.
When a queue is deleted, all connected monitors automatically update to remove
the ticket from display.
```

---

## Page 21-22 - Section 11.0 Settings Management

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

## Page 23 - Section 12.1 Selecting Counter

**ADD** this paragraph to the Note section:

```
IMPORTANT: The system now includes counter assignment protection. If another staff
member selects a counter at the same moment, one will receive an error message stating
"This counter was just taken by someone else." In this case, refresh the page and
select a different available counter.
```

---

## Page 24-25 - Section 12.3 (NEW SECTION - Add after 12.2)

**ADD** this entire new section after "12.2 Serving Clients":

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

## Page 26-27 - Section 13.0 Monitor Setup & Display

**ADD** to the Note section:

```
NEW: The monitor header now shows the assigned services (e.g., "Services: [BOBP] [NBA]
[AT]") to help identify which queues are displayed on each screen.
```

---

## Page 28 - Section 14.1 Common Issues and Solutions

**ADD** these new troubleshooting items:

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

Queue Not Updating After Admin Delete: Ensure the monitor is connected to the network.
When an admin deletes a queue, monitors should update automatically. If not, refresh
the monitor page.
```

---

## Page 28 - Section 14.2 Tips and Best Practices

**ADD** these new tips:

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

## Page 29 - FOOTER

**Change:** Update the date

| OLD | NEW |
|-----|-----|
| Last updated: October 2025 | Last updated: February 2026 |

---

## SUMMARY TABLE

| Page | Section | Change |
|------|---------|--------|
| 1 | Cover | Version 1.1 → 1.2 |
| 2-3 | TOC | Add section 12.3 |
| 4-5 | 1.5 | Add 5 new features |
| 17 | 8.0 | Add services display note |
| 19 | 9.0 | Update statuses, add admin delete note |
| 21-22 | 11.0 | Update ticket prefix description |
| 23 | 12.1 | Add counter protection note |
| 24-25 | 12.3 | NEW section - Forward/Transfer |
| 26-27 | 13.0 | Add services header note |
| 28 | 14.1 | Add 4 troubleshooting items |
| 28 | 14.2 | Add 4 tips |
| 29 | Footer | October 2025 → February 2026 |

---

*QUEWIE Version 1.2 - February 2026*
