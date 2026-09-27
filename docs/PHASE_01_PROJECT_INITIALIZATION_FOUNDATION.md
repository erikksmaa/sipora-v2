**Project:** SIPORA v2 — Sistem Informasi Program Olahraga dan Kepemudaan  
**Organization:** Dindikpora Kabupaten Pemalang  
**Phase:** 1 — Project Initialization & Foundation  
**Status:** Approved for implementation  
**Primary PRD:** `PRD.md`  
**Legacy reference:** `mandorin/`  
**New application root:** `sipora-v2/`

---

## 1. Purpose

This document is the detailed execution specification for Phase 1.

It must be read together with:

1. the root SIPORA v2 PRD,
2. the latest approved architecture decisions,
3. this Phase 1 specification.

Priority order:

1. PRD = project-wide product and architecture source of truth.
2. Latest approved decisions = newest clarifications and overrides.
3. This Phase 1 document = implementation scope for Phase 1.

If a conflict is discovered, stop and report it before making a major architectural change.

---

## 2. Phase Objective

Create a clean, production-oriented Laravel foundation for SIPORA v2.

This phase is **foundation only**.

Do not implement business domains such as:

- Youth Profile
- Identity Verification workflow
- Community
- Community Membership
- Activity
- Activity Sessions
- Activity Participation
- Attendance
- Activity Passport
- Youth Portfolio
- Program
- Proposal
- Logbook
- E-LPJ
- Opportunity
- Analytics
- final dashboards
- final Google Stitch UI

The purpose of this phase is to establish a correct technical baseline that later phases can safely build on.

---

## 3. Reconciliation Instructions

A Laravel 13 scaffold already exists in `sipora-v2/`.

Before continuing:

1. Inspect the existing scaffold.
2. Retain it unless it conflicts with this specification.
3. Do not recreate the project unless necessary.
4. Verify currently installed packages:
   - `laravel/socialite`
   - `spatie/laravel-permission`
   - `spatie/laravel-activitylog`
5. Confirm exact installed versions and compatibility with Laravel 13 and PHP 8.4.
6. Reconcile generated files against this specification.
7. Remove or revise prematurely generated files if they conflict with:
   - the PRD,
   - approved decisions,
   - this document.
8. Do not run migrations until the UUIDv7 / `BINARY(16)` user-ID strategy and Spatie polymorphic columns are implemented correctly.
9. Do not implement business functionality.
10. `mandorin/` must remain unchanged and read-only.
11. Report every deviation in the final Phase 1 report.

The current scaffold is known to have been created before this file existed. That is acceptable as long as it is reconciled correctly.

---

## 4. Target Runtime

Required environment:

- Laravel 13.x
- PHP 8.4.15
- Composer 2.10.x
- MySQL 8.0.30
- Node.js 24.x
- npm 11.x
- Git

Current known package baseline:

- `laravel/socialite` 5.31.0
- `spatie/laravel-permission` 8.3.0
- `spatie/laravel-activitylog` 5.1.1

Do not downgrade framework/runtime versions unless a demonstrated incompatibility requires it.

---

## 5. Frontend Baseline

Use:

- Blade
- Tailwind CSS
- Alpine.js
- Vite

Do not use:

- React
- Vue
- Inertia
- Livewire
- Flux UI

The final UI will later follow Google Stitch prototypes.

Phase 1 requires only simple placeholder layouts and functional authentication views.

---

## 6. Database Baseline

Database engine:

- MySQL 8.0.30
- InnoDB
- `utf8mb4`

Suggested local database:

`sipora_v2`

Remove SQLite as the default project assumption.

Do not silently retain SQLite-oriented scripts if they conflict with the MySQL target.

---

## 7. Identifier Strategy

SIPORA v2 uses application-generated UUIDv7 for:

- `users`
- future primary business entities

Target physical storage:

`BINARY(16)`

Do not silently fall back to:

- auto-increment user IDs,
- UUID strings,
- ULID,
- UUIDv4,

without explicit approval.

### 7.1 User ID

`users.id` must be designed for UUIDv7 `BINARY(16)`.

