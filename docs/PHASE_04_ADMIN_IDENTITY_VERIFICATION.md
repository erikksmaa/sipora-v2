# Phase 4 — Admin Identity Verification

## Responsibility and workflow

Personal identity verification belongs only to the global `admin` role with the `verify identities` permission. Youth, Verifier, and contextual organization roles cannot open the queue, detail, document stream, or decision endpoint.

The approved workflow is:

`pending` → `verified` | `revision` | `rejected`

Only the active latest pending submission is reviewable. Decisions use a database transaction and row locks. A terminal submission cannot be decided again, and a stale submission cannot overwrite a newer one. Revision and rejection require a reason. Rejection does not suspend the Youth account.

## Data model

Review fields already present on `user_identity_verifications` are reused: `status`, `reviewed_at`, `reviewed_by`, and `review_notes`. The matching `user_identities` aggregate receives the resulting status and, for approval only, `verified_at` and `verified_by`.

Phase 4 adds the approved `notifications` table from the SQL/DBML contract. Laravel Notifications dispatch through `SiporaDatabaseChannel`, which persists only a privacy-safe result message and the submission UUID/status.

## Private document access

Documents remain on the `private` disk. Admin access uses the authenticated route `admin.identity-verifications.document`; storage paths are never placed in HTML or response filenames. Responses use `private, no-store`, `nosniff`, and restrictive CSP headers.

## Audit and notification payloads

Decision events are `identity_verified`, `identity_revision_requested`, and `identity_rejected`. Audit and notification payloads contain a submission UUID and decision only. They exclude document number, number hashes/ciphertext, document paths, file contents, and review notes.

## Stitch reference

The Admin sidebar, navy and orange visual identity, queue summary cards, applicant rows, document review panel, and decision controls follow `docs/references/stitch/Admin Dashboard & Identity Verification - Super Admin Dindikpora Kab. Pemalang _ SIPORA v2.png`. Prototype-only SIAK, biometric, server, and government statistics panels are omitted because those integrations are not implemented.
