# Indonesian validation localization

Date: 2026-10-16

## Configuration and audit

- Set `APP_LOCALE=id` in local `.env` and `.env.example`; change the default in `config/app.php` to `id`. Keep the existing `en` fallback and Faker locale. Cleared the configuration cache.
- No application-level `lang` directory existed. Default Laravel validation/auth/password/pagination strings therefore fell back to English.
- Added `lang/id/validation.php`, `attributes.php`, `auth.php`, `passwords.php`, `pagination.php`, and `lang/id.json`.
- The validation catalog covers all validation messages shipped with the installed framework, verified by a key-coverage test. The 173 attribute mappings were audited against actual FormRequest fields, controller/helper validators, Program intake rules and generated visibility fields.
- Examples: `password_confirmation` → konfirmasi kata sandi; `organization_id` → Community; `registration_open_at` → waktu pembukaan pendaftaran; `attendance.*.status` → status presensi peserta. JSON request/response keys remain unchanged.
- Translate validation summary suffixes and pagination strings through JSON language keys; no exception response renderer or API schema changes.

## Custom and frontend messages

- `BinaryUuidExists` now uses translated `validation.exists` rather than an English literal.
- Translate Google authentication failure/linking feedback and server/client reCAPTCHA feedback. Developer log entries and technical identifiers remain unchanged.
- Normalize password/recovery copy to “kata sandi” and “tautan” in the existing services/controllers. Preserve generic unknown-email reset feedback and all security gates.
- Keep useful existing Indonesian domain messages. Correct identity upload message keys from the unused `mimes` key to the actual `extensions` and `mimetypes` rules; allowed formats and limits are unchanged.
- Add a custom birth-date message so users see “hari ini” instead of the literal `today` rule parameter; localize conditional decision/location values.
- `resources/js/validation.js` translates native `ValidityState` errors and clears its own custom validity on edit/change/reset. It does not add constraints, change values or disable browser validation. Password labels are readable even on legacy auth forms.
- Localize Chat network/JSON parsing feedback while retaining drafts and server validation details. Existing reusable error components already render server messages safely without English prefixes; no redesign was needed.
- SweetAlert2 consumes the translated server messages and retains its default visual style. Localize the toast close button's accessible label as well.

## Files modified in this task

- Configuration: `.env` (local, ignored), `.env.example`, `config/app.php`.
- Language resources: the six files under `lang` listed above.
- Backend: `app/Rules/BinaryUuidExists.php`, `app/Actions/Auth/AuthenticateGoogleUser.php`, `app/Services/Security/RecaptchaService.php`, `app/Services/InternalAccountService.php`, `app/Services/ChatService.php`, `app/Http/Controllers/Auth/GoogleAuthController.php`, `app/Http/Controllers/Auth/PasswordResetController.php`, `app/Http/Controllers/InternalPasswordController.php`, `app/Http/Controllers/Youth/AccountSecurityController.php`, `app/Http/Requests/Youth/SubmitIdentityVerificationRequest.php`.
- Frontend: `resources/js/app.js`, `resources/js/validation.js`, `resources/js/chat.js`, `resources/js/alerts.js`, `resources/views/components/auth-form.blade.php`.
- Tests: `tests/Feature/IndonesianValidationTest.php`, `tests/Frontend/validation.test.mjs`.
- This report. Other pre-existing workspace edits were preserved.

## Automated validation

- Representative localization tests: 7 PASS, 41 assertions. Cover locale/catalog coverage, required/invalid/unique email, password confirmation/min/max, uploads, wildcard attribute labels, custom UUID validation, relational Program/Activity dates, existing domain validation, Chat JSON, generic reset feedback and unchanged credentials after an invalid reset token.
- Final targeted run (`IndonesianValidationTest|GoogleAuthenticationTest|AlertRenderingTest`): 21 PASS, 141 assertions.
- Frontend tests (`tests/js/*.test.mjs` and `tests/Frontend/*.test.mjs`): 21 PASS. Include native-message lifecycle and retention of an unsent Chat draft on a network error.
- Full Laravel regression: 359 PASS, 3 FAIL, 3114 assertions, 294.62 seconds.
- Full-suite failures match the existing baseline documented in `SWEETALERT2_DEFAULT_STYLE_QA.md`: `PublicInformationPagesTest:46` and `YouthWorkspaceRenderingTest:39` expect old `<main>` markup; `YouthExperienceCorrectionTest:148` expects the old `logo.png` asset. No unrelated assertion or layout was changed to hide these failures.
- All PHP tests ran against isolated `sipora_attendance_test`. Temporary `TestCase.php` and `phpunit.xml` changes were restored byte-for-byte; development `sipora` was not refreshed or seeded.
- Vite build, Blade compilation, PHP/JavaScript syntax and scoped diff whitespace checks: PASS.

## Browser QA

| Form/context | Check |
| --- | --- |
| Login | Empty email/password and malformed email show Indonesian native messages; rejected credentials show Indonesian server feedback. |
| Register | Empty name blocked with localized required feedback; no account created. |
| Forgot password | Overlength email returns “Kolom email maksimal hanya boleh berisi 255 karakter.” from the server; mobile wrapping verified. |
| Reset password | Empty email blocked with localized feedback; no credential entered or changed. |
| Youth biodata/profile | Empty name/bio blocked; existing records unchanged. |
| Community profile | Empty name blocked. |
| Activity | Empty required fields blocked with localized native messages. |
| Program | Empty name blocked. |
| Proposal | Negative budget blocked with localized native message. |
| Logbook | Progress 101 blocked by the existing 0–100 constraint with localized feedback. |
| E-LPJ | Available demo report is in revision and requires creating another version before editing. No version was created solely for QA. Existing E-LPJ validation/workflow tests passed in the isolated regression; invalid-form browser submission remains unverified for this read-only record. |
| Chat | Empty-send remains disabled. Required/overlength server validation and JSON structure verified by automated endpoint tests. No chat message sent. |
| Admin Opportunity | Empty required fields blocked. |
| Admin internal account | Empty name blocked; no account or credentials created. |

Screenshots: `storage/app/validation-id-login-native.png`, `storage/app/validation-id-mobile-server.png`, `storage/app/validation-id-admin-mobile.png`.

## Intentional limits

- Product names (Youth, Community, Activity, Opportunity, Program, etc.), payload keys, source identifiers, log/developer diagnostics, technical format names and third-party reCAPTCHA iframe content were not translated.
- Existing non-validation UI copy such as “Sign in”, “Remember me” and some legacy password form headings remains outside this localization scope.
- No database/schema changes, new translation dependency, rule changes, authorization changes, workflow changes or commit.
- Remaining issues: the three pre-existing regression assertions above and the E-LPJ manual invalid-form check constrained by its existing workflow.
