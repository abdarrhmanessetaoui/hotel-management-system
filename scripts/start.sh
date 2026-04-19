#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel Deployment Script"

# 1. Aggressive variable expansion
resolve_var() {
    local var_val=$1
    if [[ "$var_val" == *'${'* ]]; then
        local clean_name=$(echo "$var_val" | sed -e 's/\${\+//' -e 's/}\+//')
        echo "${!clean_name}"
    else
        echo "$var_val"
    fi
}

export DB_HOST=$(resolve_var "${MYSQLHOST:-$DB_HOST}")
export DB_PORT=$(resolve_var "${MYSQLPORT:-$DB_PORT}")
export DB_DATABASE=$(resolve_var "${MYSQLDATABASE:-$DB_DATABASE}")
export DB_USERNAME=$(resolve_var "${MYSQLUSER:-$DB_USERNAME}")
export DB_PASSWORD=$(resolve_var "${MYSQLPASSWORD:-$DB_PASSWORD}")

echo "✅ Variables mapped: $DB_HOST / $DB_DATABASE"

# 2. Permissions
echo "📂 Setting up permissions..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# 3. DATABASE MIGRATIONS (The Fix)
echo "📂 Running Database Migrations..."
php artisan migrate --force

# 4. Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start PHP-FPM
echo "🐘 Starting PHP-FPM..."
FPM_BIN=$(command -v php-fpm83 || command -v php-fpm)
$FPM_BIN -y /app/php-fpm.conf -D -R

# 6. Start Nginx
echo "🌐 Starting Nginx on port $PORT..."
cp /app/nginx.conf /tmp/nginx.conf
sed -i "s#\$PORT#$PORT#g" /tmp/nginx.conf
exec nginx -c /tmp/nginx.conf -g "daemon off;"