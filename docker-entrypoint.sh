#!/bin/bash
set -e

# Railway provides dynamic $PORT
PORT=${PORT:-80}
echo "Starting web server on port ${PORT}..."

# Configure Apache to listen on Railway's $PORT
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/*.conf 2>/dev/null || true

# Ensure storage directories exist with write permissions
mkdir -p /var/www/html/storage/logs /var/www/html/storage/cache /var/www/html/storage/sessions
chmod -R 777 /var/www/html/storage

# Run database setup & seeding in background or safely
echo "Ensuring database tables..."
php database/seed.php || echo "Database seed completed or skipped."

# Make sure only one Apache MPM is loaded (prefork)
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*
a2enmod mpm_prefork >/dev/null 2>&1 || true

# Start Apache in foreground
exec apache2-foreground
