# Apartment Booking System (Laravel)

This is a simple Apartment Booking System built with Laravel (Blade + Tailwind). It includes models, migrations, controllers, views, and seeders to demonstrate apartment browsing and booking flows.

Quick setup (development):

1. Install dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

2. Set your database in `.env` (SQLite is easiest for quick testing):

```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

Create the sqlite file:

```bash
touch database/database.sqlite
```

3. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

4. Install Laravel Breeze for authentication (recommended)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```

5. Run the app

```bash
php artisan serve
```

Default seeded users:

- Admin: admin@example.com / password
- Student: student@example.com / password

Notes:
- This repository contains core scaffolding: models, migrations, controllers, Blade views, and seeders. After installing Breeze, the auth routes and views will be available.
- Customize styling and assets in `resources/views/layouts/app.blade.php` and Tailwind.
# Apartment_BoookingSystem

## Railway Deployment (2026)

This project is configured for Railway with `railway.json` and `scripts/railway-start.sh`.

### 1) Push latest code to GitHub

```bash
git add -A
git commit -m "Prepare Railway deployment"
git push origin main
```

### 2) Create Railway project

1. Open Railway dashboard.
2. New Project -> Deploy from GitHub Repo.
3. Select this repository and set Root Directory to `laravel_app`.

### 3) Add a database

1. In the same Railway project, add a MySQL service.
2. Railway will provide connection variables.

### 4) Set required Variables in the Laravel service

Set these in Railway Variables tab:

- `APP_NAME=Apartment Booking System`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<your-railway-domain>`
- `APP_KEY=base64:...` (generate locally with `php artisan key:generate --show`)
- `LOG_CHANNEL=stack`
- `LOG_LEVEL=info`
- `DB_CONNECTION=mysql`
- `DB_HOST=<from railway mysql>`
- `DB_PORT=<from railway mysql>`
- `DB_DATABASE=<from railway mysql>`
- `DB_USERNAME=<from railway mysql>`
- `DB_PASSWORD=<from railway mysql>`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- `RUN_MIGRATIONS=true`

### 5) Deploy

Railway will automatically build and start using:

- Build command from `railway.json`
- Start command `bash scripts/railway-start.sh`

### 6) First release checks

After deploy is healthy:

1. Open your Railway public URL.
2. Register a user and login.
3. Verify apartment listing and image loading.
4. Confirm admin login works with seeded admin only if seeding is run manually.

### Optional: Seed production sample data once

Use Railway shell/CLI for one-time seed (recommended only for demo projects):

```bash
php artisan db:seed --force
```
