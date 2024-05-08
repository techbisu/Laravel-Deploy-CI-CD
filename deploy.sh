#!/bin/sh

# activate maintenance mode
#php artisan down

# update source code
git pull origin main

# clear cache
php artisan cache:clear

# clear config cache
php artisan config:clear

# cache config 
php artisan config:cache

# restart queues 
php artisan -v queue:restart

# stop maintenance mode
#php artisan up