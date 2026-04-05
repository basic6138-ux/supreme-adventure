#!/usr/bin/env bash

set -euo pipefail

cd "$(dirname "$0")/.."

if [[ -z "${APP_KEY:-}" ]]; then
  echo "APP_KEY is missing. Set APP_KEY in Railway Variables before deploy."
  exit 1
fi

# Optional toggles for deploy-time tasks.
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
RUN_SEEDER="${RUN_SEEDER:-false}"

# Clear stale cache artifacts before warmup.
php artisan optimize:clear

# Make public storage available if app uses uploads.
php artisan storage:link || true

if [[ "${RUN_MIGRATIONS}" == "true" ]]; then
  php artisan migrate --force
fi

if [[ "${RUN_SEEDER}" == "true" ]]; then
  php artisan db:seed --class=ApartmentSeeder --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
