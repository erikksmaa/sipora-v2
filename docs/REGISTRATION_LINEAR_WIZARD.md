# Registration Wizard — Linear Flow + Birth Place

18 October 2026. This fix supersedes the earlier optional step-preview behavior documented in the registration/Google alignment reports.

## Behavior

- Data Diri → Alamat → Profiling → Konfirmasi uses the same Blade and Alpine implementation for manual and Google registration.
- Step indicators are plain progress list items, with no links, buttons or click handlers. Current is accent/orange; completed earlier steps show checkmarks; future steps are neutral.
- Only **Selanjutnya** moves forward, after both current/prior native checks and server validation. **Sebelumnya** moves back only to an approved preceding step. An early form submission/Enter cannot jump forward or create an account.
- Validation stays on the failed step, displays Indonesian feedback and scrolls/focuses the first invalid control. Interest validation focuses its card control.
- Inputs remain mounted with `x-show`, preserving values across backward/forward navigation. Revalidating an edited earlier step invalidates later approvals, while keeping their field values.
- **Tempat lahir*** is a normal required text field, placeholder `Contoh: Pemalang`, maximum 160 characters. It is included in the confirmation, restored from safe old input/saved Google profile data, validated by the shared server rules and persisted to existing `user_profiles.birth_place`. It is never derived from NIK.
- NIK rules, editable DOB assistance, manual male/female gender, local Pemalang district/village data and canonical Interests (1–5) remain shared.
- Manual registration still requires password + confirmation. Google name remains editable, email is readonly/server-owned, and no password is required.

## Server-enforced sequence

`POST /registration/steps/{0|1|2}` validates the selected step using the same rules as final manual/Google submission. A step requires session approval of all preceding steps for the **same submitted values**. Approval writes only a keyed HMAC fingerprint to the session, never raw NIK/password/draft data, and never creates domain records.

Final manual signup and pending Google completion require all three approvals and still validate every mandatory field, NIK uniqueness, age, address hierarchy and Interest relationship. Changing approved data without revalidating, submitting a complete payload directly without approvals, requesting a future step, or supplying a forged step index cannot bypass this check. Google approvals are scoped to the authenticated User UUID; manual approvals have their own session scope. The endpoint retains CSRF protection, validates authenticated Google/Youth/email access and is throttled.

Manual account/profile/identity/address/Interest records are created only at the existing final transaction. OAuth retains the existing safe pending User/provider state; final domain persistence remains transactional. Existing completed Google users keep their normal login behavior. The nullable legacy birth-place field is not backfilled or newly required for completed legacy accounts.

Refresh starts the client wizard at Data Diri, with no query-controlled step. Unsent edits are not autosaved. Missing required data is always rejected even if session approvals exist. The existing security policy still excludes NIK/password from flashed old input.

## Changes

Added:
- `app/Services/Youth/RegistrationWizard.php`
- `app/Http/Controllers/Auth/RegistrationStepController.php`
- `tests/Feature/RegistrationLinearWizardTest.php`

Updated:
- `resources/views/auth/register.blade.php`
- `resources/js/registration.js`
- `resources/css/app.css` (retired obsolete clickable-stepper rules)
- `app/Services/Youth/YouthRegistrationRules.php`
- `app/Services/Youth/CompleteYouthRegistration.php`
- `app/Http/Requests/Auth/AuthRequest.php`
- `app/Http/Requests/Auth/CompleteGoogleRegistrationRequest.php`
- `app/Http/Controllers/Auth/GoogleOnboardingController.php`
- `routes/auth.php`
- `tests/TestCase.php` (fixture birth place + explicit HTTP step helper; test database guard unchanged)
- Existing registration/authentication/Google feature tests now pass through actual step validation before successful final submission.
- `tests/Frontend/registration.test.mjs` adds sequential navigation, backward value preservation, invalid focus/scroll, no direct skip, server/network failure and independent birth-place coverage.

No migration, development database reset/seeding, commit or push.

## Validation

- Focused authentication/registration/Google/onboarding: **52 passed, 629 assertions** (`storage/app/linear-focused.log`).
- Strict sequence integration tests: **4 passed, 103 assertions** (`storage/app/linear-sequence.log`). Cases include future-step requests, direct final POST without approval, missing Data Diri/Alamat/Profiling, field tampering after approval, invalidated future approvals after going back, refresh/query manipulation, no account before final confirmation, manual/Google birth-place persistence, no Google password or email override, and no raw credentials/NIK in approval session state.
- All frontend tests: **44 passed, 0 failed** (`storage/app/linear-frontend.log`).
- Production build: **PASS**, 12.34 seconds (`storage/app/linear-build.log`).
- Scoped Pint and `git diff --check`: PASS.
- Full backend regression: **423 passed, 3,796 assertions**, 309.15 seconds (`storage/app/linear-regression-final.log`).
- Final Blade compilation: PASS.

## Browser QA

Actual local manual registration was checked in desktop 1440×1000 and mobile 390×844, including dark mode. No horizontal overflow. Clicking Profiling/Konfirmasi indicators kept Data Diri active; there are zero stepper links/buttons. An empty step displayed `Kolom Nama lengkap wajib diisi.`. After entering a synthetic name/NIK, Tempat Lahir remained empty (DOB assistance filled DOB only), Next stayed on Data Diri with `Kolom Tempat lahir wajib diisi.` and focused `birth_place`. No account was submitted and no credential was entered through browser QA. Full forward/back/API behavior for manual and Google is covered by integration and Node tests; no live Google consent was performed.

Evidence:
- `storage/app/registration-linear-desktop.png`
- `storage/app/registration-linear-mobile.png`
- `storage/app/registration-linear-mobile-dark.png`

Browser returned to the empty manual registration form, light theme and normal viewport.
