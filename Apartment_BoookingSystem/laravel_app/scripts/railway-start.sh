#!/bin/bash

set +e  # Don't exit on errors yet

cd "$(dirname "$0")/.."

echo "Current directory: $(pwd)"
echo "APP_KEY: ${APP_KEY:-NOT_SET}"
echo "PORT: ${PORT:-8080}"
echo "DB_CONNECTION: ${DB_CONNECTION:-NOT_SET}"

if [[ -z "${APP_KEY:-}" ]]; then
  echo "APP_KEY is missing. Set APP_KEY in Railway Variables before deploy."
  exit 1
fi

set -e  # Now exit on errors

# Optional toggles for deploy-time tasks.
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
RUN_SEEDER="${RUN_SEEDER:-false}"

# If using PostgreSQL, wait for the DB host/port to be reachable before running
# any Artisan commands that may touch the database (cache clearing, migrations).
if [[ "${DB_CONNECTION:-}" == "pgsql" ]]; then
  DB_HOST="${DB_HOST:-127.0.0.1}"
  DB_PORT="${DB_PORT:-5432}"
  WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-60}"
  echo "DB_CONNECTION=pgsql detected; waiting up to ${WAIT_TIMEOUT}s for ${DB_HOST}:${DB_PORT}..."
  for i in $(seq 1 "${WAIT_TIMEOUT}"); do
    if (echo > /dev/tcp/${DB_HOST}/${DB_PORT}) >/dev/null 2>&1; then
      echo "Postgres is reachable at ${DB_HOST}:${DB_PORT} (after ${i}s)"
      DB_READY=1
      break
    fi
    sleep 1
  done
  if [[ -z "${DB_READY:-}" ]]; then
    echo "Timeout warning: Postgres may not be ready at ${DB_HOST}:${DB_PORT}, continuing anyway..."
  fi
fi

# Clear stale cache artifacts before warmup.
echo "Clearing config cache..."
php artisan optimize:clear || true

# Make public storage available if app uses uploads.
echo "Setting up storage link..."
php artisan storage:link || true

# Run migrations if enabled
if [[ "${RUN_MIGRATIONS}" == "true" ]]; then
  echo "Running migrations..."
  if php artisan migrate --force; then
    echo "✓ Migrations completed successfully"
  else
    echo "⚠ Migration completed with status (may be OK if no pending migrations)"
  fi
fi

# Run seeder if enabled
if [[ "${RUN_SEEDER}" == "true" ]]; then
  echo "Running seeder..."
  php artisan db:seed --class=ApartmentSeeder --force || {
    echo "Seeder warning: Could not run seeder"
  }
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Use the platform-provided PORT when available (Railway sets $PORT at runtime).
PORT="${PORT:-8080}"

# Ensure database file exists for SQLite
if [[ "${DB_CONNECTION:-}" == "sqlite" ]]; then
  DB_DATABASE="${DB_DATABASE:-database/database.sqlite}"
  mkdir -p "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
  chmod 666 "$DB_DATABASE"
  echo "SQLite database ensured at $DB_DATABASE"
fi

echo "Starting Laravel server on port ${PORT}..."
echo "Listening on all interfaces (0.0.0.0:${PORT})"

# Start Laravel using artisan serve
exec php artisan serve --host=0.0.0.0 --port="${PORT}" 2>&1
