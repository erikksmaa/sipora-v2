# Phase 23B — Security & Performance Hardening

## Status

Hardening implementation is complete. Targeted security tests, dependency audits,
database rebuild, route discovery, production cache compilation, asset build, Pint,
and diff validation pass. The full regression run has two failures caused by
pre-existing, uncommitted public visual changes; those files were intentionally
left untouched and are not part of Phase 23B.

## Scope reviewed

- Manual authentication, password reset, email verification, session lifecycle,
  reCAPTCHA v3, and stateful Google Socialite login.
- Global role boundaries and contextual leader/manager membership checks.
- Admin identity review and Verifier review workflows.
- Manager nested resources for activities, sessions, participants, attendance,
  programs, proposals, logbooks/media, E-LPJ/items/receipts, and membership.
- Private storage disks, upload validation, controlled streaming, hidden model
  attributes, audit properties, and notification payloads.
- Public Portfolio, Youth Directory, global search, public domain queries, external
  Opportunity URLs, certificate verification, and public media projection.
- CSRF, open redirects, raw Blade output, mass assignment, status transitions,
  secret exposure, production configuration, seeders, dependencies, query shape,
  and external assets.

## Findings

| ID | Severity | Surface | Finding and impact | Resolution | Coverage | Status |
| --- | --- | --- | --- | --- | --- | --- |
| SEC-23B-01 | High | Verifier Activity review | Draft Activities could be enumerated with `status=all` or opened by direct route before submission. | Drafts are excluded, direct detail/poster access uses resource authorization, and conflicted Verifiers are excluded. | `ActivityWorkflowTest` | Fixed |
| SEC-23B-02 | High | Verifier Program Proposal | A Verifier could open a draft Proposal and its private document through a guessed UUID before submission. | Drafts are excluded from the queue and Verifier view authorization; detail and document endpoints now enforce policy. | `ProgramProposalTest` | Fixed |
| SEC-23B-03 | High | Conflict of interest | Activity review lacked a server-side active leader/manager conflict check. | New Activity verification policy plus transactional action authorization blocks the decision, detail, poster, and queue result. | `ActivityWorkflowTest` | Fixed |
| SEC-23B-04 | Medium | Community conflict of interest | Community review relied on role/status validation without a defensive contextual membership conflict check. | Queue, detail/logo, and transactional decision paths now reject active leader/manager conflicts. | `CommunityApplicationTest` | Fixed |
| SEC-23B-05 | Medium | Browser response security | General HTML responses did not consistently emit clickjacking, MIME-sniffing, referrer, and browser capability policy headers. | Global compatible headers added. `SAMEORIGIN` preserves the intentional authenticated identity-document iframe. HSTS is added only for HTTPS production requests. | `SecurityHardeningTest` | Fixed |
| SEC-23B-06 | Medium | Public abuse controls | Public search, Youth Directory, and certificate verification had no dedicated rate limit. | Added named IP limiters: 60/minute for search/directory and 30/minute for certificate verification. | Route inspection and existing public tests | Fixed |
| PERF-23B-01 | Low | External assets | Google Fonts and the landing Unsplash hero remain runtime third-party dependencies. | Kept because no approved/licensed local replacements are present. Self-host before production when licensing/source files are available. | Asset inventory | Deferred |
| SEC-23B-07 | Low | CSP | A site-wide CSP is not shipped because current pages require Google Fonts and reCAPTCHA, and an untested strict policy would break authentication. | Baseline headers are active. Add a compatible staged CSP after external origins and local assets are finalized. | Header test | Deferred |
| OPS-23B-01 | Medium | Seed deployment | Production seeding safely skips demo users, but it also skips master-data seeders because master and demo seeders share the same non-production branch. | Do not run demo seeders in production. Split an explicit production master-data command before deployment. | `DatabaseSeeder` review | Deferred to deployment preparation |

No Critical or unresolved High findings remain.

## Authentication, session, reCAPTCHA, and OAuth

- Successful manual and Google login regenerate the session ID.
- Logout invalidates the session and rotates the CSRF token.
- Password reset uses a neutral response and invalidates existing database sessions.
- Auth forms are limited by IP and hashed email/IP keys. OAuth is separately limited.
- reCAPTCHA fails closed, verifies provider success, score, action, hostname, and
  timeout/connection failures, and never exposes the secret to Vite.
- The local bypass requires both an explicit disabled setting and local/testing
  environment.
- Socialite remains stateful. New OAuth accounts receive only `youth`; existing
  Admin/Verifier accounts cannot be auto-linked; OAuth never changes SIPORA
  identity verification.

## Authorization and IDOR review

Representative negative paths are covered for guest, unrelated Youth, member,
cross-Community Manager, Verifier, and Admin. Nested controller checks bind child
records to their route parent for Activity Sessions, Attendance, Participation,
Program Proposal, Logbook Media, E-LPJ Items, and receipts. Notification mutation
is owner-scoped. UUID opacity is not used as an authorization mechanism.

Admin remains responsible for personal identity verification. Admin is not granted
Verifier actions. Verifier review actions enforce their own status transitions and
contextual conflicts.

## Private files and uploads

- Identity documents, Proposal documents, Logbook media, and financial receipts
  use private non-served disks outside `public/`.
- There is no `public/storage` symlink in the checkout and no direct private path is
  rendered.
