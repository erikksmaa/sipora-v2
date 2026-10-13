# Demo asset manifest integration — preflight stopped

> Historical preflight record. The user subsequently approved the P001 and
> P011 owner corrections. Integration and validation are now complete with zero
> ownership conflicts. See [the final integration report](DEMO_ASSET_MANIFEST_INTEGRATION.md)
> for current state and final asset counts. Statements below describe the earlier
> stopped audit, not the final implementation.

The user's uploaded manifest was copied byte-for-byte to
`database/seeders/data/demo-assets-manifest.json` (SHA256
`18588dc4ae6c7de9862980c6868f0ae87f9e9f97f37276047259bbbbe2e5fe87`).

Integration stopped at the explicit instruction to stop and report a relationship
conflict with current business rules. No existing seeder, application business
rule, schema, database record, or original media file was changed in this task.

## Blocking relationship conflicts

P001 is owned by C001 (Komunitas Pemuda Digital Pemalang). The uploaded manifest
associates these Activities with P001 and other Communities:

| Activity | Manifest owner | Program owner |
| --- | --- | --- |
| A002 — Pelatihan Public Speaking Dasar | C004 — Pemalang Public Speaking Club | C001 |
| A003 — Bootcamp Desain Konten Digital | C010 — Komunitas Desain Pemalang | C001 |
| A005 — Seminar Kewirausahaan Digital | C005 — Youth Entrepreneur Pemalang | C001 |

`App\Rules\ProgramBelongsToOrganization` requires the selected Program's
organization_id to match the Activity's organization. A read-only check against
the configured local sipora database reproduced the native validation failure
for all three: “Program yang dipilih tidak tersedia untuk komunitas ini.”

Importantly, the existing database already contains these cross-Community links.
The manifest does not introduce them, but an integration must not perpetuate them
by bypassing the application rule. P001 is already completed, and
`SaveActivityDraftAction` also locks composition changes for completed Programs.
No attempt was made to downgrade/reopen P001 or transfer reviewed records.

An ownership decision is required before changing the canonical mapping. Keeping
all four primary Activities under C001 would preserve P001 as a single-owner
Program. Keeping the distinct Community owners would require different Program
relationships or an explicitly authorized change to cross-Community business rules.
Neither change was inferred from the asset integration request.

## Other mapping differences to reconcile after the blocker

Uploaded P002 identifies Pemuda Kreatif Kecamatan Taman; earlier Batch 7B/7G
P002 identifies Creative Youth Development Program, now P006 in the uploaded
manifest. Resolve by title/slug and preserve each Program's history; never apply
the previous P002 workflow to the new P002 solely because the demo keys match.

Legacy DemoYouthSeeder names differ from the visual Y002–Y020 identities.
Three visual identities already have dedicated asset.*@sipora.test accounts.
An integration must reuse explicit natural keys and avoid attaching an existing
person's picture to a different named identity or duplicating canonical accounts.

The activity cover folders `activities/` and `03_ACTIVITIES/Axxx/` contain
byte-identical covers according to the earlier export verification. They were
preserved. Existing runtime/private media conventions must be retained.

## Asset audit

- Manifest parses correctly; 20 Youth, 15 Community, 15 Activity, 15 Opportunity,
  15 Program, 15 feed keys, and 8 document keys.
- 169 unique AVAILABLE references: 167 files and 2 directory references.
- All AVAILABLE references exist at the uploaded paths.
- 115 unique PLANNED references preserved, without assigning them to database fields.
- No database writes or destructive commands were executed.

## Safe checks executed

- `audit-uploaded-manifest.py`: JSON, exact repository copy, available paths,
  status counts and manifest relationships checked successfully.
- `check-manifest-relationship-rules.php`: local sipora read-only check;
  all three expected relationship violations reproduced.
- Full PHPUnit was not run: Tests/TestCase.php requires canonical MySQL sipora,
  and RefreshDatabase tests can erase current demo data.
- Seeder execution, application route sanity checks, optimize:clear and build
  were not run because the user expressly requires stopping on this conflict.
- No automatic commit.

Machine-readable evidence is in `output/demo-assets/uploaded-manifest-preflight.json`
and `output/demo-assets/manifest-relationship-rule-check.json`.

## Follow-up: golden owner correction approved

The user explicitly approved assigning A001/A002/A003/A005 to C001 under P001.
The repository manifest now contains those canonical values, with an explanatory
meta note. The downloaded original was preserved. This supersedes the original
P001 manifest conflicts above; no database mutation has been performed yet.

A complete grouping of all Activity→Program mappings also found another conflict:
P011 (Youth Career Readiness Program) contains A010 (Workshop Personal Branding)
owned by C004 (Pemalang Public Speaking Club), and A013 (Seminar Karier dan Magang)
owned by C012 (Youth Education Network). A single Program cannot satisfy both
owners under ProgramBelongsToOrganization. This was not covered by the initial
three-Activity database check.

The initial read-only audit script preserves the uploaded-manifest baseline;
do not rerun it to overwrite the user-corrected repository manifest.
`output/demo-assets/manifest-owner-correction.json` records the updated manifest
checksum and the remaining P011 conflict. The explicit stop-on-conflict instruction
still applies until ownership or Program assignment is clarified for A010/A013.
