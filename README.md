# template.philipnewborough.co.uk

A CodeIgniter 4 starter template providing a structured foundation for web applications that require a public-facing site, an authenticated admin dashboard, a RESTful API, and CLI tooling.

## Stack

- **PHP 8.2+** with CodeIgniter 4.7+
- **MySQL** (SQLite3 and PostgreSQL configs also available)
- **Bootstrap 5** (dark theme) with Bootstrap Icons
- **DataTables** (admin panel, server-side via `hermawan/datatables`)
- **jQuery**
- **ESLint** for JavaScript linting
- **PHPUnit 10** for testing

## Features

### Authentication
Authentication is delegated to an external centralised auth server. Session tokens stored in cookies (`user_uuid`, `token`) are validated via cURL on each request. On success, the session is populated with user data (id, username, email, groups, `is_admin` flag).

Five request filters handle access control:

| Filter | Purpose |
|---|---|
| `AuthFilter` | Enforces authentication; redirects unauthenticated users to the external login page |
| `OptionalAuthFilter` | Attempts to hydrate the session on public routes without redirecting |
| `AdminFilter` | Restricts routes to users with the `is_admin` flag |
| `ApiFilter` | Validates API key and user UUID headers; handles CORS preflight |
| `DebugFilter` | Restricts debug routes to admin users |

### Routing

| Route | Handler | Access |
|---|---|---|
| `GET /` | `Home::index` | Public (optional auth) |
| `GET /admin` | `Admin\Home::index` | Admin only |
| `GET /admin/datatable` | `Admin\Home::datatable` | Admin only |
| `GET /api/test/ping` | `Api\Test::ping` | API key required |
| `GET /logout` | `Auth::logout` | Authenticated |
| `GET /unauthorised` | `Unauthorised::index` | Public |
| `cli/test/index/:name` | `CLI\Test::index` | CLI only |
| `cli/test/count` | `CLI\Test::count` | CLI only |

### Admin Panel
The `/admin` dashboard requires admin authentication and includes a server-side DataTables-powered data table with Bootstrap 5 styling, a responsive offcanvas sidebar, and conditional navigation. DataTables requests are handled by `Admin\Home::datatable` at `GET /admin/datatable`, which uses [`hermawan/datatables`](https://github.com/hermawan/ci4-datatables) to build the server-side JSON response from a query builder instance. Column output can be customised via `->edit()` callbacks before calling `->toJson(true)`.

### API
All routes under `/api/*` are protected by the `ApiFilter`. Authentication is header-based (`apikey`, `user-uuid`). A master API key can bypass per-user validation. Responses are JSON. CORS is open (`*`) with support for `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, and `OPTIONS`.

### Frontend Assets
Assets live in `public/assets/` and are organised by template, page, and vendor. Cache busting is handled via `filemtime()` query strings. JavaScript follows ES6+ conventions enforced by ESLint.

### Testing
Tests are located in `tests/` and run with PHPUnit via `composer test`. Includes unit, database, and session test examples.

## Setup

1. Copy `env` to `.env` and configure your environment variables (database credentials, base URL, API keys, auth server URLs).
2. Install PHP dependencies: `composer install`
3. Install JS dependencies: `npm install`
4. Run migrations: `php spark migrate`
5. Run tests: `composer test`

## Directory Structure

```
app/
  Config/       Application configuration
  Controllers/  HTTP, Admin, API, CLI, and Debug controllers
  Filters/      Request filters (auth, admin, API, debug)
  Libraries/    Custom libraries (Sendmail)
  Models/       (empty — add your own)
  Database/     Migrations and seeds
  Views/        Templates and page views
public/
  assets/       CSS, JS, and vendor assets
tests/          PHPUnit tests
```
