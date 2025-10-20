# HR Mini (Laravel API)

Simple HR API:
- Auth (Sanctum token)
- Employees CRUD (admin only for write)
- Report: total salary by department

## Stack
- PHP 8.2+
- Laravel 10/11
- Sanctum
- SQLite (local)

## Setup

```bash
composer install
cp .env.example .env
# Edit .env for SQLite:
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/project/database/database.sqlite

php artisan key:generate
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# Ensure only ONE personal_access_tokens migration exists

# Create sqlite file
# Windows PowerShell:
ni .\database\database.sqlite -ItemType File -Force

php artisan migrate --seed
php artisan serve
