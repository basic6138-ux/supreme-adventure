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

# Use the platform-provided PORT when available (Railway sets $PORT at runtime).
PORT="${PORT:-8080}"

echo "Starting Laravel dev server on port ${PORT}"

# Start the server in the background so we can poll the /up health endpoint.
php artisan serve --host=0.0.0.0 --port="${PORT}" &
SERVER_PID=$!

# Wait for the app to respond on /up (timeout defaults to 120s, configurable via HEALTHCHECK_TIMEOUT)
HEALTHCHECK_TIMEOUT="${HEALTHCHECK_TIMEOUT:-120}"
echo "Waiting up to ${HEALTHCHECK_TIMEOUT}s for /up to return 200..."
for i in $(seq 1 "${HEALTHCHECK_TIMEOUT}"); do
  if curl -sSf "http://127.0.0.1:${PORT}/up" >/dev/null 2>&1; then
    echo "Healthcheck succeeded after ${i}s"
    break
  fi
  sleep 1
done

if ! kill -0 "${SERVER_PID}" >/dev/null 2>&1; then
  echo "Server process exited unexpectedly. Showing last 200 lines of storage/logs/laravel.log:"
  tail -n 200 storage/logs/laravel.log || true
  exit 1
fi

# Wait on the server process so the container keeps running with the server in foreground.
wait "${SERVER_PID}"
