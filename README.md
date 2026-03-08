# Laravel Starter

A practical Laravel 10 starter template with:
- Jetstream + Fortify authentication
- Role-based authorization (enum + gates + policies)
- Service layer + DTO pattern for domain logic
- Docker-based local environment
- Feature and unit test examples
- Structured application logging

This repository is intended to be reused as a base for new Laravel projects.

## Stack

- PHP `8.1+` (project is Docker-ready with PHP-FPM)
- Laravel `10.x`
- Jetstream + Fortify + Sanctum
- MySQL `8.0`
- PHPUnit `10`

## Quick Start (Docker)

1. Clone the project
```bash
git clone <your-repo-url>
cd StartLaravel
```

2. Copy environment
```bash
cp .env.example .env
```

3. Start containers
```bash
docker compose up -d --build
```

4. Install dependencies + app key (inside container)
```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

5. Run migrations (and seed if you want initial data)
```bash
docker compose exec app php artisan migrate
# optional
docker compose exec app php artisan db:seed
```

6. Open app
- App: `http://localhost:8080`

## Project Architecture

### 1) Authentication

Authentication is handled by Jetstream/Fortify, with customizations:

- Login field is configured as `login` (email/username-style flow).
- Custom credential resolution is defined in:
  - `app/Providers/JetstreamServiceProvider.php`
- Fortify rate limiter is configured in:
  - `app/Providers/FortifyServiceProvider.php`

There is also a login listener that blocks inactive users:
- `app/Listeners/CheckUserActiveStatus.php`
- wired in `app/Providers/EventServiceProvider.php`

### 2) Authorization (RBAC)

Authorization uses multiple layers:

- Role enum:
  - `app/Enums/UserRole.php`
- User permission helpers:
  - `app/Models/User.php` (`hasRole`, `hasPermission`, scopes)
- Gates:
  - `app/Providers/AuthServiceProvider.php`
- Policies:
  - `app/policies/UserPolicy.php`

This gives centralized role/permission logic while keeping controllers clean.

### 3) Service Layer + DTO

Business logic is intentionally moved out of controllers:

- `app/services/UserService.php`
  - user creation/update/toggle/delete flows
  - logging and failure handling
- `app/services/FileUploadService.php`
  - user picture store/delete
- `app/DTOs/UserDTO.php`
  - request-to-domain data mapping

Controllers remain thin and delegate to services.

### 4) Request Validation

FormRequest classes validate and authorize inbound data:

- `app/Http/Requests/StoreUserRequest.php`
- `app/Http/Requests/UpdateUserRequest.php`

This keeps validation close to HTTP boundaries.

### 5) Logging

User lifecycle operations are logged through `Log::info` / `Log::error`, especially in:
- `app/services/UserService.php`

Useful for:
- audit trail basics
- operational debugging
- identifying failed create/update/delete actions

Logs are written under:
- `storage/logs/`

### 6) Testing

The project includes feature tests and unit tests.

- Feature tests: authentication/authorization behavior
- Unit tests: service-layer behavior (`UserService`)

Run all tests:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test tests/Unit/UserServiceTest.php
php artisan test tests/Feature/AuthorizationTest.php
```

Docker equivalent:
```bash
docker compose exec app php artisan test
```

## Key Directories

- `app/Http/Controllers` -> HTTP entrypoints
- `app/Http/Requests` -> validation + request authorization
- `app/services` -> domain/application services
- `app/DTOs` -> transfer objects
- `app/Enums` -> role and constants
- `app/policies` -> policy-based authorization
- `database/migrations` -> schema
- `database/seeders` -> seed data
- `tests/Feature` -> HTTP/auth behavior
- `tests/Unit` -> pure logic tests

## Starter Conventions

- Keep controllers thin
- Put business logic in services
- Use DTOs for structured input mapping
- Keep role/permission logic centralized
- Add tests for every new service/policy change
- Log critical domain actions

## Notes

- If you run Artisan/tests on host machine instead of Docker, ensure required PHP extensions are installed (`pdo_mysql`, `mbstring`, etc.).
- For Docker workflow, prefer running all Artisan/test commands inside the `app` container.

---

If you use this as a starter, keep this README updated when you add new modules (billing, API versioning, queues, events, etc.).
