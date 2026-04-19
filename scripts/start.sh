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
echo "▶ DB_HOST (pre-check): $DB_HOST"

# 2. Safety check for DB_HOST (Handle cases where Railway hasn't expanded placeholders)
if [[ "$DB_HOST" == *'${'* ]]; then
    echo "⚠️ WARNING: DB_HOST contains an unexpanded placeholder: $DB_HOST"
    echo "Checking if we can resolve it manually..."
    # If DB_HOST is '${MYSQLHOST}', we try to get the value of MYSQLHOST
    VAR_NAME=$(echo $DB_HOST | sed -e 's/\${//' -e 's/}//')
    if [ -n "${!VAR_NAME}" ]; then
        export DB_HOST="${!VAR_NAME}"
        echo "✅ Resolved DB_HOST to: $DB_HOST"
    fi
fi

# 3. Permissions
echo "📂 Setting up permissions..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# 4. Laravel Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start PHP-FPM
echo "🐘 Starting PHP-FPM..."
FPM_BIN=$(command -v php-fpm83 || command -v php-fpm)
$FPM_BIN -y /app/php-fpm.conf -D -R

# 6. Start Nginx with Port Injection
echo "🌐 Starting Nginx on port $PORT..."
# Replace $PORT placeholder in nginx.conf
envsubst '$PORT' < /app/nginx.conf > /tmp/nginx.conf

# Start Nginx in foreground
exec nginx -c /tmp/nginx.conf -g "daemon off;"