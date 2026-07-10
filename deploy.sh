#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")"

git pull origin main

composer install --no-dev --optimize-autoloader --no-interaction

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deploy complete."
