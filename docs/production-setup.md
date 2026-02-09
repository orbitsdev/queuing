# Production Setup Guide

This guide covers deploying Kiosqueeing to a production environment.

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or PostgreSQL (recommended for production)
- Nginx or Apache
- Supervisor (for background processes)
- SSL certificate (recommended)

## 1. Server Preparation

### Install PHP Extensions

```bash
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd
```

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

## 2. Application Setup

### Clone Repository

```bash
cd /var/www
git clone <repository-url> kiosqueeing
cd kiosqueeing
```

### Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for production:

```env
APP_NAME=Kiosqueeing
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kiosqueeing
DB_USERNAME=kiosqueeing_user
DB_PASSWORD=secure_password_here

# Queue
QUEUE_CONNECTION=database

# Cache & Session
CACHE_STORE=database
SESSION_DRIVER=database

# Reverb WebSocket
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=kiosqueeing-production
REVERB_APP_KEY=your-secure-key-here
REVERB_APP_SECRET=your-secure-secret-here
REVERB_HOST=your-domain.com
REVERB_PORT=8080
REVERB_SCHEME=https

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE kiosqueeing;"
mysql -u root -p -e "CREATE USER 'kiosqueeing_user'@'localhost' IDENTIFIED BY 'secure_password_here';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON kiosqueeing.* TO 'kiosqueeing_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

# Run migrations
php artisan migrate --force
```

### Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
```

### Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/kiosqueeing
sudo chmod -R 755 /var/www/kiosqueeing
sudo chmod -R 775 /var/www/kiosqueeing/storage
sudo chmod -R 775 /var/www/kiosqueeing/bootstrap/cache
```

## 3. Web Server Configuration

### Nginx

Create `/etc/nginx/sites-available/kiosqueeing`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com;
    root /var/www/kiosqueeing/public;

    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/kiosqueeing /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

## 4. Background Services

### Supervisor Configuration

Install Supervisor:

```bash
sudo apt install supervisor
```

#### Queue Worker

Create `/etc/supervisor/conf.d/kiosqueeing-queue.conf`:

```ini
[program:kiosqueeing-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/kiosqueeing/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/kiosqueeing/storage/logs/queue.log
stopwaitsecs=3600
```

#### Reverb WebSocket Server

Create `/etc/supervisor/conf.d/kiosqueeing-reverb.conf`:

```ini
[program:kiosqueeing-reverb]
process_name=%(program_name)s
command=php /var/www/kiosqueeing/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/kiosqueeing/storage/logs/reverb.log
stopwaitsecs=3600
```

#### Start Services

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

#### Check Status

```bash
sudo supervisorctl status
```

Expected output:
```
kiosqueeing-queue:kiosqueeing-queue_00   RUNNING   pid 12345, uptime 0:01:00
kiosqueeing-queue:kiosqueeing-queue_01   RUNNING   pid 12346, uptime 0:01:00
kiosqueeing-reverb                       RUNNING   pid 12347, uptime 0:01:00
```

## 5. WebSocket Proxy (Optional)

If you want to proxy WebSocket through Nginx on port 443:

Add to your Nginx server block:

```nginx
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_read_timeout 60s;
    proxy_send_timeout 60s;
}
```

Then update `.env`:
```env
REVERB_PORT=443
VITE_REVERB_PORT=443
```

## 6. Scheduled Tasks (Cron)

Add Laravel scheduler to cron:

```bash
sudo crontab -e -u www-data
```

Add this line:
```
* * * * * cd /var/www/kiosqueeing && php artisan schedule:run >> /dev/null 2>&1
```

## 7. Firewall

```bash
sudo ufw allow 80
sudo ufw allow 443
sudo ufw allow 8080  # Only if not proxying WebSocket through Nginx
sudo ufw enable
```

## 8. Monitoring & Logs

### View Logs

```bash
# Laravel logs
tail -f /var/www/kiosqueeing/storage/logs/laravel.log

# Queue worker logs
tail -f /var/www/kiosqueeing/storage/logs/queue.log

# Reverb logs
tail -f /var/www/kiosqueeing/storage/logs/reverb.log

# Nginx access logs
tail -f /var/log/nginx/access.log

# Nginx error logs
tail -f /var/log/nginx/error.log
```

### Restart Services

```bash
# Restart queue workers (after code updates)
sudo supervisorctl restart kiosqueeing-queue:*

# Restart Reverb
sudo supervisorctl restart kiosqueeing-reverb

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Restart Nginx
sudo systemctl restart nginx
```

## 9. Deployment Updates

When deploying updates:

```bash
cd /var/www/kiosqueeing

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build frontend assets
npm install
npm run build

# Restart queue workers
sudo supervisorctl restart kiosqueeing-queue:*
```

## 10. Backup

### Database Backup

```bash
# Manual backup
mysqldump -u kiosqueeing_user -p kiosqueeing > backup_$(date +%Y%m%d).sql

# Automated daily backup (add to cron)
0 2 * * * mysqldump -u kiosqueeing_user -pYOUR_PASSWORD kiosqueeing > /var/backups/kiosqueeing_$(date +\%Y\%m\%d).sql
```

### Application Backup

```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz /var/www/kiosqueeing/storage
```

## Troubleshooting

### Queue Not Processing

```bash
# Check supervisor status
sudo supervisorctl status

# Restart queue
sudo supervisorctl restart kiosqueeing-queue:*

# Check logs
tail -f /var/www/kiosqueeing/storage/logs/queue.log
```

### WebSocket Not Connecting

```bash
# Check if Reverb is running
sudo supervisorctl status kiosqueeing-reverb

# Check Reverb logs
tail -f /var/www/kiosqueeing/storage/logs/reverb.log

# Test WebSocket port
curl -I http://127.0.0.1:8080
```

### Permission Issues

```bash
sudo chown -R www-data:www-data /var/www/kiosqueeing
sudo chmod -R 775 /var/www/kiosqueeing/storage
sudo chmod -R 775 /var/www/kiosqueeing/bootstrap/cache
```

### Clear All Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
