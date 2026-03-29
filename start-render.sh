#!/usr/bin/env sh
set -e

php artisan migrate --force
php artisan db:seed --class=MonasteriesCsvSeeder --force
php artisan optimize:clear

exec php artisan serve --host=0.0.0.0 --port=10000