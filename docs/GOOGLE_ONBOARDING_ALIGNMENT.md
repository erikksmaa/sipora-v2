# Google Registration Onboarding Alignment

Date: 18 October 2026. No commit/push. No development database reset, migration, or reseeding in this phase.

## Audit before changes

- `AuthenticateGoogleUser` already created a minimal User + Youth role + `user_social_accounts` record in a transaction. It did not create a Youth profile, domicile, NIK, or interests. Password was already nullable, not fabricated.
- The callback previously always selected `HomeRoute` after login. There was no separate Google completion controller or giant Google-only Blade in the actual checkout. Incomplete users reached the Youth workspace/onboarding and the existing Biodata completion form. The earlier registration phase had already changed Biodata into five steps. This phase replaces that route into completion for pending Google users with the shared four-step registration view.
- Stateful Socialite validates OAuth state. Configuration remains `config/services.php` (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`). No credentials are copied to this report.
- `user_social_accounts` has unique `(provider, provider_user_id)` and `(user_id, provider)` constraints. It stores provider email and optional avatar URL, with no access/refresh token columns. Google imports deliberately set avatar URL to null; SIPORA initials remain the fallback.
- Existing provider identity is resolved before email linking, preserving the existing SIPORA email even if Google's email changes. First-time same-email linking requires a SIPORA-verified account and authoritative Gmail or matching verified Workspace domain. Unverified accounts, conflicting Google links, and new links to Admin/Verifier are rejected. These safeguards were retained.
- Google requires an explicitly verified email claim. The existing email-verified semantics remain; this does not verify the SIPORA identity.
- Existing account security already supports setting a local password for an OAuth-only user without requiring an invented current password. No new password feature was added.

## Resulting flow and shared implementation

Google OAuth → minimal pending Youth account → `/youth/registration` → **Data Diri → Alamat → Profiling → Konfirmasi** → identity document submission → existing Admin identity review. Existing role permissions and identity/portfolio feature gates are unchanged.

Both manual and Google registration now use:

- `resources/views/auth/register.blade.php`: the entire same wizard, including its stepper, NIK/DOB assistance, manual gender, dependent address selects, Interest cards and masked confirmation.
- `resources/js/registration.js`: existing shared Alpine state/validation, DOB parser, editable DOB protection, max-five selection and masked summary. No second JavaScript onboarding module.
- `YouthRegistrationRules`: required NIK, age 16–30, manual male/female, canonical Pemalang district/village parent validation, optional detail/RT/RW/postal code, 1–5 distinct real Interests.
- `CompleteYouthRegistration`: locked, transactional profile/visibility/domicile/NIK/Interest writes, using the existing profile, address, identity-anchor and sync-Interest actions.
- Existing `IdentityAnchorService` and unique database hash constraint: encrypted NIK, privacy-safe duplicate error, one NIK per Youth identity.
- Existing offline 14-district/223-village reference and actual Interest records; no external address API.

Google differences are limited to authentication context: name prefilled/editable, email readonly from the authenticated server account, no password/confirmation fields, no public signup CAPTCHA repeated after authenticated OAuth, and contextual Google welcome copy. Google-only signup still has a null password. Submitted email/password/role fields are excluded from validated completion data, so they cannot alter email, credentials or privileges. Place of birth and phone remain existing editable Biodata fields, but are not new signup requirements; the manual registration contract does not require them either.

The confirmation shows name, stored account email, masked client NIK, DOB, manual gender, district, village and selected Interests. It renders no token, provider ID or password. Identity status stays unverified until the existing document-review process.

## Persistence, retries, resume and legacy compatibility

- OAuth persists only User, role, verified email and provider link. Final confirmation writes domain records in one transaction. A failure rolls back every domain write, retaining the safe pending account/provider link for retry.
- Pending is deliberately distinct from full Youth feature readiness: a linked Youth without full name, DOB or primary domicile needs registration completion. This reuses the established legacy Biodata completion contract. Newly completed users have all newly required NIK/village/Interest data because final server validation requires it.
- Existing Google or local-plus-Google users with the previous required profile/domicile data bypass registration. Missing newly introduced village/NIK/Interest fields do not force a completed legacy user back through signup. Existing identity gates can still request missing identity information normally.
- Repeat OAuth resolves the same provider account. The callback sends an incomplete user back to the shared wizard. Saved partial profile, DOB, gender, domicile and Interests are loaded; no destructive restart occurs.
- Draft fields are held in the browser until final confirmation, not persisted per step. Closing the tab loses unsent edits; previously saved records remain. NIK is deliberately not reflashed/prefilled into HTML after an error. Users can revisit steps freely, while final submission revalidates all required steps.
- Final writes lock the User row. Repeated valid submission after completion leaves records untouched rather than creating another profile/address/Interest set. Existing sync actions also use idempotent relationship writes.
- The Youth route group redirects pending Google users to completion before accessing workspace pages, including the prior Biodata entry. Completion itself is exempt, so there is no redirect loop. Public browsing and logout remain available.
- The previous callback used `HomeRoute`, not an intended redirect; that behavior is preserved for completed accounts. No intended URL is consumed or discarded by the new pending redirect.
- No old Google-specific completion route/view existed to delete. The prior Biodata route remains functional for all completed accounts.

## Security findings

No OAuth architecture changes. Stateful Socialite, trusted email ownership checks, provider uniqueness and internal-account protections are preserved. Public signup creates Youth only. Provider tokens are never persisted/rendered/logged by the new flow. Existing failure logging records exception class only, excluding callback URL/code/account data. No remote Google avatar is required. Existing CSRF, auth, email verification, role checks, NIK encryption/private-document storage and upload permissions remain.

## Files in this phase

Added:
- `app/Services/Youth/YouthRegistrationRules.php`
- `app/Services/Youth/CompleteYouthRegistration.php`
- `app/Services/Youth/GoogleOnboardingService.php`
- `app/Http/Requests/Auth/CompleteGoogleRegistrationRequest.php`
- `app/Http/Controllers/Auth/GoogleOnboardingController.php`
- `app/Http/Middleware/EnsureGoogleOnboarding.php`
- `tests/Feature/GoogleOnboardingAlignmentTest.php`
- This report.

Updated:
- `app/Actions/Auth/RegisterUser.php`
- `app/Http/Requests/Auth/AuthRequest.php`
- `app/Http/Controllers/Auth/GoogleAuthController.php`
- `resources/views/auth/register.blade.php`
- `routes/youth.php`
- `tests/Feature/GoogleAuthenticationTest.php`

Other existing uncommitted changes from earlier phases were retained. No migrations added or run.

## Validation

- Initial focused Google/registration/onboarding/identity/Development Pathway selection: **40 passed, 424 assertions**.
- Expanded Google suite: **22 passed, 232 assertions** (`storage/app/google-auth-final.log`). Includes legacy callback bypass, safe linking, repeat provider identity, readonly/server-owned email, no password, strict NIK/age/gender/address/Interest validation, including malformed address input, transaction failure rollback, retry idempotence, role restrictions, saved partial data, remote avatar fallback and manual/Google domain parity.
- Existing shared Node tests exercise NIK DOB prefill, manual correction preservation, no gender derivation, max-five Interests, dependent local village reset and masked summary.
- All Node tests: **41 passed, 0 failed** (`storage/app/google-frontend.log`).
- Vite production build: **PASS**, 14.90 seconds (`storage/app/google-build.log`).
- Scoped Pint: PASS. Blade cache: PASS. `git diff --check`: PASS.
- Full Laravel regression: **419 passed, 3,625 assertions**, 369.88 seconds (`storage/app/google-regression-final.log`).

## Responsive and dark-mode QA

Browser QA used the real shared Blade rendered by the isolated test fixture with synthetic Google identity/reference data, temporarily served as a local HTML fixture. No live OAuth credentials, development user creation, real NIK or documents were needed. The temporary public fixture was removed after QA. Server behavior/callback/authorization is covered by integration tests; live Google consent was not exercised.

Checked 1440×1000, 768×1024, 390×844, 360×800, 412×915: no horizontal overflow. Email readonly and zero password fields verified in DOM. NIK assistance filled DOB, manual correction remained through subsequent steps, and gender remained manually chosen. Local Moga/Banyumudal selection worked. Sixth Interest was refused with Indonesian feedback. Confirmation masked NIK and displayed the corrected DOB. Dark Data Diri, Alamat, Profiling/error state and Konfirmasi were checked; no console warnings/errors. Authenticated Google layout uses the existing authenticated public header; guest-only bottom navigation is not applicable. Browser viewport/theme restored and returned to the regular registration page.

Evidence in `storage/app/`:
- `google-onboarding-desktop.png` — empty NIK, prefilled Google name/email, no password.
- `google-onboarding-mobile-dark.png` — selected Interest cards and max-five feedback.
- `google-onboarding-confirmation-360.png`, `google-onboarding-confirmation-412.png` — masked mobile confirmation.
- `google-onboarding-confirmation-desktop.png` — desktop confirmation.

## Remaining limits

No staged draft autosave. Newly typed unsent fields are not recovered after browser closure; saved data/provider account are preserved. Live Google consent requires a configured real Google account and was not tested here. Legacy completion detection intentionally accepts the previous profile/domicile contract so new fields do not lock out existing completed users. Identity verification is separate and still reviewed by the authorized Admin. No commit or push.
