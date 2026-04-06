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

# Force SQLite database connection for this app (override any Railway environment defaults)
export DB_CONNECTION=sqlite
export DB_DATABASE="${DB_DATABASE:-database/database.sqlite}"
echo "✓ Database connection forced to SQLite at: ${DB_DATABASE}"

# Set safe defaults for session and cache to avoid database dependency
export SESSION_DRIVER="${SESSION_DRIVER:-file}"
export CACHE_STORE="${CACHE_STORE:-file}"
echo "Session and cache drivers set to: ${SESSION_DRIVER} / ${CACHE_STORE}"

# Configure SQLite database - ensure file exists and is writable
mkdir -p "$(dirname "$DB_DATABASE")"
touch "$DB_DATABASE"
chmod 666 "$DB_DATABASE"
echo "✓ SQLite database file ensured and writable"

if [[ -z "${APP_KEY:-}" ]]; then
  echo "ERROR: APP_KEY is missing. Set APP_KEY in Railway Variables before deploy."
  exit 1
fi

set -e  # Now exit on errors

# Optional toggles for deploy-time tasks.
RUN_MIGRATIONS="${RUN_MIGRATIONS:-true}"
RUN_SEEDER="${RUN_SEEDER:-false}"

# Skip PostgreSQL waiting - we're using SQLite
echo "SQLite will be initialized locally"

# Clear stale cache artifacts before warmup.
echo "Clearing config cache..."
php artisan optimize:clear 2>&1 || echo "⚠ optimize:clear had issues (may be OK on fresh install)"

# Make public storage available if app uses uploads.
echo "Setting up storage link..."
php artisan storage:link 2>&1 || echo "⚠ storage:link had issues (may already exist)"

# Run migrations if enabled
if [[ "${RUN_MIGRATIONS}" == "true" ]]; then
  echo "======= RUNNING MIGRATIONS ======="
  # First check if database is fresh or has migrations
  MIGRATION_COUNT=$(php artisan migrate:status 2>&1 | grep -c "^.*\|" || echo "0")
  
  if php artisan migrate --force 2>&1; then
    echo "✓ Migrations completed successfully"
  else
    MIGRATION_EXIT_CODE=$?
    echo "⚠ Migration exit code: $MIGRATION_EXIT_CODE (checking if app can still start)"
  fi
  
  # Verify apartments table has data
  APARTMENT_COUNT=$(php artisan tinker --execute="echo \App\Models\Apartment::count();" 2>/dev/null | tail -1 || echo "0")
  echo "Apartments in database: $APARTMENT_COUNT"
fi

# Run seeder if enabled (enable by default for fresh installs)  
if [[ "${RUN_SEEDER:-}" != "false" ]] || [[ "$APARTMENT_COUNT" == "0" ]]; then
  echo "Running seeders to populate data..."
  php artisan db:seed --class=ApartmentSeeder --force 2>&1 || echo "⚠ ApartmentSeeder already run or had issues"
  php artisan db:seed --force 2>&1 || echo "⚠ DatabaseSeeder had issues"
  echo "✓ Seeders completed"
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache 2>&1 || echo "⚠ config:cache had issues"
# Skip route caching - it can cause stale routes in production
echo "Skipping route cache to avoid stale routes"
php artisan view:cache 2>&1 || echo "⚠ view:cache had issues"

# Use the platform-provided PORT when available (Railway sets $PORT at runtime).
PORT="${PORT:-8080}"

echo "======= STARTING SERVER ======="
echo "Starting Laravel server on 0.0.0.0:${PORT}"

# Start Laravel using artisan serve
exec php artisan serve --host=0.0.0.0 --port="${PORT}" 2>&1