Implement a reliable reusable application-level conversion strategy between:

- PHP UUID value,
- database `BINARY(16)` representation.

The implementation should be reusable by future domain models.

### 7.2 Spatie compatibility

Spatie tables may keep integer primary keys:

- `roles.id`
- `permissions.id`

But polymorphic model references that point to `users.id` must be compatible with `BINARY(16)`.

Review and customize:

- `model_has_roles`
- `model_has_permissions`

Do not use Spatie default integer model references if they conflict with the user identifier strategy.

### 7.3 Other framework/package references

Review any table that references a user, including where applicable:

- sessions
- notifications
- activity logs
- social accounts
- authentication-related references

Do not leave incompatible integer user references.

### 7.4 UUID tests

Add tests proving that:

- a User can be created,
- UUIDv7 is generated application-side,
- the UUID round-trips correctly through `BINARY(16)`,
- Spatie roles can be assigned,
- role checks work,
- relevant user-related foreign keys work.

Correctness is more important than forcing a partial implementation.

If a blocking incompatibility is discovered, stop before migrations and report it rather than silently changing strategy.

---

## 8. Timestamp Strategy

Target application-owned database timestamp precision:

`DATETIME(6)`

Application display timezone:

`Asia/Jakarta`

Keep canonical time handling consistent.

Do not introduce mixed timezone assumptions into business logic.

---

## 9. Authentication Foundation

Implement Laravel session-based authentication supporting:

- register
- login
- logout
- email verification
- forgot password
- reset password
- authentication rate limiting

Manual registration fields:

- name
- email
- password
- password confirmation

Do not build Youth Profile fields yet.

Authentication views should be minimal Blade views that can be redesigned later.

Do not install a starter kit that introduces React, Vue, Livewire, Flux, or Inertia.

---

## 10. Email Verification

The `User` model must support Laravel email verification.

Manual registration flow:

Register  
→ account created  
→ verification email sent  
→ user verifies email  
→ verified account

Do not confuse email verification with SIPORA Identity Verification.

---

## 11. Google OAuth

Use Laravel Socialite.

Google OAuth is an optional convenience authentication method.

It is **not** SIPORA Identity Verification.

Create a separate table:

`user_social_accounts`

Suggested fields:

- id
- user_id
- provider
- provider_user_id
- provider_email
- avatar_url nullable
- created_at
- updated_at

Recommended constraints:

- unique `(provider, provider_user_id)`
- index `user_id`

Do not store OAuth access or refresh tokens unless they are actually required.

### 11.1 Existing account linking

If Google returns an email that safely matches an existing SIPORA account:

- do not create a duplicate User,
- link the provider identity to the existing account when safe.

### 11.2 OAuth-only accounts

Password may be nullable for OAuth-created accounts.

The architecture should allow the user to set a password later.

### 11.3 Government users

Do not provide public OAuth registration that grants Admin or Verifier roles.

---

## 12. Google reCAPTCHA v3

Implement reCAPTCHA v3 as a reusable security service.

Preferred structure:

`app/Services/Security/RecaptchaService.php`

Configuration:

`config/recaptcha.php`

Environment variables:

- `RECAPTCHA_SITE_KEY`
- `RECAPTCHA_SECRET_KEY`
- `RECAPTCHA_MIN_SCORE`

Suggested initial threshold:

`0.5`

Server-side verification should validate:

- `success`
- `score`
- expected `action`
- `hostname` where practical

Apply to:

- manual registration
- login
- forgot password

Do not apply reCAPTCHA to authenticated internal workflows.

Keep Laravel rate limiting enabled.

### 12.1 Testability

The reCAPTCHA service must be mockable or fakeable.

Automated tests must not call Google's live verification service.

---

## 13. Spatie Permission

Use `spatie/laravel-permission`.

Global roles are only:

- `youth`
- `verifier`
- `admin`

Do not create global roles:

- `leader`
- `manager`
- `member`

Those are contextual organization roles for later phases.

### 13.1 Initial permissions

Admin:

