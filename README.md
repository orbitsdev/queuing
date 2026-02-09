# Kiosqueeing - Queue Management System

A comprehensive queue management system for managing queues across multiple branches with real-time updates.

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (default) or MySQL/PostgreSQL

## Installation

### 1. Clone and Install Dependencies

```bash
git clone <repository-url>
cd queuing

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

```bash
# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### 4. Configure Reverb (Real-time WebSocket)

Add these to your `.env` file for real-time updates:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Running the Application

### Development (Recommended)

Run all services with a single command:

```bash
composer run dev
```

This starts concurrently:
- **Laravel server** - http://localhost:8000
- **Queue worker** - Processes background jobs
- **Vite dev server** - Frontend hot reload

Then in a **separate terminal**, start the WebSocket server:

```bash
php artisan reverb:start
```

### All 4 Services (Full Setup)

For full functionality, you need these 4 services running:

| Terminal | Command | Purpose |
|----------|---------|---------|
| 1 | `php artisan serve` | Laravel server (port 8000) |
| 2 | `php artisan queue:listen` | Queue worker for background jobs |
| 3 | `npm run dev` | Vite frontend dev server |
| 4 | `php artisan reverb:start` | WebSocket server (port 8080) |

Or use `composer run dev` (runs terminals 1-3) + `php artisan reverb:start` separately.

### Why Each Service?

- **Queue Worker**: Processes queued jobs like broadcasting events, sending notifications
- **Reverb WebSocket**: Enables real-time updates on monitor displays and admin dashboard
- **Vite**: Hot-reloads frontend assets during development

## Production Setup

### 1. Environment Configuration

```bash
cp .env.example .env
```

Edit `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (MySQL recommended for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kiosqueeing
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Queue (database or redis)
QUEUE_CONNECTION=database

# Reverb WebSocket
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=your-domain.com
REVERB_PORT=8080
REVERB_SCHEME=https
```

### 2. Install & Build

```bash
# Install dependencies (no dev)
composer install --no-dev --optimize-autoloader
npm install

# Build frontend assets
npm run build

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force
```

### 3. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
```

### 4. Run Background Services

Use a process manager like **Supervisor** to keep services running:

**Queue Worker** (`/etc/supervisor/conf.d/queue-worker.conf`):
```ini
[program:queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/queue.log
```

**Reverb WebSocket** (`/etc/supervisor/conf.d/reverb.conf`):
```ini
[program:reverb]
command=php /path/to/project/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/reverb.log
```

Then reload Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

### 5. Web Server (Nginx Example)

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6. Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## User Roles

| Role | Access |
|------|--------|
| **Superadmin** | Full system access, manage all branches |
| **Admin** | Branch-specific administration |
| **Staff** | Counter operations, queue processing |

## Application URLs

| URL | Description |
|-----|-------------|
| `/` | Home page |
| `/login` | Login page |
| `/dashboard` | Redirects based on role |
| `/admin/dashboard` | Admin dashboard |
| `/counter/select` | Staff counter selection |
| `/counter/transaction` | Staff queue processing |
| `/display/{monitor_id}` | Public monitor display |

## Testing

```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php
```

## Code Style

```bash
# Fix PHP code style
./vendor/bin/pint
```

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Livewire 3, Volt, Flux, Wire UI, TailwindCSS
- **Admin UI**: Filament 3.3
- **Real-time**: Laravel Reverb (WebSockets)
- **Build Tool**: Vite 6
- **Testing**: Pest

## License

MIT
