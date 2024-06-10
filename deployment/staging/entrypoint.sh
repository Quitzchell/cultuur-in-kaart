#!/bin/sh
set -e

# Ensure proper permissions for the working directory and database
chown -R www-data:www-data /var/www/html

# Run Laravel migrations and seed
php artisan migrate:fresh --seed --force

# Start supervisord to run Nginx and PHP-FPM
exec supervisord -c /etc/supervisord.conf
