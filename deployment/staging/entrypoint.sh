#!/bin/sh
set -e

## Create directory for database
#mkdir -p /var/www/html/database
#
## Ensure proper permissions
#chown -R www-data:www-data /var/www/html/database

# Run Laravel migrations and seed
php artisan migrate:fresh --seed --force

# Run the command to start the application
exec "$@"
