#!/bin/sh
set -e

# Run Laravel migrations and seed
php artisan migrate:fresh --seed --force

# Run the command to start the application
exec "$@"
