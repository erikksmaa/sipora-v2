# SIPORA v2 Development Data

Data in this document is for local development only. It is not production data, and the credentials must never be used in production.

## Local database

The canonical local database is `sipora`. Rebuild it with:

```bash
php artisan migrate:fresh --seed
```

The PHPUnit suite also targets `sipora` by project decision and uses `RefreshDatabase`. Running the full suite rebuilds the schema, so run `php artisan migrate:fresh --seed` afterward to restore the demo dataset.

The modular development seeders are skipped when the application environment is `production`.

## Demo accounts

All demo accounts use the development-only password `password`.

| Account | Email | Global role | Suggested scenario |
| --- | --- | --- | --- |
| Admin | `admin@sipora.test` | `admin` | Personal identity review |
| Verifier | `verifier@sipora.test` | `verifier` | Community and Activity review |
| Youth 1 | `youth1@sipora.test` | `youth` | Leader workflow, near-complete profile, verified identity |
| Youth 2 | `youth2@sipora.test` | `youth` | Manager and participant workflow, pending identity |
| Youth 3 | `youth3@sipora.test` | `youth` | Cross-Community authorization and revision identity |
| Youth 4 | `youth4@sipora.test` | `youth` | Pending membership and attendance examples |
| Youth 5 | `youth5@sipora.test` | `youth` | Rejected/left membership and cancelled registration examples |

Identity numbers and documents created by the seeders are explicitly fake development fixtures. Activity categories are temporary development values until official Dindikpora categories are approved.

## Phase 12 manual scenarios

- Sign in as `youth3@sipora.test`, open Pemuda Olahraga Pemalang, then open Bootcamp Digital Pemalang participants. Attendance evidence is available for three accepted participants. Youth 1 remains pending completion and can be finalized as completed or no-show.
- Sign in as `youth2@sipora.test` and open `/youth/passport`. Bootcamp Digital Pemalang appears as a completed, verified Activity experience.
- Sign in as `youth4@sipora.test` and open `/youth/passport`. The no-show Bootcamp record does not appear.
- Youth 3 has an accepted participation with pending completion on Workshop Web Development Pemula. It cannot be finalized while that Activity is still scheduled.

Attendance is supporting evidence only. The contextual leader or manager makes the final completion decision after the Activity execution is completed.

## Phase 13 certificate scenarios

- Sign in as `youth1@sipora.test` and open `/youth/certificates`. The verified Bootcamp certificate can be viewed and downloaded as a PDF.
- Open the public verification link from Youth 1's certificate in a guest browser. The page shows only the recipient display name, Activity, organizer, issue date, certificate number, and verification code.
- Sign in as `youth3@sipora.test`, manage Bootcamp Digital Pemalang, then issue the still-unissued certificate for Youth 2. Youth 2 is completed and eligible; issuing it twice is blocked.
- Youth 4 remains `no_show` and cannot receive a certificate.

## Phase 14 Portfolio scenarios

- `youth1@sipora.test` is the rich public Portfolio example. Open `/youth/portfolio` as the owner or `/portfolio/erik-kusuma-rais` as a guest. The Portfolio contains profile enrichment, an active Community role, completed Activity, and verified SIPORA certificate.
- `youth3@sipora.test` is a sparse Portfolio example for owner and public empty-state checks.
- `youth2@sipora.test` is the privacy example. Skills, education, organization experience, and achievements remain visible to the owner with a private indicator but are omitted from `/portfolio/nabila-putri-salsabila`.
- Email, phone, birth date, detailed domicile address, identity data/documents, review notes, attendance notes, and private certificate download links never appear on public Portfolio pages.

## Phase 15 discovery scenarios

Guest scenarios:

- Open `/activities` to see approved, published, upcoming Activity ordered by date. Search `teknologi`, filter category `Teknologi`, or filter the districts Pemalang, Taman, and Comal.
- Open `/communities` to search approved, active Community and filter by category or district. Pending, revision, rejected, suspended, and archived Community records never appear.
- Open `/search?q=teknologi` for grouped public Activity and Community results. Blank searches show an instruction state and wildcard characters are treated as literal input.
- The landing page now reads upcoming Activity and active Community from the database. Opportunity, Program, public statistics, and youth stories remain isolated presentation placeholders until their domains exist.