- Private delivery requires authenticated role/resource authorization and emits
  `no-store` plus `nosniff`; the global header also prevents cross-origin framing.
- Upload rules restrict MIME/type and size, while stored names are generated by the
  server. Original file names are not used as storage paths.
- Sensitive paths are hidden from model serialization and are absent from audit and
  notification payloads.

## Portfolio, directory, input, and output safety

- Public Portfolio composition applies each section visibility flag before
  projecting data or counts.
- Directory and global-search eligibility is filtered in SQL by public Portfolio;
  hidden bio/skills cannot affect matching or rendering.
- Public photo delivery requires both public Portfolio and photo visibility.
- Search terms are capped at 120 characters and wildcard characters are escaped.
- User text uses escaped Blade output. The only raw Blade output is the internal,
  fixed SVG icon path component.
- Opportunity links accept only HTTP/HTTPS and render with `noopener noreferrer`.
- Mutation endpoints retain Laravel web CSRF protection and do not use GET.

## Performance observations

Measurements were taken locally against the canonical seeded `sipora` database.
They are diagnostic values, not latency assertions.

| Route/service | Queries after hardening | Local time | Notes |
| --- | ---: | ---: | --- |
| Landing `/` | 21 | 157.2 ms | Bounded featured queries and aggregate statistics |
| Activity discovery | 9 | 32.6 ms | Paginated, eager-loaded, aggregate participant count |
| Community discovery | 8 | 22.1 ms | Paginated with SQL counts |
| Opportunity discovery | 8 | 26.0 ms | Paginated and bounded |
| Program discovery | 7 | 32.7 ms | Paginated and eager-loaded |
| Youth Directory | 21 | 76.7 ms | Paginated; privacy relations/counts eager-loaded |
| Global Search | 27 | 86.8 ms | Five bounded domain groups, maximum eight each |
| Admin analytics service | 15 | 12.3 ms | Aggregate queries only |
| Verifier dashboard summary | 6 | 10.0 ms | Aggregate queue queries only |

No performance code was changed, so a before/after delta is not claimed. Inspection
found no per-card relationship query in these routes and no `SELECT all` followed by
collection `take()`. No index migration was justified; the approved schema remains
unchanged.

## Asset and external dependency status

- Production build: CSS 120.92 kB (19.74 kB gzip), JavaScript 109.90 kB
  (39.53 kB gzip).
- Vite emits hashed assets. Configure the web server to cache `/build/assets/*`
  immutably for one year while serving `manifest.json` with revalidation.
- Google Fonts: still required by current visual layouts; external privacy and
  availability dependency. Self-host only from approved licensed font files.
- Google reCAPTCHA: required security dependency for manual auth forms.
- Google OAuth: optional login dependency; callback domain must match production.
- Unsplash hero: external performance/privacy dependency. Replace only when an
  approved licensed local asset is supplied.
- `public/hot` was an ignored local Vite marker and was removed before the production
  build so Laravel resolves hashed build assets.

## Dependency audit

- `composer audit --locked`: no security advisories.
- `npm audit --omit=dev`: zero vulnerabilities.
- No major dependency upgrades were performed.

## Production configuration recommendations

- Set `APP_ENV=production`, `APP_DEBUG=false`, the canonical HTTPS `APP_URL`, and a
  unique protected `APP_KEY`.
- Set `SESSION_SECURE_COOKIE=true`, keep HTTP-only cookies, encryption, and SameSite
  Lax, and terminate HTTPS before serving authenticated traffic.
- Configure real mail delivery and a queue worker for email/notification latency.
- Configure the Google OAuth callback and reCAPTCHA production hostname exactly.
- Grant write access only to `storage/` and `bootstrap/cache/`; keep private storage
  outside the public web root.
- Run route/config/view caches during deployment and clear/rebuild them after each
  release.
- Configure scheduler/queue supervision, database backup/restore testing, log
  retention, and static asset cache headers during staging/deployment.
- Separate production master-data seeding from all development/demo accounts before
  running a production seed command.

## Verification record

- Canonical database confirmed: MySQL `sipora`.
- `php artisan migrate:fresh --seed`: passed.
- Targeted hardening suite: 36 tests, 234 assertions, passed.
- Full suite: 247 passed, 2 failed, 1647 assertions. Both failures are stale visual
  assertions caused by pre-existing uncommitted UI changes in `resources/`; no
  hardening test failed.
- `php artisan route:list`: passed, 188 routes.
- `composer audit --locked`: passed.
- `npm audit --omit=dev`: passed.
- `npm.cmd run build`: passed.
- `php artisan route:cache`, `config:cache`, `view:cache`: passed; local caches were
  then cleared with `optimize:clear`.
- `git diff --check`: passed.
- Pint was applied only to Phase 23B files; final verification is recorded in the
  final Phase report.

## Working-tree boundary

The following pre-existing visual files were not edited for Phase 23B and must not be
included in its commit:

- `resources/css/app.css`
- `resources/views/components/public/page-intro.blade.php`
- `resources/views/components/public/section-heading.blade.php`
- `resources/views/home.blade.php`
- `resources/views/layouts/base.blade.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/public/about.blade.php`

Phase 23C — Regression & UAT Preparation must not start until those visual changes
are reconciled and the full suite is green.
