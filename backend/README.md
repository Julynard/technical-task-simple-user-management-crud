# Backend (Laravel API)

Laravel 12 API for user management with clean layering (Controller → Service → Repository).

## Requirements
- PHP 8.2+
- Composer

## Setup
```powershell
cd C:\Users\Admin\Desktop\technical-task-simple-user-management-crud\backend
```

Create SQLite file (already created in this project):
```
database/database.sqlite
```

Run migrations:
```powershell
php artisan migrate
```

Start API server:
```powershell
php -S 127.0.0.1:8090 -t public
```

## API
Base URL: `http://127.0.0.1:8090/api`

- `GET /users` (supports `?page=1&per_page=10&q=search`)
- `POST /users`
- `GET /users/{id}`
- `PUT /users/{id}`
- `DELETE /users/{id}`

## Validation Errors
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## Soft Delete Restore Behavior
If a user is soft-deleted and the same email is created again, the record is restored and `restored_at` is set.

## Quality Commands
```powershell
vendor\bin\pint
vendor\bin\phpstan analyse --memory-limit=2G
```
