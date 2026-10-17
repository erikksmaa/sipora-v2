# SweetAlert2 default visual restoration

Date: 2026-10-16

## Audit and implementation

- Removed `buttonsStyling: false`, SIPORA `customClass` mappings, and empty toast `showClass` overrides from the shared alert helper and documentation demo.
- Removed pixel borders, decorative shadows, custom spacing, toast grid/width, custom typography, icon colors, progress-bar colors, custom buttons, and dark popup overrides from `alerts.css`.
- Dialogs and toasts now use the installed SweetAlert2 vendor stylesheet, including standard buttons, icons, backdrop and animations. Keep the default white appearance in both SIPORA themes.
- Retained only the existing container z-index, reduced-motion accommodation, and vendor-compatible `content-box` icon sizing. The latter prevents universal border-box resets on the documentation page from deforming success icons.
- Kept all messages, triggers, routes, controllers, redirects, form submission and confirmation logic unchanged. Toasts still close after 2000 ms, show a close button/progress bar, pause on mouseenter and resume on mouseleave.
- Confirmation still uses warning, cancel-first focus, reversed button order and disabled outside-click dismissal. Escape, cancel and confirm work. Existing confirmation tests verify cancellation and exactly-once submission with the original named submitter.
- Public GSAP reveals target explicitly marked elements; Swal containers are appended outside page-motion wrappers and do not carry those markers. No motion code changes were necessary.
- No Swal loading integration exists in the current source; there was no loading workflow to modify.

## Files changed for this task

- `resources/js/alerts.js`
- `resources/css/alerts.css`
- `resources/js/design-system.js`
- `resources/css/design-system.css`
- `resources/views/design-system/components.blade.php`
- `resources/views/design-system/index.blade.php`
- `resources/views/design-system/foundations.blade.php`
- `resources/views/design-system/documentation.blade.php`
- `tests/js/confirmation.test.mjs`: brought the form mock up to date with the existing `hasAttribute('data-confirm')` check; also covers forms without the attribute. Production confirmation code was not changed.
- This report.

## Validation

- `npm run build`: PASS.
- `php artisan view:cache`: PASS.
- `node --test tests/js/confirmation.test.mjs tests/Frontend/*.test.mjs`: 18 PASS.
- Full `php artisan test`: 352 PASS, 3 FAIL, 3073 assertions, 394.80 s. Ran against isolated `sipora_attendance_test`, not development `sipora`; temporary configuration and TestCase changes were restored byte-for-byte.
- Targeted `AlertRenderingTest`: 3 PASS, 13 assertions.
- The three full-suite failures reproduce with the previous alert code/styles as well (3 failures, 74 assertions):
  - `PublicInformationPagesTest:46`: exact old `<main>` markup excludes the existing `data-page-motion` attribute.
  - `YouthWorkspaceRenderingTest:39`: same attribute-order assumption.
  - `YouthExperienceCorrectionTest:148`: still expects `logo.png`, while the existing layout uses `sipora-growth-mark-v1.png`.
- Those unrelated layout/logo assertions were left unchanged. Full regression is therefore not green.
- Browser: success/error/warning/info toast icons, warning/delete demo confirmation, confirm/cancel, Escape, cancel focus, timer dismissal, hover pause, default button styles and default light appearance on dark backgrounds checked. Mobile dialog fits 320 and 390 px; desktop checked at the default viewport.
- Actual form checks: requesting reset for a nonexistent QA address returned the existing success flash, without sending mail to an account; an email longer than 255 characters returned the existing server-validation error toast.
- No database schema or business workflow changes. No commit created.

## Screenshots

- `storage/app/swal-default-desktop.png`
- `storage/app/swal-default-mobile-toast-dark.png`
- `storage/app/swal-default-mobile-confirm-dark.png`

Remaining issue: the three pre-existing layout/logo test assertions listed above. No unresolved visual issue was found in the SweetAlert2 checks.
