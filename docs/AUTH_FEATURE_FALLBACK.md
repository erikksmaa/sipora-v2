# Temporary auth provider fallback

`config/features.php` defaults `FEATURE_GOOGLE_OAUTH` and `FEATURE_RECAPTCHA` to `false`. Missing flags disable both integrations even when old provider credentials remain configured. The real `.env` and `APP_DEBUG` are unchanged.

## Disabled behavior

- Google buttons are hidden on login and manual registration. Both OAuth entry and callback redirect to login with `Login dengan Google sedang dinonaktifkan sementara.` before Socialite is invoked.
- Existing Google users and pending onboarding records are retained. Existing onboarding completion remains available to authenticated pending users. Google-only accounts without a local password can use the existing password reset flow, provided outbound email works.
- No reCAPTCHA script, site key, token field or submit interception is rendered. The token is excluded from validation and the auth request skips verification. The service also returns immediately when the feature is disabled.
- Normal credentials, registration wizard sequence, field validation, CSRF, session handling, email verification and endpoint throttles remain in place. No provider code, routes or credentials were deleted.

## Deployment and config cache

This code must still be deployed to production. Local edits do not change the hosted website. Use the normal deployment process to refresh Laravel config/view caches; do not introduce a public cache-clearing endpoint.

An older cached configuration has no `features` keys, which the guards treat as disabled. When flags change later, rebuild the production config cache so Laravel reads the new values. Refresh compiled views as part of deployment as usual.

When server access returns:

```dotenv
FEATURE_GOOGLE_OAUTH=true
FEATURE_RECAPTCHA=true
```

All existing `GOOGLE_*` and `RECAPTCHA_*` settings remain supported and must be valid for the production domain. The legacy `RECAPTCHA_ENABLED` setting is retained: set it to `true` for the frontend when re-enabling (the competition example intentionally has it `false`). With the feature enabled, the previous production fail-closed verification behavior is preserved. Missing or invalid keys are not silently accepted.

Manual registration still requires the existing production application key, database/session configuration and outbound email for email verification and password recovery. This fallback does not replace those dependencies.

## Validation

`AuthFeatureFlagsTest` exercises disabled behavior under a production environment with real CSRF checks and an isolated test database. Existing provider tests explicitly enable their respective features, retaining coverage for Socialite state, account linking, captcha action/hostname/score checks and fail-closed responses. No live provider credentials or production account data are used.

Results (20 October 2026):

- 60 existing backend tests passed: AuthenticationTest, AuthenticationReliabilityTest, GoogleAuthenticationTest, RecaptchaTest, RegistrationLinearWizardTest, RegistrationConsolidationTest and GoogleOnboardingAlignmentTest.
- 13 new AuthFeatureFlagsTest cases passed, 125 assertions. The initial run had two fixture setup errors (seeding after switching the environment to production); fixtures now seed before that simulation, and all 13 cases passed on rerun.
- 45 frontend tests passed: `node --test tests/Frontend/*.test.mjs tests/js/*.test.mjs`.
- `npm run build`, PHP syntax checks on changed runtime PHP/new test, and `git diff --check` passed.
- Inspected the complete source diff: only environment references, flags and synthetic test credentials added; no actual secrets introduced. Routes, rate limit definitions, CSRF middleware, real `.env` and `APP_DEBUG` were not edited.
- Tests ran against guarded `sipora_regression_test`, not the demo or production database. No live OAuth/reCAPTCHA request or deployment was performed.

## Files changed

| File | Change |
|---|---|
| `config/features.php` | New default-off flags |
| `.env.example` | Document disabled feature defaults |
| `.env.competition.example` | Document disabled feature defaults |
| `app/Http/Controllers/Auth/GoogleAuthController.php` | Guard redirect and callback before Socialite |
| `app/Http/Requests/Auth/AuthRequest.php` | Exclude token and skip service while disabled |
| `app/Services/Security/RecaptchaService.php` | Feature guard before network verification |
| `resources/views/auth/google-link.blade.php` | Hide Google login option |
| `resources/views/auth/register.blade.php` | Gate Google registration and captcha markup |
| `resources/views/components/auth-form.blade.php` | Gate shared captcha rendering/interception |
| `tests/Feature/AuthFeatureFlagsTest.php` | New disabled/reenabled production behavior tests |
| `tests/Feature/AuthenticationTest.php` | Explicitly enable captcha for existing tests |
| `tests/Feature/AuthenticationReliabilityTest.php` | Explicitly enable captcha for existing tests |
| `tests/Feature/GoogleAuthenticationTest.php` | Explicitly enable OAuth for existing tests |
| `tests/Feature/RecaptchaTest.php` | Explicitly enable captcha for existing tests |
| `docs/AUTH_FEATURE_FALLBACK.md` | Operational notes and validation report |
