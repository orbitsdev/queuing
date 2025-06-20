
# 📑 Kiosqueeing System — Kiosk Module API Documentation (.md)

## ✅ Purpose
This markdown file is ready to download and share with backend developers. It explains the kiosk module's API purpose, real-world use, data flow, and endpoint responsibilities.

## ✅ Scenario
- Kiosk installed in a branch.
- Identified by its unique branch code.
- Customers select services, receive tickets.
- Tickets sync to counters and monitor displays.

## ✅ Required Data
- Branch code: to link the kiosk.
- Service ID: selected by customer.

## ✅ API Endpoints
1. `POST /api/kiosk/check-branch` → validate branch code.
2. `GET /api/kiosk/branch` → get branch info for branding.
3. `GET /api/kiosk/services` → list active services.
4. `POST /api/kiosk/queue` → generate new ticket.

## ✅ Integration Purpose
- Clean, consistent, safe queue generation.
- Full sync with counter module.
- Real-time display on monitors.

**Use this markdown file as the official reference for implementing and maintaining the Kiosk Module API.**
