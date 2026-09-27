# SIPORA v2

Sistem Informasi Program Olahraga dan Kepemudaan — Dindikpora Kabupaten Pemalang.
Youth ecosystem platform. **Current scope: Phase 1 — Project Initialization & Foundation.**

Fresh implementation; `mandorin/` is read-only historical reference. No youth profile, community, activity, participation, portfolio, program or other business domain is implemented. Workspace pages are minimal placeholders.

## Requirements

- PHP 8.4.15 (Composer constraint ^8.4), Composer 2.10.x
- Laravel 13.x; MySQL 8.0.30 / InnoDB / utf8mb4
- Node 24.x, npm 11.x
- Blade, Tailwind CSS 4, Alpine.js 3, Vite 7
- PDO MySQL and extensions validated by `composer check-platform-reqs`

Versions are locked in composer.lock/package-lock.json. No frontend starter kit is used.

## Local installation

From this directory in PowerShell:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Configure local database credentials and APP_URL in `.env`. Do not overwrite an existing environment or regenerate its application key during routine updates.

Create dedicated databases in MySQL:

```sql
CREATE DATABASE sipora_v2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE sipora_v2_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Read `docs/PHASE_01_MIGRATION_GATE.md` before the first migration. Never target Mandorin or a database with important data.

```powershell
php artisan migrate --seed
npm run build
php artisan serve
```

Run `npm run dev` separately, or `composer dev` for PHP and Vite together. `composer setup` installs dependencies/builds assets but deliberately does not migrate. Web server document root must be `public/`.

Idempotent seeders create only youth/verifier/admin roles and 15 permissions; no accounts or fixed admin passwords. Government account management is a later phase. Tests use temporary factories.

## Authentication and external configuration

Registration accepts name/email/password/confirmation and assigns only youth. Laravel hashes passwords and sends verification before workspace access. Login/logout, remember-me and recovery use session authentication and password brokers.

### Mail

Local `MAIL_MAILER=log` writes verification/reset links to `storage/logs/laravel.log`. Actual delivery needs SMTP host/port/credentials/sender in `.env`; set MAIL_SCHEME appropriately. Treat logs containing signed/reset links as private.

### reCAPTCHA v3

Set RECAPTCHA_SITE_KEY, RECAPTCHA_SECRET_KEY, RECAPTCHA_HOSTNAME. Minimum score defaults to 0.5. Actions register/login/forgot_password are configurable in `.env.example`.

Missing configuration, rejected scores/actions/hosts and network failures fail closed. No production bypass. Live manual auth needs real configuration; tests fake the service/HTTP and never call Google. Reset-token submission and authenticated internal actions do not use reCAPTCHA.

### Google OAuth (optional)

Configure GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET and the exact GOOGLE_REDIRECT_URI (default http://localhost:8000/auth/google/callback). Socialite retains state verification; no stateless mode.

Safe linking:

- Known provider identity signs into its linked account; an email change does not silently change SIPORA email.
- New identities need verified, Google-authoritative email: Gmail or matching Workspace hd domain.
- Matching SIPORA accounts must already have verified email. No public auto-linking of Admin/Verifier accounts or conflicting identities.
- Unsupported third-party email identities use manual sign-in/recovery. No merging of unverified accounts.
- Unique provider identity and user/provider constraints prevent duplicate links. A racing duplicate fails safely; retry resolves the established identity.
- New users receive only youth and nullable passwords. Password recovery can set a password later.
- No access/refresh token is persisted; no avatar fetching.

**Google OAuth/email verification do not mean SIPORA Identity Verification.** No identity documents/workflow exist in Phase 1.

## Identifiers and time

`BinaryUuid` uses Ramsey UUID (Laravel dependency) to generate UUIDv7 and convert canonical text to/from RFC-order bytes. No byte swapping, UUIDv4 users or integer fallback.

`HasBinaryUuid` uses non-incrementing string keys and generates bytes before insert. **Eloquent id/getKey() are raw 16-byte strings**, preserving standard relationship and Spatie pivot writes without vendor changes. Use uuid()/getRouteKey() for text. JSON attributes convert primary keys to canonical text.

External lookup: `User::findOrFail(BinaryUuid::bytes($uuid))`. Route binding and queue restoration convert automatically. Never pass raw binary keys into URLs, JSON or audit properties. Assign foreign keys through relationships or getKey().

The auth provider converts session/remember-cookie text to binary queries. The binary-database session handler stores binary user references while auth payload identifiers remain text. Keep that driver with this schema; the stock database session driver does not convert. Signed email verification uses canonical UUID and verifies the current account.

Spatie roles/permissions keep BIGINT IDs; model pivots use BINARY(16). Audit rows keep native BIGINT IDs with binary subject/causer references. Audit subjects/causers must be UUID models. Integer role/permission IDs can be explicit properties, not polymorphic subjects. No automatic model/request logging.

Application-owned date columns use DATETIME(6), persisted in UTC. Display timezone: config('sipora.display_timezone') = Asia/Jakarta. Framework queue/cache/session epoch counters keep native integer types. Database notifications are deferred and must later use binary notifiable IDs.

## Authorization and routes

- Spatie: global youth/verifier/admin roles and system permissions.
- Laravel Policies: resource authorization in later phases.
- Organization Membership: contextual leader/manager/member in later phases, never global roles.

web.php loads public/auth/youth/manager/verifier/admin route files. /youth/home, /verifier/dashboard, /admin/dashboard require authentication, verified email and their respective role. No blanket admin bypass. Manager routes stay empty pending organization context.

## Security and storage

CSRF is active. Public auth has IP and email/IP limits; OAuth/verification endpoints have limits. Password reset rotates remember tokens and invalidates database sessions. Passwords/CAPTCHA tokens are excluded from flashed input. OAuth tokens are not persisted.

The private disk points to storage/app/private with no serving route/public symlink. Public media is separate. No upload endpoint exists. Production needs HTTPS, SESSION_SECURE_COOKIE=true, APP_DEBUG=false, private database credentials and public/ as document root. Session encryption, HTTP-only and Lax cookies are configured. Never commit .env or secrets.

## Verification

Tests exclusively target **sipora_v2_testing** on MySQL; PHPUnit forces this name and bootstrap refuses another database before refresh. This disposable database is recreated by tests. Host/user/password come from the local environment. No Google credentials or live calls needed.

```powershell
php artisan about
composer check-platform-reqs
php artisan route:list
php artisan test
php vendor/bin/pint --test
npm run build
```

Only on the dedicated disposable development database:

```powershell
php artisan migrate:fresh --seed
php artisan migrate:rollback
php artisan migrate --seed
```

No remote push/history rewrite is part of this phase. Branch convention: develop for active work, main for reviewed stable work. See docs/SQL_REFERENCE_RECONCILIATION.md and docs/PHASE_01_PROJECT_INITIALIZATION_FOUNDATION.md.

**Stop after Phase 1. Phase 2 needs explicit approval.**
# sipora-v2
