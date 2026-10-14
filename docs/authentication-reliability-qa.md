# Authentication Reliability Fix — SIPORA

14 October 2026. Scope: manual email verification and forgot/reset password. No schema changes or automatic commit.

## Audit / actual causes
- The existing User correctly implements MustVerifyEmail and casts email_verified_at as datetime and password as hashed. Manual RegisterUser assigns only Youth. Registered dispatches Laravel's verification notification.
- Verification already uses an authenticated, signed, 60-minute URL, text UUID matching for the binary-ID User, and 6/minute verification throttles. Fulfill handles Verified event/idempotence correctly.
- Confirmed recovery bug: a signed link opened while logged out sets an intended URL, but SessionController previously always redirected to HomeRoute, losing the verification destination. Login now uses Laravel redirect()->intended() with the existing HomeRoute fallback.
- Confirmed feedback bug: PasswordResetController discarded the password broker result, reporting delivery even on broker throttling. It now gives a clear throttled response and generic success for unknown email without enumerating accounts.
- Mail transport exceptions previously propagated as an unhandled error. During registration this could leave an already-created account without a recoverable signed-in notice. Registration now preserves the account and authenticated notice with safe resend instructions; resend/reset transport failures produce generic validation feedback without provider details.
- Development configuration audit: MAIL_MAILER=log, no SMTP credentials configured. This driver does not deliver to a real inbox. APP_URL=http://127.0.0.1:8000. The expected password_reset_tokens table exists; broker/provider/schema need no replacement.

## Final flows
Manual: create unverified Youth → Registered/verification notification → verification notice with current user's own email, resend and logout → signed link → login resumes the link when needed → fulfillment/Verified once → role-appropriate HomeRoute → verified middleware allows access. Verified users leave the notice and cannot trigger unnecessary resend. Account email verification does not imply identity verification.

Reset: email form + existing CSRF/reCAPTCHA/rate limits → Laravel password broker → configured mailer → reset page receives URL token and decoded email → broker validates hashed token, email and 60-minute expiry → password cast hashes new value → remember token rotates, database sessions are revoked → PasswordReset event → broker deletes token → login with success feedback. Invalid, expired, wrong-email and replayed tokens are rejected. Existing min8/max72/confirmation rules are unchanged.

## Security / Socialite
Signed auth middleware, expiry, throttling, UUID mapping, CSRF and reCAPTCHA remain active. No custom raw-token comparison, new token logging, credential logging or role grant. Reset token was added to dontFlash alongside passwords/reCAPTCHA tokens. Safe mail errors do not include provider exception details. Unknown-account reset requests keep a generic success response.
Google Socialite implementation was not modified. Existing stateful callback, authoritative verified Google-email matching, no privilege escalation, no identity verification and safe failure tests remain green. Live Google OAuth was not reauthorized in the browser; compatibility was checked through existing mocked provider tests.

## Views
Only forgot-password, reset-password and verification-notice were polished, with a small shared inline feedback component. Existing login/register architecture and shared auth form remain. Plus Jakarta Sans, existing theme tokens and light/dark layout are retained. Notice includes the authenticated account's email and logout; resend has a loading/disabled state. Reset page offers a new-link action and retains old email after validation. No unrelated frontend redesign.

## Files changed in this phase
- [app/Http/Controllers/Auth/EmailVerificationController.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/app/Http/Controllers/Auth/EmailVerificationController.php>)
- [app/Http/Controllers/Auth/PasswordResetController.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/app/Http/Controllers/Auth/PasswordResetController.php>)
- [app/Http/Controllers/Auth/RegistrationController.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/app/Http/Controllers/Auth/RegistrationController.php>)
- [app/Http/Controllers/Auth/SessionController.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/app/Http/Controllers/Auth/SessionController.php>)
- [routes/auth.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/routes/auth.php>)
- [bootstrap/app.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/bootstrap/app.php>)
- [resources/views/auth/verify-email.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/auth/verify-email.blade.php>)
- [resources/views/auth/forgot-password.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/auth/forgot-password.blade.php>)
- [resources/views/auth/reset-password.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/auth/reset-password.blade.php>)
- [resources/views/components/auth-flow-feedback.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/auth-flow-feedback.blade.php>)
- [tests/Feature/AuthenticationReliabilityTest.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/tests/Feature/AuthenticationReliabilityTest.php>)

## Checks
Targeted: 31 passed / 238 assertions (before adding the actual-mail end-to-end test, which is included in full regression).
Full regression: 299 passed / 2,455 assertions, 255.30 seconds.
npm run build passed (79 modules). Blade view:cache, PHP syntax/Pint and route:list passed. No duplicated auth route names or reset auth/guest middleware conflicts were found. Test settings are restored after isolated sipora_attendance_test runs; existing development data was not reset.

New tests cover logged-out signed-link resumption, duplicate Verified protection, own-email notice, authenticated/rate-limited resend, already-verified resend suppression, broker throttle, unknown email, actual credential change, old/new login, remember/session revocation, token hashing/expiry/mismatch/replay, confirmation validation/token flash suppression and safe transport failures/recoverable registration.
An additional end-to-end test switches only the test mailer to array, uses the real notification/mail pipeline, follows the actual generated email links and completes both required journeys. Email bodies/tokens stay in memory and are not printed. External SMTP is not needed for automated tests.

## QA evidence and limits
Browser smoke checked forgot/reset views at 1440×1000 and 390×844 in light/dark, with no horizontal overflow. URL-encoded plus-address email remains correctly populated. Verification notice and all complete registration/login/reset journeys are exercised by HTTP feature tests.
Complete live browser/inbox journeys were not claimed: development has no delivering mailer configured, and no live Google OAuth account was used. Browser QA did not modify any real account password. These are distinct from the passing full-flow HTTP tests using actual array-mail transport.
Evidence: storage/app/auth-reliability-qa/. Logs: storage/logs/auth-reliability-targeted.log, auth-reliability-regression.log, auth-reliability-build.log. Prior frontend work remains separate and uncommitted.

## Mail environment still required
To deliver real email, configure the deployment's actual mail service in .env. Do not paste secrets into chat or commit .env.

```
APP_URL=<the same canonical host users open in the browser>
MAIL_MAILER=smtp
MAIL_HOST=<provided by your mail service>
MAIL_PORT=<provided by your mail service>
MAIL_SCHEME=<supported TLS scheme, when required>
MAIL_USERNAME=<provided credential, if required>
MAIL_PASSWORD=<provided credential, if required>
MAIL_FROM_ADDRESS=<authorized sender>
MAIL_FROM_NAME=SIPORA
```

MAIL_URL may be used instead if your existing provider requires it. A local SMTP capture service can also be used for development. No credentials or addresses were invented. Keep SMTP and application logs private; the log mailer writes email bodies/links and must not be treated as real delivery or used for real password recovery in production. After editing environment values, run php artisan config:clear (or rebuild config cache for production).
Use one host consistently; localhost and 127.0.0.1 have separate cookie contexts. Do not bypass URL signature checks or force identity verification to work around host mismatches.

Unresolved prerequisite: real inbox delivery must be checked after valid mail configuration is supplied. All code checks are green.
