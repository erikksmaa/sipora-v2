# Printable attendance sheet

Implementation: AttendanceSheetController; dedicated resources/views/print/attendance-sheet.blade.php. No PDF dependency, database migration, or digital attendance mutation.

## Routes and authorization

- GET /manage/{organization}/activities/{activity}/attendance-sheet (manager.activities.attendance-sheet): ActivityPolicy::view, active Community leader/manager.
- GET /manage/{organization}/programs/{program}/attendance-sheet (manager.programs.attendance-sheet): ProgramPolicy::manageApplications.
- GET /verifier/programs/{program}/attendance-sheet (verifier.programs.attendance-sheet): same Program policy, non-conflicting Verifier with approved latest proposal.

Existing auth, verified, role and manager onboarding middleware remain. Authenticated unauthorized actors receive 403; mismatched organization nesting receives 404. Admin has no Program application-management permission in the existing policy, so this feature does not broaden Admin privileges.

Buttons: manager/participants/index.blade.php and programs/applications.blade.php; open dedicated print view in a new tab.

## Data and privacy

Activity: registration_status=accepted, excluding soft-deleted participations. Program: canonical participants() relation, status=accepted only. Missing users excluded. No application pagination or search/status filters are used. Full list sorted by displayed full name, case-insensitively and naturally, with UUID as deterministic tie-breaker.

Name uses profile full_name, falling back to account name. include_phone=1 explicitly opts into the optional phone column, otherwise no phone field is queried. Missing phone is blank. Only name and optional phone are projected into print rows; no email, NIK, DOB, private address, documents, or verification metadata. Responses use private/no-store and noindex/nofollow.

Actual organizer is the owning Community. Activity date is displayed in Asia/Jakarta; Program uses its actual date range. Missing venue/date shown as an em dash; no address or session inferred for a Program.

## Layout and use

A4 portrait, 15 mm margins, black/white, 11 pt body, 10 mm handwriting rows, wide signature column, strong borders, repeating table header, avoid splitting rows. Dedicated HTML has no workspace layout/assets/navbar/sidebar/chat. Screen-only phone controls and Cetak / Simpan PDF button are hidden in print. Browser window.print() handles printing or Save as PDF. Large lists paginate through browser print layout, never application pagination. Physical sheets do not replace session attendance.

## Validation

Focused AttendanceSheetTest: 3 passed, 98 assertions, covering 5/30/55 accepted participants, filtering, full-list independence, manager/Verifier access, unauthorized users, cross-community nesting, empty lists, phone defaults/missing values, private-field absence, standalone markup, and no attendance writes.
Frontend: 45 passed. npm run build passed. Blade compilation and git diff --check passed.
Full backend: 428 passed, 4239 assertions (604.16 seconds), using the guarded sipora_regression_test database.

Browser QA: real local Program sheet shows all 7 currently accepted participants sorted by name, including the participant beyond page one; phone off by default and opt-in works. Activity sheet also checked separately. Screenshot: storage/app/attendance-program-preview.png.

No commit or push performed.

## Leader and Community Manager access verification

Both active Community leaders and managers already use the manager workspace and are authorized by the existing Activity and Program policies. Added an explicit HTTP regression proving that each role sees the print buttons on both participant pages and can open both sheets. Ordinary members and removed managers receive 403. No production permissions were changed. Latest focused suite: 4 passed, 122 assertions (29.66 seconds); Pint and git diff --check passed. The full 428-test result above precedes this additional regression test.
