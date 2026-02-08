# Technical Task: Simple User Management CRUD

Laravel 12 backend with a Breeze-authenticated Vue dashboard.

## Tech Stack
- Backend: Laravel 12, PHP, SQLite (default), REST API
- Frontend: Vue 3 + Inertia (Breeze), Tailwind, Vite
- Tooling: Pint, PHPStan + Larastan

## Repository Structure
- Laravel application lives at the repository root

## Requirements
- PHP 8.2+
- Composer
- Node.js + npm

## Auth vs CRUD Users
- `backend_users` table powers the Laravel Breeze admin/auth area.
- `users` table is only for the CRUD list (created via the API/UI).
- This mirrors the Nova-style split: backend admins vs. managed users.
- The CRUD UI now lives inside the authenticated Dashboard.

## Architecture Overview
Backend follows a clean layering approach:
- **Controller**: Handles HTTP requests and responses only
- **Service**: Business logic and orchestration
- **Repository**: Data access and query logic
- **Form Requests**: Validation with consistent JSON errors
- **Resources**: Response shaping for API data

Dashboard UI (Breeze + Inertia) includes the CRUD:
- **Services**: Centralized API calls (`resources/js/Services/userService.js`)
- **Components**: Reusable UI (`resources/js/Components/Crud`)
- **Pages**: Dashboard (`resources/js/Pages/Dashboard.vue`)

## Setup
```powershell
git clone https://github.com/Julynard/technical-task-simple-user-management-crud.git
cd C:\Users\Admin\...\technical-task-simple-user-management-crud
composer install
cp .env.example .env
php artisan key:generate
```

SQLite file (already created in this project):
```
database/database.sqlite
```

Run migrations (required, seeds demo data):
```powershell
php artisan migrate --seed
```

Update `.env` for Sanctum + local host:
```
APP_URL=http://localhost:8090
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:8090,127.0.0.1,127.0.0.1:8090
```

Start the server:
```powershell
php -S localhost:90 -t public
```

Note: Port `90` may require elevated privileges and may already be in use. If it fails, use a higher port like `8091` and update `APP_URL`/`SANCTUM_STATEFUL_DOMAINS` accordingly.

For the admin UI assets:
```powershell
npm install
npm run dev
```

If you want to reset and re-seed:
```powershell
php artisan migrate:fresh --seed
```

## Working Features
- Laravel Breeze login for `backend_users`
- CRUD for `users` with pagination + search
- API token auth via `POST /api/auth/token`
- Sanctum-protected API routes (`auth:sanctum`)
- UI-only future ideas panel (3x3 cards)
- Create form dummy data generator

## API Endpoints
Base: `http://127.0.0.1:8090/api`

- `POST /auth/token` (issue API token)
- `POST /auth/logout` (revoke current token, requires `auth:sanctum`)
- `GET /users` (supports `?page=1&per_page=10&q=search`)
- `POST /users`
- `GET /users/{id}`
- `PUT /users/{id}`
- `DELETE /users/{id}`

### Token Auth (Postman)
1. Call `POST /api/auth/token` with email/password.
2. Use `Authorization: Bearer <token>` for `/api/users` requests.

The Postman collection includes `Auth - Token` and auto-saves the token into `api_token`.

### Sample POST Body
```json
{
  "first_name": "Ada",
  "last_name": "Lovelace",
  "company_name": "Analytical Engines Inc.",
  "email": "ada@example.com",
  "contact_number": "+1-555-0101",
  "description": "First programmer"
}
```

### Validation Error Format
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## API-Dashboard Interaction
- The dashboard calls `GET /api/users` for list/pagination/search.
- Create/update/delete routes are called from the dashboard UI.
- Validation errors (422) are mapped to specific form fields.
- Soft deletes are enabled; re-creating a deleted email restores the user and sets `restored_at`.

## Soft Delete Restore Behavior
If a user is soft-deleted and the same email is created again, the record is restored and `restored_at` is set.

## Quality Commands
```powershell
vendor\bin\pint
vendor\bin\phpstan analyse --memory-limit=2G
```

## Postman
Import the collection:
```
Postman-User-CRUD.postman_collection.json
```
