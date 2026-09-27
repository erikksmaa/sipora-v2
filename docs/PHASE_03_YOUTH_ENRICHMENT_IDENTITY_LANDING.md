# Phase 3 — Youth Profile Enrichment, Identity Submission & Public Landing

## Scope delivered

Phase 3 adds self-reported skills, education, external or historical organization experience, achievements, runtime profile completion, section visibility controls, Youth-side identity submission, and the public landing page. Organization experience in this phase is profile history; it is not SIPORA Community Membership.

Identity submission supports `ktp`, `kia`, and `student_card`. A student card is supporting identity evidence and is not stored as a national identity number. Email verification and Google OAuth remain account authentication signals only.

## Profile completion weights

Completion is calculated at runtime and cannot be edited directly.

| Group | Field | Weight |
|---|---|---:|
| Basic profile | Full name | 10% |
| Basic profile | Birth date | 10% |
| Basic profile | Gender | 5% |
| Basic profile | Phone | 5% |
| Basic profile | Occupation status | 10% |
| Basic profile | Bio | 10% |
| Basic profile | Domicile | 10% |
| Basic profile | Interests | 10% |
| Enrichment | Skills | 10% |
| Enrichment | Education | 10% |
| Enrichment | Organization experience or achievement | 10% |

## Identity document security

- Files are stored on the `private` disk under randomized names.
- There is no public URL or storage symlink for identity documents.
- Allowed server-detected MIME types are JPEG, PNG, and PDF, up to 4 MB.
- Document numbers are encrypted at rest and hashed for duplicate detection.
- Document paths, hashes, ciphertext, and document numbers are hidden from serialization and excluded from audit properties.
- One pending submission is allowed per Youth. Revision or rejection creates a new submission and preserves prior history.
- A verified identity cannot be overwritten by Youth.

## Public landing data

Temporary Activity, Community, Opportunity, Program, statistics, and story content is isolated in `app/Presenters/PublicLandingPresenter.php` and marked `temporary_landing_presentation`. Statistics are visibly labeled as illustration data. Existing Interest records are read from the database; a presentation-only fallback is used only when the Interest catalog is empty.

When those domains are implemented, replace the corresponding arrays inside the presenter with read-model or repository queries while preserving the view keys. The Blade structure does not need to change.
