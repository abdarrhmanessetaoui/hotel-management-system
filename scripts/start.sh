#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel Deployment Script"

# 1. Map Railway Database Variables
# These prioritize Railway's injected variables if they exist
export DB_HOST=${MYSQLHOST:-$DB_HOST}
export DB_PORT=${MYSQLPORT:-$DB_PORT}
export DB_DATABASE=${MYSQLDATABASE:-$DB_DATABASE}
export DB_USERNAME=${MYSQLUSER:-$DB_USERNAME}
export DB_PASSWORD=${MYSQLPASSWORD:-$DB_PASSWORD}

echo "✅ Database environment variables mapped."

# 2. Fix Permissions
echo "📂 Setting up permissions..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 3. Laravel Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Start PHP-FPM with custom config
echo "🐘 Starting PHP-FPM..."
FPM_BIN=$(command -v php-fpm83 || command -v php-fpm)
$FPM_BIN -y /app/php-fpm.conf -D -R

# 5. Start Caddy
echo "🌐 Starting Caddy on port $PORT..."
exec caddy run --config /app/Caddyfile --adapter caddyfile