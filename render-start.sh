#!/usr/bin/env bash
set -e

php artisan migrate --force
php artisan db:seed --class=MonasteriesCsvSeeder --force
php artisan optimize:clear

php artisan serve --host=0.0.0.0 --port=${PORT:-10000}