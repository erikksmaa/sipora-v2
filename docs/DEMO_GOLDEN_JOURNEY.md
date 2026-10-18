# Demo Golden Journey — SIPORA v2

Updated 18 October 2026, Asia/Bangkok. Implemented and refreshed additively on the existing local demo database. No migration, reset, commit or push.

## Primary scenario

- **Program:** Pemalang Digital Youth Academy 2026
- **Activity:** Workshop Full-Stack Web Development untuk Pemuda
- **Youth:** Erik Kusuma Rais (`youth1@sipora.test`)
- **Community:** Komunitas Pemuda Digital Pemalang
- **Manager:** Aditya Pratama (`demo.youth01@sipora.test`)
**Government reviewer:** `verifier@sipora.test`

- Public Program: `/programs/pemalang-digital-youth-academy-2026`
- Public Activity: `/activities/academy-workshop-full-stack-web-development-untuk-pemuda`
- Manager Program intake: `/manage/komunitas-pemuda-digital-pemalang/programs/pemalang-digital-youth-academy-2026/applications`
- Manager Activity participants: `/manage/komunitas-pemuda-digital-pemalang/activities/academy-workshop-full-stack-web-development-untuk-pemuda/participants`
- Youth Passport: `/youth/passport`; certificates: `/youth/certificates`.

### Actual government ownership/permissions

The current Program model requires `organization_id`; it has no government-owner type, direct government creation workflow or separate government Program table. The supported government role is Verifier oversight, proposal approval and application selection. This scenario follows that model: the existing verified Community owns/implements the Program, and the existing non-conflicting Verifier approves its proposal and makes selection decisions. Admin can inspect it through the government directory. No extra privilege, ownership column or fake government status was introduced.

## Lifecycle and quota

| Item | Seeded state |
| --- | --- |
| Program | `running`, approved latest proposal, public |
| Selection | Program `selection_mode=review` |
| Quota | 30 |
| Applications | 16 distinct Youth |
| Accepted | 6, including Erik |
| Submitted | 3 |
| Under review | 2 |
| Waitlisted | 2 |
| Rejected | 2 |
| Withdrawn | 1 |
| Remaining quota | 24; only accepted applications consume quota |
| Linked Activity | Approved, published, `completed`; Activity mode is `approval_required` |
| Activity participants | 6 accepted and completed |
| Sessions | 4 |
| Attendance | 24 records; Erik has 4 present, 0 absent, 0 excused |
| Other attendance | Five participants attend all four; Bagas has three present and one excused |
| Certificates | 6 issued/verified SIPORA certificates, linked to their actual participation |
| Erik Passport | Derived accepted/completed participation, linked certificate, 4/4 attendance |

Program remains running because Academy continues into another batch while the first workshop batch is complete. Intake remains open and quota is not filled. On first seeding, the workshop starts 35 days before the demo date; its dates are retained on reruns. On this machine the completed sessions are **13–16 September 2026**, 09:00–15:00 WIB (02:00–08:00 UTC), with valid check-in/out within each session. Program runs 19 August 2026–16 January 2027 and intake is open 19 August–12 December 2026. A rerun extends an expired intake window for the running demo without moving past sessions into the future.

Sessions:

1. Fundamental Web Development
2. Backend dengan Laravel
3. Frontend & Integrasi
4. Final Project dan Evaluasi

The current completion rule is an explicit Manager decision after Activity execution is completed and participation accepted. Attendance is supporting evidence; no automatic attendance-percentage threshold exists. The fixture supplies full evidence for Erik and uses the existing completion actions. Certificates are issued directly; there is **no claim-certificate workflow** to simulate. PDF files are generated through the existing certificate renderer rather than fabricated files.

Program acceptance does not automatically enroll a Youth in an Activity. The fixture deliberately performs both real workflows for the same Youth under the linked Program.

## Exact competition walkthrough

1. Sign in as Erik with his current local credential and open the public Program URL above. Show **Diterima**, review-mode intake and quota 30/sisa 24.
2. Point out the linked Workshop in “Activity dalam Program”. Open it and show the “Bagian dari Pemalang Digital Youth Academy 2026” link back to Program.
3. Switch to the existing Manager account (`demo.youth01@sipora.test`). Open the Program applications URL; filter `q=Erik`. Show accepted status and the real six-status distribution.
4. Open Manager → Activity → Workshop → Peserta. Erik is accepted and “Selesai terverifikasi”, with 4 present across 4 sessions. The list also shows varied attendance for Bagas.
5. Open Detail Activity → Sesi, then each session’s Presensi. All timestamps are in the past and each session has six attendance records.
6. Return to Erik’s Youth workspace → Activity Saya / Passport.
7. Open the Full-Stack Workshop Passport entry: show completed participation and four sessions attended.
8. Follow “Lihat sertifikat”; show his certificate, “Unduh PDF” and public verification. Public certificate verification also works from Erik’s row in the Manager participant list.
9. Return to Passport: the record comes from the real participation, not a separately inserted Passport record. The current Passport UI shows Activity/Community; open the public Activity to reveal its Program context.
10. Optionally open `/statistics` and `/ecosystem-map`. The new Program, Activity, participants and district context contribute through existing dynamic queries; no analytics counters were inserted.

