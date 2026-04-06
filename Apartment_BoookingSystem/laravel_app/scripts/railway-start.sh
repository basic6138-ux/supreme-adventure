#!/bin/bash

set +e  # Don't exit on errors yet

cd "$(dirname "$0")/.."

echo "======= DEPLOYMENT START ======="
echo "Current directory: $(pwd)"
echo "APP_KEY: ${APP_KEY:-NOT_SET}"
echo "PORT: ${PORT:-8080}"
echo "DB_CONNECTION: ${DB_CONNECTION:-NOT_SET}"
echo "APP_ENV: ${APP_ENV:-NOT_SET}"
echo "APP_DEBUG: ${APP_DEBUG:-NOT_SET}"
echo "SESSION_DRIVER: ${SESSION_DRIVER:-NOT_SET}"
echo "CACHE_STORE: ${CACHE_STORE:-NOT_SET}"

# Set safe defaults for session and cache to avoid database dependency
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"
echo "Session and cache drivers set to: ${SESSION_DRIVER} / ${CACHE_STORE}"

# Configure SQLite database if using SQLite
if [[ "${DB_CONNECTION:-}" == "sqlite" ]] || [[ -z "${DB_CONNECTION:-}" ]]; then
  DB_DATABASE="${DB_DATABASE:-database/database.sqlite}"
  export DB_DATABASE
  echo "Using SQLite database at: $DB_DATABASE"
  mkdir -p "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
  chmod 666 "$DB_DATABASE"
  echo "✓ SQLite database file ensured and writable"
fi

if [[ -z "${APP_KEY:-}" ]]; then
  echo "ERROR: APP_KEY is missing. Set APP_KEY in Railway Variables before deploy."
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
php artisan optimize:clear 2>&1 || echo "⚠ optimize:clear had issues (may be OK on fresh install)"

# Make public storage available if app uses uploads.
echo "Setting up storage link..."
php artisan storage:link 2>&1 || echo "⚠ storage:link had issues (may already exist)"

# Run migrations if enabled
if [[ "${RUN_MIGRATIONS}" == "true" ]]; then
  echo "======= RUNNING MIGRATIONS ======="
  if php artisan migrate --force 2>&1; then
    echo "✓ Migrations completed successfully"
  else
    MIGRATION_EXIT_CODE=$?
    echo "⚠ Migration exit code: $MIGRATION_EXIT_CODE (checking if app can still start)"
  fi
fi

# Run seeder if enabled (enable by default for fresh installs)
if [[ "${RUN_SEEDER:-}" != "false" ]]; then
  echo "Running seeders..."
  php artisan db:seed --class=ApartmentSeeder --force 2>&1 || echo "⚠ ApartmentSeeder already run or had issues"
  php artisan db:seed --force 2>&1 || echo "⚠ DatabaseSeeder had issues"
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache 2>&1 || echo "⚠ config:cache had issues"
php artisan route:cache 2>&1 || echo "⚠ route:cache had issues"
php artisan view:cache 2>&1 || echo "⚠ view:cache had issues"

# Use the platform-provided PORT when available (Railway sets $PORT at runtime).
PORT="${PORT:-8080}"

echo "======= STARTING SERVER ======="
echo "Starting Laravel server on 0.0.0.0:${PORT}"

# Start Laravel using artisan serve
exec php artisan serve --host=0.0.0.0 --port="${PORT}" 2>&1
