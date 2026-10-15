# Internal Account Management & Recovery — 15 October 2026

## Implementation

1. **Architecture:** existing `User`, binary UUID binding, Spatie roles and `AuditEntry` remain the source of truth. `InternalAccountService` owns account lifecycle/recovery, `InternalUserPolicy` authorizes administrative actions, and `EnsureInternalAccountAccess` enforces activation/session version/mandatory password change.
2. **Schema:** three columns on `users`: `internal_active` (true), `must_change_password` (false), `internal_session_version` (0). Existing accounts retain their access. These fields are hidden and excluded from mass assignment. No new account table or role.
3. **Directory:** `/admin/internal-users`, only Admin/Verifier accounts without a Youth role; name/email search, role, activation and email verification filters; 15 rows/page; four clickable summaries. No last-login column because the existing domain does not track it.
4. **Verifier creation:** name/email, unique normalized email, explicit Verifier role, random temporary password, first-login password change required. Email remains unverified until the existing signed verification flow completes.
5. **Admin creation:** explicit allowlisted Admin role plus the acting Administrator's current password. Creation and role assignment are audited. Payloads cannot inject permissions, role arrays, verification or security flags.
6. **Activation:** soft activation/deactivation, no delete endpoint. Inactive users cannot log in or continue a session. References to historical reviews/evaluations are preserved. Self-management through administrative lifecycle endpoints is denied. A transaction locks the Admin role row to serialize lifecycle changes, with a last-active-Admin guard.
7. **Reset link:** uses Laravel's existing password broker/notification, expiry, single-use token and broker throttle. Administrative requests are additionally rate limited and audited. Mail transport failures show safe errors; no token appears in the Admin UI or audit properties.
8. **Temporary password:** cryptographically random 16 characters with upper/lowercase, number and symbol. Only a hash is stored. Plaintext is returned directly to the authorized POST response once, with `no-store, private`; never flashed to session, recorded in audit properties, or automatically emailed. The input is cleared on page hide/close. There is no readback endpoint.
9. **Mandatory change:** `/internal/password` permits only password/confirmation, and logout. All other web routes redirect there while the flag is set. New password must differ, is hashed, clears the flag and rotates the session. A forged forced-change request when the flag is false receives 403.
10. **Predictable development password:** deliberately unsupported. Local/testing and production all use secure randomness. There is no environment setting that can accidentally enable a fixed production password.
11. **Sessions:** reset/status/email change/password change increments a server-side version and rotates remember token. Database/binary-database rows belonging to the target are deleted. Other session drivers are invalidated on their next request through version checking. A successful own-password change regenerates the current session and keeps only its fresh version. Version-zero legacy sessions remain compatible immediately after migration.
12. **Verification resend:** existing signed notification for unverified accounts only, rate limited and audited; no manual “mark verified” action. Changing email clears verification and old reset tokens.
13. **Activitylog:** create, explicit role assignment, edit (field names only), activate/deactivate, reset-link request, temporary reset, verification resend, own/forced password change and completed broker reset. Actor, subject, event and timestamp use existing binary UUID audit relations. No password/hash/token properties. Admin log page is limited to `internal_accounts` events.
14. **Authorization:** Admin-only management routes and service policy checks; Verifier/Youth denied. Youth records cannot be edited/promoted through this directory. Sensitive operations on other Admins require the actor's current password. No administrative self-deactivation/reset/edit/role-change/delete flow. Own-password routes require the appropriate Admin/Verifier role and current password; route-specific workspace shells remain active.

## Files and migration

15. **Files:** `app/Services/InternalAccountService.php`, `app/Policies/InternalUserPolicy.php`, `app/Http/Middleware/EnsureInternalAccountAccess.php`, `app/Http/Controllers/Admin/InternalUserController.php`, `app/Http/Controllers/InternalPasswordController.php`; integrations in `User`, Auth `SessionController`, `GoogleAuthController`, `PasswordResetController`, `bootstrap/app.php`, and Admin/Verifier/Auth routes. Views: `admin/internal-users/*`, `government/account/password.blade.php`, workspace navigation. Added feature tests and this report; appended the canonical SQL schema delta. Other pre-existing working-tree changes belong to earlier phases.
16. **Migration:** `2026_10_15_000001_add_internal_account_security.php`, applied successfully to development `sipora`. The SQL schema snapshot carries the same three columns. No development users were created, reset, edited or deactivated for QA.

## Validation

17. **New tests:** `tests/Feature/InternalAccountManagementTest.php` covers both password workspaces, directory exclusion/filters/pagination, creation, random one-time secret handling, Admin confirmation, mass-assignment protection, email re-verification, role-change denial, self/last-Admin protection, inactivity, forced-change journey, current-password checks, session invalidation, broker single-use/expiry/throttle, signed verification, invalid password inputs, and mixed Youth-role exclusion.
18. **Test results:** full regression **331 passed / 2,875 assertions** (309.73 s). Final targeted run after formatting and three extra boundary cases: **23 passed / 264 assertions** (42.45 s), comprising 15 internal-account tests and 8 existing authentication-reliability tests. The three extra tests were added after full-suite discovery and are included in the final targeted run. All tests use isolated `sipora_attendance_test`, never a refresh/reset of development `sipora`. Temporary test configuration is restored byte-for-byte after each run.
19. **Build:** Vite production build passed (79 modules; 17.07 s). PHP syntax checks, route listing and Blade view compilation passed. Laravel Pint applied to new PHP components and the Google callback integration.
20. **Browser QA:** existing demo Admin and Verifier signed in successfully. Inspected Admin directory/create form and the conditional Admin-password field; both own-password pages show the correct workspace and active item. Checked desktop 1440x900 and mobile 390x844, light and dark rendering. Table stays horizontally scrollable inside its container; password form has no horizontal overflow. Screenshots in `storage/app/internal-password-admin.png`, `internal-password-verifier.png`, `internal-password-verifier-mobile.png`, `internal-users-mobile-dark.png`. Viewport override reset. No real account credential mutations were performed through the browser: those flows were exercised through isolated feature tests.
21. **Limits:** real inbox delivery depends on configured SMTP; external email delivery was not verified. Browser credential entry/change is reserved for the human user; therefore live create/reset/deactivate scenarios were not submitted. No new features outside internal account lifecycle/recovery were introduced. No automatic commit.

## Final test output

```text
Full regression:    331 passed (2875 assertions), 309.73 s
Final targeted:     23 passed (264 assertions),   42.45 s
Vite build:         PASS, 79 modules, 17.07 s
PHP syntax:         PASS
Blade compilation:  PASS
Password routes:    4 workspace routes + 2 mandatory-change routes
```

The targeted suite includes the final three extra boundary tests; counts overlap with the full regression and must not be added together. The test guard and `phpunit.xml` have no residual changes. All existing development accounts retain their credentials and roles; migration defaults preserve activation.
