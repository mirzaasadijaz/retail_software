#!/bin/bash
set -e

# Cache generate karein
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations chalayein
php artisan migrate --force

# Nginx aur PHP-FPM start karein (yeh base image ka default command hai)
/usr/bin/run.sh