For a second live participant without switching to Erik’s customized credential, Aditya also has the same Academy application, 4/4 completed participation, certificate and Passport entry. This is an alternate demonstration account, not a replacement for Erik’s primary fixture.

### Accounts and credentials

| Purpose | Existing account | Current-machine note |
| --- | --- | --- |
| Primary Youth | `youth1@sipora.test` | Existing customized password preserved; use the credential already set locally |
| Community Manager / second Youth participant | `demo.youth01@sipora.test` | Existing standard development credential; browser login verified |
| Government Verifier | `verifier@sipora.test` | Existing customized password preserved |
| Admin | `admin@sipora.test` | Existing standard development credential |

Fresh demo account creation continues to use `UserSeeder::PASSWORD`, only for development fixtures. No production credential is documented here. This refresh does not change any existing password. Community Manager is an organization-membership permission, not an added system role.

## Seeder audit and changes

Audit covered models, migrations/check constraints, `ProgramIntakeService`, `ProgramPolicy`, Program discovery, proposal/execution actions, Activity eligibility/registration/review/attendance/completion/certificate actions, Passport query, asset manifest and existing demo accounts.

- `CompetitionDemoSeeder` was the existing canonical competition entrypoint. It already seeded useful communities, Programs, Activities, Passport, social/forum/chat, media and optional proposal/logbook/evaluation scenarios. These remain.
- `DemoEcosystemSeeder` represented Program execution states and completed Activities, but did not configure the latest Program intake/quota or seed `program_applications`. Older completed participation examples use direct fixture writes. They are retained as existing regression scenarios; the new golden journey uses domain actions.
- `DemoYouthSeeder` already supplied DOB, birth place, gender, interests, skills and mixed verification/readiness states, but primary addresses did not have the new canonical village relation. Some asset-backed Youth had no canonical Interest rows.
- `DemoIdentityAnchorSeeder` already supplied encrypted 16-digit synthetic anchors. New anchor generation now supports the female day offset; valid existing anchors remain untouched. No NIK is printed in output or this document.
- `IdentityVerificationSeeder`, `ActivityAttendanceScenarioSeeder` and `YouthPassportScenarioSeeder` still contain historical `DEV-*` identity fixtures. They are legacy focused QA entrypoints, not the latest competition reset. The canonical competition entrypoint runs anchor repair and onboarding compatibility afterward. Their intentionally varied historical scenarios and private-document records were not deleted or fabricated anew.
- `DatabaseSeeder` remains the general development/QA seed entrypoint; it is not silently replaced by the competition scenario.

Added:
- `database/seeders/GovernmentProgramJourneySeeder.php`: orchestrates the new fixture with stable Program/Activity slugs and existing workflow services.
- `database/seeders/DemoYouthOnboardingSeeder.php`: repairs missing birth place/DOB/gender, canonical village and minimum canonical Interests for the competition/asset-backed demo Youth; preserves privacy, verification status, optional incomplete biography and existing relationships.
- `tests/Feature/GovernmentProgramJourneySeederTest.php`.

Updated:
- `database/seeders/CompetitionDemoSeeder.php` calls the journey after existing asset/scenario seeders.
- `database/seeders/DemoIdentityAnchorSeeder.php` for new synthetic generation.
- `tests/Feature/CompetitionDemoSeederTest.php`: Erik now has six Passport entries/five certificates in the clean canonical fixture, retaining the previous five-entry journey and its no-certificate example.

The new fixture uses `SubmitProgramProposalAction`, `ReviewProgramProposalAction`, `StartProgramExecutionAction`, `ProgramIntakeService` configure/apply/review/withdraw, Activity submit/review/publish, `RegisterForActivityAction`, registration review, attendance recording, execution completion, participation completion and certificate issuance. Simulated historical time is restored in `finally`; no current/future attendance is manufactured.

## Safe installation / refresh

Canonical competition setup, after the project’s normal additive migrations and local environment setup:

```powershell
php artisan db:seed --class=CompetitionDemoSeeder
```

On an existing prepared competition database, refresh only Academy plus missing onboarding references:

```powershell
php artisan db:seed --class=GovernmentProgramJourneySeeder
```

