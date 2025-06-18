#!/bin/bash

# Navigate to the app root
cd /home/site/wwwroot

# Run migrations or other commands if needed
# php artisan migrate --force

# Start Laravel with the correct root directory
php artisan serve --host=0.0.0.0 --port=8080