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