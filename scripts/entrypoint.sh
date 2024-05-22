#!/bin/sh
# entrypoint.sh

set -e

echo "Starting entrypoint script..."

# Wait for the database to be ready
# echo "Waiting for MySQL to be ready..."
# /scripts/wait-for-db.sh mysql:3306 -- echo "MySQL is up - executing command"

# echo "MySQL is available, proceeding..."

# Fix permissions
echo "Fixing permissions..."
/scripts/fix-permissions.sh

# Run migrations
echo "Running migrations..."
php artisan migrate

# Start PHP-FPM
echo "Starting PHP-FPM..."
exec php-fpm
