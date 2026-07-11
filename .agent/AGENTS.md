# Portal ICC — Agent Guide

## Project state
This is a **fresh Laravel 12.63.0 scaffold** — no custom code has been built yet. The full product requirements live in `.agents/prd.md` (the single source of truth for intended features). Routes (`routes/web.php`) only contain the default welcome page.

## Stack
- **Backend:** Laravel 12, PHP ^8.2, MySQL 8 (port 3307, db `portal-icc`)
- **Frontend:** Tailwind CSS v4, Vite 7, Alpine.js (planned), Livewire 3 (planned)
- **Admin panel:** Filament v4 (planned, not yet installed)
- **Tests:** PHPUnit 11 — SQLite `:memory:` in testing (see `phpunit.xml`)

## Key commands (run from repo root)

| Command | Action |
|---|---|
| `composer run-script test` | Clear config + run `php artisan test` |
| `php artisan test --filter=ExampleTest` | Single test class |
| `php artisan test --filter=test_something` | Single test method |
| `composer run-script dev` | Full dev env: artisan serve + queue:listen + pail logs + vite HMR |
| `composer run-script setup` | Full first-time project setup |
| `./vendor/bin/pint` | Laravel Pint (PSR-12 code style lint) |
| `npm run dev` | Vite HMR only |
| `npm run build` | Vite production build |

Lint then test: `./vendor/bin/pint --test; if ($?) { composer run-script test }`

## Architecture (planned in `.agents/prd.md`)
- **Public portal** — Blade + Livewire 3, guest-accessible
- **Admin panel** — Filament v4 at `/admin` (Super Admin & Editor roles)
- **Organizer panel** — Custom Livewire dashboard at `/panel`
- **Single login form** — role-based redirect: `super_admin`/`editor` → `/admin`, `penyelenggara` → `/panel`
- **RBAC** — Spatie `laravel-permission` (planned)
- **Certificates** — DomPDF/Browsershot, QR codes, queued generation
- **Queue** — DB driver (`QUEUE_CONNECTION=database`)

## Conventions
- EditorConfig: 4-space indent, LF line endings
- PHP namespace: `App\` → `app/`, `Tests\` → `tests/`
- DB prefix for sessions, cache, queue: all use `database` driver
- Test env uses SQLite `:memory:` (no real DB needed for tests)
- `.env.example` has the canonical env reference
- **Always read `.agents/prd.md` before building new features** — it defines the full spec, DB schema, user flows, and access control rules
