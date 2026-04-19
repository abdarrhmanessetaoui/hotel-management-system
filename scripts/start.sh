#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel Deployment Script"

# 1. Try to Parse MYSQL_URL if MYSQLHOST is missing (Robustness)
if [ -z "$MYSQLHOST" ] && [ -n "$MYSQL_URL" ]; then
    echo "🔗 Parsing MYSQL_URL for database connection details..."
    # mysql://root:pass@host:3306/db
    export DB_HOST=$(echo $MYSQL_URL | sed -e 's|mysql://.*@||' -e 's|:.*||')
    export DB_PORT=$(echo $MYSQL_URL | sed -e 's|.*:||' -e 's|/.*||')
    export DB_DATABASE=$(echo $MYSQL_URL | sed -e 's|.*/||')
    export DB_USERNAME=$(echo $MYSQL_URL | sed -e 's|mysql://||' -e 's|:.*||')
    # This might miss the password if it contains special chars, but better than nothing
    export DB_PASSWORD=$(echo $MYSQL_URL | sed -e 's|mysql://[^:]*:||' -e 's|@.*||')
fi

# 2. Map standard Railway Database Variables
export DB_HOST=${MYSQLHOST:-$DB_HOST}
export DB_PORT=${MYSQLPORT:-$DB_PORT}
export DB_DATABASE=${MYSQLDATABASE:-$DB_DATABASE}
export DB_USERNAME=${MYSQLUSER:-$DB_USERNAME}
export DB_PASSWORD=${MYSQLPASSWORD:-$DB_PASSWORD}

echo "✅ Environment variables mapped."
echo "▶ DB_HOST: $DB_HOST"
echo "▶ DB_PORT: $DB_PORT"
echo "▶ DB_DATABASE: $DB_DATABASE"

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
cp /app/nginx.conf /tmp/nginx.conf
sed -i "s#\$PORT#$PORT#g" /tmp/nginx.conf
exec nginx -c /tmp/nginx.conf -g "daemon off;"