The targeted command requires the existing 24-account competition fixtures and verified digital Community; it is not an alternative fresh-install entrypoint. New seeders explicitly allow only local database `sipora` or testing database `sipora_regression_test`. They refuse production before writes. Do not run `migrate:fresh` or `UserSeeder` to refresh an existing demo; the latter rewrites credentials. Use `php artisan test` for isolated regression validation.

Stable lookup keys: Program/Activity slug, proposal version, `(program_id,user_id)`, `(activity_id,user_id)`, session number and `(session_id,participation_id)`. Existing applications/decisions/certificate identities are preserved on rerun. Existing issued numbers and verification codes are not regenerated. This is additive maintenance, not a reset of changes made during a live demonstration.

## Current development refresh and integrity

The targeted seeder was executed **twice** on local `sipora` after isolated focused tests passed. Counts after the first and second run are identical:

| Records | Before | After first | After second |
| --- | ---: | ---: | ---: |
| Users | 47 | 47 | 47 |
| Communities | 20 | 20 | 20 |
| Programs | 24 | 25 | 25 |
| Program applications | 0 | 16 | 16 |
| Activities | 15 | 16 | 16 |
| Activity participations | 144 | 150 | 150 |
| Sessions | 27 | 31 | 31 |
| Attendance | 142 | 166 | 166 |
| Certificates | 12 | 18 | 18 |
| Notifications | 33 | 80 | 80 |
| Audit entries | 55 | 140 | 140 |

Notifications/audit are generated once by the real workflow actions. Forum, feed, chat and old scenarios were retained. Password hashes and the asset-manifest checksum compare unchanged before/after.

All **24 canonical competition Youth** pass current checks: synthetic `000000` prefix; unique encrypted 16-digit NIK; decoded DOB equals profile DOB; birth place/manual gender present; village belongs to selected district; 1–5 real Interests. Intentionally incomplete later onboarding/identity states remain available for demonstrations. No real identity document was generated.

Asset manifest preflight: **181 available, 115 planned, zero ownership conflicts**. The manifest is unchanged. The new Activity may reuse the existing same-community Web Development poster only when its stored file actually exists; otherwise it uses the application fallback. Program has no invented image column. The proposal uses the existing clearly fictional readable PDF fixture. No external media downloads or old media deletion.

Evidence: `storage/app/demo-journey-refresh.json`, `storage/app/demo-journey-integrity.json`.

## Validation results

- Focused golden journey + canonical double-run tests: **4 passed, 411 assertions**, 37.62s (`storage/app/demo-journey-focused.log`).
- Full backend: **425 passed, 4,141 assertions**, 299.86s (`storage/app/demo-journey-regression.log`). Uses isolated MySQL `sipora_regression_test`; development storage is not used by tests.
- Frontend: **45 passed, 0 failed** (`storage/app/demo-journey-frontend.log`).
- Production build: **PASS**, 14.65s (`storage/app/demo-journey-build.log`).
- Scoped Pint: PASS. `git diff --check`: PASS.

HTTP integration verifies Erik’s Program acceptance, linked public Activity/Program context, Manager applications/participants/session attendance, Verifier selection screen, Admin Program directory, Erik Passport detail, certificate view and actual PDF response. It also checks unique applications, quota, per-session timestamps, clock restoration, onboarding/NIK integrity and unchanged row counts/certificate identity after running the full canonical seeder twice.

Manual browser QA on current demo: public Academy and quota; Manager filtered Erik accepted; six completed participants with attendance summary; Erik’s public certificate says “Sertifikat valid”; Aditya’s actual Youth Passport lists Academy with 4/4 and opens its issued certificate with PDF link. No review/attendance decisions or applications were changed through the browser. Primary Erik/Verifier owner sessions were validated by HTTP integration rather than guessing their customized passwords.

Screenshots: `storage/app/demo-academy-public.png`, `demo-academy-manager-erik.png`, `demo-academy-participants.png`, `demo-academy-passport-aditya.png`.

## Remaining implementation limits

- Direct government ownership/creation is not implemented; government oversight/selection is represented through the existing Verifier policy, with Community ownership intact.
- Program acceptance and Activity enrollment are separate existing workflows.
- Completion is a Manager decision, not a computed attendance threshold; certificates have no claim state.
- Passport’s current UI does not show Program directly; its Activity link exposes the Program context.
- Existing Activity/Passport views format some timestamps in application UTC, while Program intake explicitly formats WIB. The fixture stores valid historical instants (09:00 WIB sessions); global date-formatting behavior was not redesigned in this seeder phase.
- Existing customized Erik/Verifier credentials are intentionally preserved. Existing real/valid NIK anchors are not overwritten by the repair seeder; the 24 current competition fixtures were verified synthetic.
- Legacy standalone QA seeders are retained; use the canonical competition entrypoint for the latest complete demo.
