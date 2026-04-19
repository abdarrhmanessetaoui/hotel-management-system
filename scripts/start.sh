#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel Deployment Script"

# 1. Aggressive variable expansion
# Some Railway environments pass literals like '${MYSQLHOST}' instead of the value.
# We manually resolve these here.
resolve_var() {
    local var_val=$1
    if [[ "$var_val" == *'${'* ]]; then
        # Handle both ${VAR} and ${{VAR}}
        local clean_name=$(echo "$var_val" | sed -e 's/\${\+//' -e 's/}\+//')
        echo "${!clean_name}"
    else
        echo "$var_val"
    fi
}

# Map and Resolve
export DB_HOST=$(resolve_var "${MYSQLHOST:-$DB_HOST}")
export DB_PORT=$(resolve_var "${MYSQLPORT:-$DB_PORT}")
export DB_DATABASE=$(resolve_var "${MYSQLDATABASE:-$DB_DATABASE}")
export DB_USERNAME=$(resolve_var "${MYSQLUSER:-$DB_USERNAME}")
export DB_PASSWORD=$(resolve_var "${MYSQLPASSWORD:-$DB_PASSWORD}")

echo "✅ Environment variables mapped and resolved."
echo "▶ DB_HOST: $DB_HOST"
echo "▶ DB_PORT: $DB_PORT"
echo "▶ DB_DATABASE: $DB_DATABASE"

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

# 5. Start Nginx with Port Injection
echo "🌐 Starting Nginx on port $PORT..."
cp /app/nginx.conf /tmp/nginx.conf
sed -i "s#\$PORT#$PORT#g" /tmp/nginx.conf
exec nginx -c /tmp/nginx.conf -g "daemon off;"