#!/bin/sh
set -e

# Ensure proper permissions for the working directory
chown -R www-data:www-data /var/www/html

# Create the SQLite database file if it does not exist
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chmod 644 /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

# Run Laravel migrations and seed
php artisan migrate:fresh --seed --force

# Start supervisord to run Nginx and PHP-FPM
exec supervisord -c /etc/supervisord.conf
