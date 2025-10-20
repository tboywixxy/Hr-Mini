# HR Mini (Laravel)

Simple REST API to manage employees with auth (Sanctum), roles, CRUD, and a total salary-per-department report.

## Requirements
- PHP 8.2+
- Composer
- MySQL/PostgreSQL/SQLite
- Laravel 10+ & Sanctum

## Setup (from Git)
```bash
git clone <your-repo-url> hr-mini
cd hr-mini
composer install
cp .env.example .env             
php artisan key:generate
php artisan migrate --seed       
php artisan serve                 
```

### Seeded Admin
- Email: `admin@example.com`
- Password: `Admin@12345`

## Auth Flow
1. `POST /api/login` → `{ token, user }`
2. Use `Authorization: Bearer <token>` on protected routes
3. `GET /api/me` to verify
4. `POST /api/logout` to revoke token

## Endpoints
**Auth**
- `POST /api/login` (public)
- `POST /api/logout` (auth)
- `GET /api/me` (auth)

**Employees**
- `GET /api/employees` (auth)
- `GET /api/employees/{id}` (auth)
- `POST /api/employees` (**admin**)
- `PUT /api/employees/{id}` (**admin**)
- `DELETE /api/employees/{id}` (**admin**)

**Reports**
- `GET /api/reports/total-salary-by-department` (**admin**)

Example:
```json
{ "Engineering": 300000, "HR": 90000 }
```

## Quick Test (PowerShell)
```powershell
$r = Invoke-RestMethod -Method POST -Uri "http://127.0.0.1:8000/api/login" `
  -Headers @{ Accept="application/json" } -ContentType "application/json" `
  -Body (@{ email="admin@example.com"; password="Admin@12345" } | ConvertTo-Json)
$TOKEN = $r.token

$emp = @{ name="Jane Doe"; email="jane@example.com"; position="Backend Dev"; salary=350000; department="Engineering" } | ConvertTo-Json
Invoke-RestMethod -Method POST -Uri "http://127.0.0.1:8000/api/employees" `
  -Headers @{ Authorization="Bearer $TOKEN"; Accept="application/json" } `
  -ContentType "application/json" -Body $emp

Invoke-RestMethod -Method GET -Uri "http://127.0.0.1:8000/api/reports/total-salary-by-department" `
  -Headers @{ Authorization="Bearer $TOKEN"; Accept="application/json" }
```

## Notes
- Roles: `admin` (full) vs `user` (read-only).
- 422 = validation errors (duplicate email, non‑numeric salary).
- 401 = not authenticated; 403 = not admin.

## Submission
Zip the project (or push to GitHub) and share the link as instructed.
