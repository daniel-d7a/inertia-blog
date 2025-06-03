#!/bin/bash

# Start Apache in the background
service apache2 start

composer install --no-interaction --optimize-autoloader --no-dev

npm install
# Start Vite development server in the background
# (npm run dev) &

# Start Laravel development server in the background
(php artisan serve) &

# Keep the container running
tail -f /dev/null