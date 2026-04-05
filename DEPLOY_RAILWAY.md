Railway deployment notes

1. This repository contains the Laravel app under `Apartment_BoookingSystem/laravel_app`.

2. Railway configuration:
   - Top-level `railway.json` builds from `Apartment_BoookingSystem/laravel_app`.
   - Start command runs `bash scripts/railway-start.sh` in that directory.

3. Environment variables required in Railway project settings:
   - `APP_KEY` (generate with `php artisan key:generate --show` locally)
   - `DB_CONNECTION` (e.g., `sqlite` or `pgsql`)
   - If using SQLite, set `DB_DATABASE` to a writable path and ensure migrations run at deploy.
   - `PORT` (Railway will provide a default; script uses `${PORT:-8080}`)

4. Optional toggles (set as Railway variables):
   - `RUN_MIGRATIONS` (default `true`)
   - `RUN_SEEDER` (default `false`)

5. If you prefer Docker, add a `Dockerfile` at repo root pointing into `Apartment_BoookingSystem/laravel_app`.

6. After creating the Railway project, set the `railway.json` at the repository root and enable deploys from the `main` branch.
