# Phase 17 — Program Proposal Workflow

Phase 17 implements versioned Program Proposal drafting, submission, Verifier decisions, revision, and resubmission. The implementation follows `docs/database/sipora_v2_mysql_backend_driven_clean_v2.sql` for `program_proposals`.

## Lifecycle

The persisted statuses are `draft`, `submitted`, `under_review`, `revision`, `rejected`, and `approved`. A manager edits only the latest draft. Submission requires an active approved Community, a planned Program, at least one linked Activity, and a private proposal document. Revision creates the next numbered draft and copies the previous document to a distinct private object, preserving reviewed history.

Verifier decisions can approve, request revision, or reject a submitted/under-review version. Revision and rejection require notes. A terminal proposal cannot be reviewed twice. Approval leaves `programs.execution_status` and all Activity lifecycle values unchanged.

## Authorization and privacy

Manager access derives from active contextual `leader` or `manager` membership. Verifier access derives from the global `verifier` role. A Verifier who manages the Program's Community cannot decide its Proposal. Documents use the private `proposal_documents` disk and are streamed only through authorized routes with private, no-store and `nosniff` headers. Raw storage paths are hidden from model serialization and omitted from notifications and audit properties.

Activity links cannot be added, removed, or moved while the affected Program's latest Proposal is `submitted` or `under_review`. Program editing is also locked during that review window.

## Approved-schema limitation

The approved `program_proposals` table has no submitter column. The submission timestamp is persisted in `submitted_at`; the authenticated submitter is retained as the Spatie activity-log causer. No unapproved ownership or submitter column was added.

## Deferred scope

Program execution controls, Logbook, financial reporting/E-LPJ, evaluation, completion, and public Program discovery remain deferred to later phases. No tables for those domains are introduced here.
