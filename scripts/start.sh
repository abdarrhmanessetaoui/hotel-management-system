#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel Deployment Script"

# 1. Map Railway Database Variables
# Priority: use Railway's injections, fallback to existing DB_*
export DB_HOST=${MYSQLHOST:-$DB_HOST}
export DB_PORT=${MYSQLPORT:-$DB_PORT}
export DB_DATABASE=${MYSQLDATABASE:-$DB_DATABASE}
export DB_USERNAME=${MYSQLUSER:-$DB_USERNAME}
export DB_PASSWORD=${MYSQLPASSWORD:-$DB_PASSWORD}

echo "✅ Environment variables mapped."
echo "▶ DB_HOST: $DB_HOST"

# 2. Permissions
echo "📂 Setting up permissions..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# 3. Laravel Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Start PHP-FPM
echo "🐘 Starting PHP-FPM..."
FPM_BIN=$(command -v php-fpm83 || command -v php-fpm)
$FPM_BIN -y /app/php-fpm.conf -D -R

# 5. Start Nginx with Port Injection (using sed for maximum compatibility)
echo "🌐 Starting Nginx on port $PORT..."

# Copy config to /tmp to ensure it's writable
cp /app/nginx.conf /tmp/nginx.conf

# Replace $PORT placeholder with actual port value
# We use '#' as a delimiter in sed to avoid issues with potential slashes
sed -i "s#\$PORT#$PORT#g" /tmp/nginx.conf

# Start Nginx in foreground
exec nginx -c /tmp/nginx.conf -g "daemon off;"