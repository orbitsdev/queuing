# Kiosqueeing Documentation

## Table of Contents

| Document | Description |
|----------|-------------|
| [System Overview](system-overview.md) | Application architecture, modules, routes, and workflow |
| [Database](database.md) | Models, relationships, schema, and ERD |
| [API - Kiosk](api-kiosk.md) | Kiosk API endpoints for ticket creation |
| [Production Setup](production-setup.md) | Complete production deployment guide |

## Quick Links

- **Getting Started**: See [README.md](../README.md) in the project root
- **Development**: `composer run dev` + `php artisan reverb:start`
- **Production**: Follow [Production Setup](production-setup.md)

## Tech Stack

- Laravel 12 + Livewire 3 + Filament 3.3
- Laravel Reverb (WebSockets)
- TailwindCSS + Wire UI + Flux
- Pest (Testing)