- `manage users`
- `verify identities`
- `manage opportunities`
- `manage master data`
- `manage government users`
- `moderate users`
- `moderate communities`
- `view analytics`
- `view audit logs`

Verifier:

- `review communities`
- `review activities`
- `review proposals`
- `review logbooks`
- `review financial reports`
- `complete programs`

Do not create unnecessary Youth permissions in Phase 1.

### 13.2 Seeders

Create idempotent seeders for:

- roles
- permissions
- role-permission mappings

A newly registered public user should receive the `youth` role through application logic.

Do not hard-code real production Admin credentials.

---

## 14. Authorization Architecture

Phase 1 establishes the system-level authorization baseline only.

Architecture:

**Spatie Permission**  
→ system roles and permissions

**Laravel Policies**  
→ resource authorization in later phases

**Organization Membership**  
→ contextual organization roles in later phases

Do not simulate future organization membership through Spatie roles.

---

## 15. Activity Logging

Use `spatie/laravel-activitylog`.

Phase 1 should:

- configure the package correctly,
- ensure schema compatibility with the user identifier strategy,
- provide a clean foundation for explicit audit logging later.

Do not log every request or model change automatically.

Future sensitive events include:

- identity verification decisions,
- role changes,
- moderation,
- approval/rejection actions.

Avoid recording sensitive identity content in audit logs.

---

## 16. User Foundation

Keep the `users` table focused on account/authentication concerns.

It may include only fields genuinely needed for Phase 1, such as:

- id
- name
- email
- email_verified_at
- password nullable
- remember token
- timestamps

Do not add:

- Youth bio
- birth date
- gender
- address
- NIK
- interests
- skills
- education
- identity documents
- organization fields

Those belong to later phases.

The `User` model should support:

- authentication
- email verification
- Socialite linking
- Spatie `HasRoles`
- UUIDv7 binary ID handling

---

## 17. Route Architecture

Prepare separate route files:

- `routes/web.php`
- `routes/public.php`
- `routes/youth.php`
- `routes/manager.php`
- `routes/verifier.php`
- `routes/admin.php`

Load them cleanly through Laravel 13 routing configuration.

Phase 1 only needs authentication and minimal placeholders.

Suggested placeholders:

- `/`
- `/youth/home`
- `/verifier/dashboard`
- `/admin/dashboard`

Manager routes may remain empty or minimal because Organization context does not exist yet.

Protect routes appropriately by:

- authentication,
- verified email where intended,
- role/permission middleware.

Do not create business controllers yet.

---

## 18. Application Structure

Prepare clean directories for future work:

- `app/Actions/`
- `app/Services/`
- `app/Services/Security/`
- `app/Policies/`
- `app/Enums/`
- `app/Support/`
- `app/Notifications/`

Do not fill them with speculative placeholder classes.

Only create actual Phase 1 classes.

Controllers should remain thin.

---

## 19. Blade Layout Baseline

Create minimal reusable layouts:

- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/youth.blade.php`
- `resources/views/layouts/manager.blade.php`
- `resources/views/layouts/verifier.blade.php`
- `resources/views/layouts/admin.blade.php`

These are placeholders only.

The final UI will later be implemented from Google Stitch prototypes.

Do not attempt full SIPORA visual implementation in this phase.

---

## 20. Alpine.js and Vite

Ensure Alpine.js is installed and integrated.

Verify:

- `npm install`
- `npm run build`

work successfully.

Keep frontend dependencies minimal.

Do not add a large UI component framework.

---

## 21. Private Storage Baseline

Prepare a private storage disk or equivalent secure storage configuration for future sensitive files such as:

- KTP
- KIA
- student cards
- sensitive administrative documents

Do not build upload functionality yet.

Public media and private sensitive documents must remain conceptually separated.

Private identity files must not be exposed through `storage:link`.

---

## 22. Session and Security Configuration

Review session configuration for the chosen architecture.

If database sessions are used, session user references must be compatible with UUIDv7 `BINARY(16)`.

Confirm:

- CSRF protection remains active,
- passwords use Laravel hashing,
- session security uses Laravel defaults appropriate for local development,
- secure production settings remain configurable via environment.

Do not invent custom cryptography.

---

## 23. Development Quality Tools

Ensure Laravel Pint is available and runnable.

Static analysis may be added only if:

- compatible with Laravel 13 / PHP 8.4,
- it does not block Phase 1,
- it provides real value.

Do not add tooling simply because it exists.

Keep Laravel's supported test stack.

---

## 24. Testing Baseline

Add automated tests for Phase 1.

At minimum cover:

- public homepage loads,
- manual registration works,
- newly registered user receives `youth` role,
- login works,
- logout works,
- email verification flow is configured,
- password reset flow is configured,
- unauthenticated user cannot access Youth protected route,
- ordinary Youth cannot access Admin route,
- ordinary Youth cannot access Verifier route,
- Admin can access Admin placeholder route,
- Verifier can access Verifier placeholder route,
- Google OAuth redirect route exists,
- Google OAuth failure/callback handling fails safely,
- matching OAuth email does not create duplicate account under the implemented safe-linking rules,
- UUIDv7 binary User ID works,
- Spatie role assignment works with the binary User ID,
- reCAPTCHA can be mocked/faked and tests never call Google.

Tests should prioritize authorization failures as well as happy paths.

---

## 25. Environment Configuration

`.env.example` must be complete but contain no secrets.

Include variables for at least:

### Application / Database
- application URL
- database connection

### Google OAuth
- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`

### reCAPTCHA
- `RECAPTCHA_SITE_KEY`
- `RECAPTCHA_SECRET_KEY`
- `RECAPTCHA_MIN_SCORE`

### Mail
- Laravel-supported mail configuration needed for email verification/reset

Do not commit `.env`.

Do not commit credentials, API keys, OAuth secrets, or production identifiers.

---

## 26. Git Baseline

Existing repository:

`erikksmaa/SIPORA---Sistem-Informasi-Program-Olahraga-dan-Kepemudaan`

Expected branch convention:

- `main` = stable
- `develop` = active development

Do not push or rewrite remote history unless explicitly authorized in the current environment.

Ensure `.gitignore` excludes:

- `.env`
- dependencies
- generated build output where appropriate
- logs
- local IDE settings where appropriate
- secret credentials

Do not copy Mandorin Git history into SIPORA v2.

---

## 27. README

Update the project README with:

- SIPORA v2 description
- current phase
- technology stack
- runtime requirements
- local installation
- MySQL database setup
- Composer setup
- frontend setup
- environment setup
- Google OAuth configuration
- reCAPTCHA configuration
- mail setup requirement
- migration commands
- seed commands
- test commands
- build commands

Also document the authorization architecture:

- Spatie Permission = system-level roles/permissions
- Laravel Policies = resource-level authorization
- Organization Membership = contextual organization role

Make clear that Google OAuth does not equal Verified Youth identity.

---

## 28. Explicit Non-Goals

Do **not** implement in Phase 1:

- Youth Profile
- onboarding questionnaire
- identity document upload/review
- Interests
- Skills
- Education
- Achievements
- Community
- Organization memberships
- Activity
- Sessions
- Participation
- Attendance
- Passport
- Certificates
- Program
- Proposal
- Logbook
- E-LPJ
- Opportunity
- Analytics
- recommendations
- bookmarks
- public portfolio
- final public pages
- final manager/verifier/admin dashboards
- Google Stitch UI recreation

Do not create dummy domain migrations merely to “prepare” future phases.

---

## 29. Migration Safety Gate

Before running the first migration, verify all of the following:

- `users.id` uses the approved UUIDv7 `BINARY(16)` strategy.
- UUID generation happens at the application layer.
- Spatie polymorphic model IDs are compatible with `users.id`.
- `user_social_accounts.user_id` is compatible with `users.id`.
- session user references are compatible if database sessions are used.
- activity log causer/subject relationships do not introduce incompatible user identifiers.
- foreign key definitions are valid for MySQL 8.0.30.
- application-owned timestamp columns use the intended precision.
- no SQLite-specific schema assumptions remain.

If any of these items cannot be implemented safely, stop and report the issue.