Youth scenarios:

- Sign in as `youth1@sipora.test`. Youth Home suggests upcoming Technology and Education Activity because their category slugs match the account's stored interests or completed Activity history.
- Sign in as `youth3@sipora.test`. The active Community membership is excluded from Community suggestions.
- Remove all interests from a local test account to see the neutral fallback: upcoming public Activity ordered by date and recently approved active Community not already joined.

Personalization is deterministic. It uses only Interest slugs that exactly match Activity Category slugs, completed accepted Activity categories, and active Community memberships. It does not inspect identity data, profile visibility, attendance notes, review notes, or behavioral history.

## Phase 16 Program scenarios

- Sign in as `youth1@sipora.test` and open the Komunitas Programmer Pemalang Manager workspace. `Program Pemuda Digital 2026` is a planned Program with `Workshop Web Development Pemula` linked to it.
- `Program Kepemimpinan Muda` is a valid planned Program without Activity. Its detail page explains that at least one Activity will be required before Proposal submission becomes available in a later phase.
- Other Activities in Komunitas Programmer Pemalang remain standalone, proving `activities.program_id` stays optional.
- Sign in as `youth3@sipora.test` to manage `Program Olahraga Komunitas` under Pemuda Olahraga Pemalang. It provides the cross-organization authorization scenario: each manager can only view, edit, or link Programs within their own contextual Community.
- Program categories in the development database are deterministic samples only and are not official Dindikpora master data.
- Proposal is implemented in Phase 17. Logbook, financial reporting / E-LPJ, evaluation, and public Program discovery remain unavailable.

## Phase 17 Program Proposal scenarios

- `Program Pemuda Digital 2026` has an approved Proposal version 1. Approval does not start the Program and does not change Activity verification.
- `Program Olahraga Komunitas` has a submitted Proposal in the Verifier queue.
- `Program Kreativitas Pemuda` has a Proposal requiring revision. Its manager can create version 2, edit the copied private document, and resubmit it while preserving version 1.
- `Program Kepemimpinan Muda` has a draft Proposal but no linked Activity, so submission is correctly blocked until the composition requirement is met.
- Sign in as `verifier@sipora.test` and open `/verifier/program-proposals` to test the queue and decision detail. A Verifier who also contextually manages the same Community cannot decide that Community's Proposal.
- Seeded Proposal files are local development fixtures on the private `proposal_documents` disk. They have no public URL.

## Phase 18 Program Logbook scenarios

- `Program Pemuda Digital 2026` is running with an approved Proposal, two Logbook entries, and one private media fixture. One Logbook is approved and one is waiting in the Verifier queue.
- `Program Literasi Teknologi` has an approved Proposal and linked Activity. Phase 19 advances this development fixture to running so its submitted E-LPJ can be reviewed.
- `Program Olahraga Komunitas` and `Program Kreativitas Pemuda` cannot start because their latest Proposals are submitted and revision respectively.
- Managers can create, edit, archive, attach private media, and submit draft/revision Logbooks only while a Program is running. Submitted and approved records are locked.
- Sign in as `verifier@sipora.test` and open `/verifier/program-monitoring` to inspect factual Activity/Logbook metrics and review submitted Logbooks. Evaluation data is not present.

## Phase 19 E-LPJ scenarios

- `Program Pemuda Digital 2026` has a draft E-LPJ with expense, income, and private evidence examples. Its proposal budget is compared with the derived expense realization.
- `Program Literasi Teknologi` is running with an approved Proposal and Logbook plus a submitted E-LPJ in the Verifier queue.
- `Program Kolaborasi Digital` is running with an approved Proposal and Logbook plus an E-LPJ requiring revision. Its realization exceeds the approved proposal budget to exercise the warning state; the application does not silently block or approve the variance.
- Sign in as `youth1@sipora.test` and open the Program detail in the Manager workspace to manage the draft or explicitly create a new version from the revision report.
- Sign in as `verifier@sipora.test` and open `/verifier/financial-reports` to review submitted E-LPJ records. Evidence is streamed from the private `financial_receipts` disk through authorized routes.
- Approval, revision, and rejection preserve the Program's running state. Phase 20 owns final Program evaluation and completion.
