#!/bin/bash

cd /home/site/wwwroot

# PHP dependencies
composer install --no-dev --optimize-autoloader

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan view:cache

# File permissions (optional, but recommended)
chmod -R 775 storage bootstrap/cache

# Run PHP's built-in server from the public directory
php -S 0.0.0.0:8080 -t public