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
echo "▶ DB_PORT: $DB_PORT"

# 2. Wait for DB (Optional but helpful for stability)
if [ -n "$DB_HOST" ]; then
  echo "📡 Waiting for database at $DB_HOST:$DB_PORT..."
  # Simple timeout loop for DB connectivity
  for i in {1..30}; do
    if php -r "echo @fsockopen('$DB_HOST', $DB_PORT) ? 'connected' : '';" | grep -q 'connected'; then
      echo "✅ Database is reachable!"
      break
    fi
    echo "⌛ Waiting for DB... ($i/30)"
    sleep 2
  done
fi

# 3. Permissions and Directories
echo "📂 Setting up permissions..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 777 storage bootstrap/cache

# 4. Laravel Optimization
echo "⚡ Optimizing Laravel..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Start PHP-FPM
echo "🐘 Starting PHP-FPM..."
FPM_BIN=$(command -v php-fpm83 || command -v php-fpm)
$FPM_BIN -y /app/php-fpm.conf -D -R || (echo "❌ PHP-FPM failed to start" && exit 1)

# 6. Start Caddy
echo "🌐 Starting Caddy on port $PORT..."
# Using exec to replace the shell process
exec caddy run --config /app/Caddyfile --adapter caddyfile