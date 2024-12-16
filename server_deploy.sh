#!/bin/sh
set -e
 
echo "Deploying application ..."
 
# Enter maintenance mode
(php artisan down --message 'The app is being (quickly!) updated. Please try again in a minute.') || true
    # Update codebase
    git fetch origin deploy
    git reset --hard origin/deploy
 
    # Install dependencies based on lock file
    composer install --no-interaction --prefer-dist --optimize-autoloader
 
    # Note: If you're using queue workers, this is the place to restart them.
    # ...
 
    php artisan migrate

    # Clear cache
    php artisan optimize

    chmod 777 server_deploy.sh
    chmod -R 777 storage
    chmod -R 777 bootstrap/cache
 
# Exit maintenance mode
php artisan up
 
echo "Application deployed!"