Do not run a partially compatible schema merely to make migrations pass.

---

## 30. Database Migration Requirements

After the migration safety gate is satisfied:

1. Create the local MySQL database if it does not already exist.
2. Configure the application for MySQL.
3. Run migrations from a clean database.
4. Run role/permission seeders.
5. Confirm that migrations can be rolled back and rerun during development.
6. Confirm that a freshly migrated database is enough to boot the application.

Required command verification:

```bash
php artisan migrate:fresh --seed
```

This command must succeed on the development database before Phase 1 is considered complete.

Do not run destructive migration commands against any database that contains important data.

---

## 31. Authentication Behaviour Requirements

Manual registration:

```text
Register
→ reCAPTCHA verification
→ User created
→ youth role assigned
→ verification email sent
→ email verification
→ authenticated Youth area
```

Manual login:

```text
Credentials
→ rate limiter
→ reCAPTCHA verification
→ authentication
```

Forgot password:

```text
Email
→ reCAPTCHA verification
→ password-reset notification
```

Google OAuth:

```text
Continue with Google
→ Google
→ callback
→ locate provider identity
→ safely locate/link existing SIPORA account if applicable
→ otherwise create account
→ assign youth role
→ authenticate
```

Google OAuth must never assign:

- admin
- verifier

through public authentication.

---

## 32. Role Protection Requirements

Minimum Phase 1 route behaviour:

### Guest

May access:

- public homepage
- register
- login
- password reset
- Google OAuth entry point

Must not access:

- Youth protected pages
- Verifier workspace
- Admin workspace

### Youth

May access:

- Youth placeholder home

Must not access:

- Verifier workspace
- Admin workspace

### Verifier

May access:

- Verifier placeholder dashboard

### Admin

May access:

- Admin placeholder dashboard

Role checks must be enforced server-side.

Hiding navigation elements is not authorization.

---

## 33. Security Acceptance Criteria

Phase 1 is not complete unless:

- CSRF protection is active.
- passwords are hashed through Laravel.
- authentication endpoints are rate limited.
- reCAPTCHA is validated server-side.
- reCAPTCHA score/action checks are configurable.
- tests do not contact Google reCAPTCHA.
- OAuth callback state/security is handled by Socialite.
- OAuth does not create duplicate accounts for the supported safe-linking case.
- `.env` is ignored.
- credentials are absent from tracked files.
- private storage is not web-accessible.
- Admin and Verifier routes enforce authorization server-side.
- no hard-coded production password exists.
- no sensitive OAuth token is stored without a demonstrated requirement.

---

## 34. Code Quality Requirements

Before Phase 1 completion:

Run Laravel Pint:

```bash
./vendor/bin/pint
```

or the appropriate Windows-compatible invocation.

Ensure:

- controllers remain small,
- repeated authentication logic is extracted appropriately,
- reCAPTCHA logic is not duplicated in controllers,
- OAuth linking logic is testable,
- UUID conversion logic has one clear source of truth,
- no speculative architecture is added for later domains.

Do not create abstractions merely to increase folder count.

---

## 35. Required Verification Commands

Before reporting Phase 1 as completed, run and record results for:

```bash
php artisan about
composer check-platform-reqs
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
npm install
npm run build
```

Also run:

```bash
./vendor/bin/pint --test
```

or equivalent.

If a command cannot run because it requires real external credentials, verify the configuration path through tests/mocks and clearly report the limitation.

Google OAuth and reCAPTCHA must not require live credentials for automated tests to pass.

---

## 36. Phase 1 Verification Checklist

Phase 1 can be marked complete only if all applicable items pass:

- [ ] Laravel 13 application boots.
- [ ] PHP/platform requirements pass.
- [ ] MySQL is the active database target.
- [ ] SQLite defaults have been removed or intentionally neutralized.
- [ ] UUIDv7 `BINARY(16)` User IDs work.
- [ ] UUID round-trip is tested.
- [ ] Spatie works with the binary User ID.
- [ ] Roles and permissions seed correctly.
- [ ] Public registration works.
- [ ] New public registrations receive the `youth` role.
- [ ] Login works.
- [ ] Logout works.
- [ ] Email verification is configured.
- [ ] Password reset is configured.
- [ ] Google Socialite integration is structured safely.
- [ ] OAuth duplicate-account handling is tested for supported cases.
- [ ] reCAPTCHA service exists and is testable.
- [ ] Auth rate limiting remains enabled.
- [ ] Youth route protection works.
- [ ] Admin route protection works.
- [ ] Verifier route protection works.
- [ ] Private storage baseline exists.
- [ ] Tailwind works.
- [ ] Alpine.js works.
- [ ] Vite build succeeds.
- [ ] Phase 1 tests pass.
- [ ] Pint passes.
- [ ] `.env.example` is documented.
- [ ] README is updated.
- [ ] `mandorin/` remains unchanged.
- [ ] No Phase 2+ domain functionality was implemented.
- [ ] No secret is committed.

---

## 37. Definition of Done — Phase 1

Phase 1 is complete when SIPORA v2 has a stable technical foundation where:

1. the application installs from a clean checkout,
2. MySQL schema builds successfully from scratch,
3. UUIDv7 binary identifiers work correctly,
4. system-level roles and permissions work,
5. manual authentication works,
6. email verification/reset foundations work,
7. Google OAuth architecture works safely,
8. reCAPTCHA and rate limiting protect public auth flows,
9. private storage is prepared,
10. route/workspace boundaries are established,
11. frontend build succeeds,
12. automated tests pass,
13. the project is documented,
14. no business domain has been implemented prematurely.

Passing UI screenshots alone do not satisfy the Phase 1 Definition of Done.

---

## 38. Stop Condition

After Phase 1 verification:

**STOP.**

Do not implement Phase 2.

Do not create Youth Profile migrations.

Do not create Community, Activity, Program, or other domain schemas.

Wait for explicit review and approval.

---

## 39. Required Final Report

Return the final report using exactly this structure:

### PHASE
Phase 1 — Project Initialization & Foundation

### STATUS
Completed / Completed with Issues / Blocked

### RUNTIME
- Laravel:
- PHP:
- Composer:
- MySQL:
- Node:
- npm:

### PACKAGES
List relevant installed package names and exact versions.

### IMPLEMENTED
Summarize completed Phase 1 work.

### FILES CREATED
List important newly created files.

### FILES MODIFIED
List important modified files.

### DATABASE CHANGES
Describe:
- users schema
- UUID strategy
- Socialite account table
- Spatie tables
- Activitylog tables
- session/auth tables
- other Phase 1 migrations

### UUID IMPLEMENTATION
Explain exactly:
- UUIDv7 generation,
- PHP representation,
- `BINARY(16)` storage,
- model conversion,
- Spatie compatibility.

### AUTHENTICATION
Summarize:
- registration
- login/logout
- email verification
- password reset
- Google OAuth

### RECAPTCHA
Describe implementation, validation checks, and testing strategy.

### AUTHORIZATION
Describe:
- roles,
- permissions,
- middleware,
- protected placeholders.

### SECURITY
Summarize:
- CSRF
- rate limits
- credential handling
- private storage
- OAuth account linking
- other relevant controls

### ROUTES
Summarize route files and important Phase 1 routes.

### FRONTEND
Summarize:
- Blade
- Tailwind
- Alpine
- Vite
- build result

### TESTS
- Passed:
- Failed:
- Skipped:

List major test scenarios.

### COMMANDS VERIFIED
Report result of:

- `php artisan about`
- `composer check-platform-reqs`
- `php artisan migrate:fresh --seed`
- `php artisan route:list`
- `php artisan test`
- `npm run build`
- Pint check

### KNOWN ISSUES
List unresolved issues.

If none:

`None.`

### DEVIATIONS FROM PRD / PHASE SPECIFICATION
Explicitly list deviations.

If none:

`None.`

### MANDORIN STATUS
Confirm whether legacy project remained unchanged.

### NEXT PHASE READINESS
State whether the project is technically ready for Phase 2.

Do not begin Phase 2.