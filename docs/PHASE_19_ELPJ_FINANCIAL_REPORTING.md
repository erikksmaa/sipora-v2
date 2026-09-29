# Phase 19 — E-LPJ & Financial Reporting

## Contract and lifecycle

Phase 19 implements the approved `financial_reports` and `financial_items` tables from `docs/database/sipora_v2_mysql_backend_driven_clean_v2.sql`. A Program has many versioned Financial Reports and a Financial Report has many Financial Items. Report statuses are exactly `draft`, `submitted`, `under_review`, `revision`, `rejected`, and `approved`. Item types are exactly `income` and `expense`.

A contextual leader or manager may create the first draft only while the Program is `running`, its latest Proposal is `approved`, and it has at least one approved Logbook. Only the latest draft is editable. Submission requires at least one Financial Item. A Verifier may approve, request revision, or reject a submitted report, but may not review a Community they contextually manage. Revision and rejection require a note.

Revision is versioned. The manager explicitly creates the next draft from the latest `revision` report. Items and available evidence are copied to new private paths while the reviewed version remains immutable. Submitted, rejected, and approved reports are locked.

E-LPJ approval does not update `programs.execution_status`. Phase 20 remains responsible for final evaluation and any approved completion transition.

## Money model

Each Financial Item stores `amount` as `DECIMAL(18,2)`. PHP totals use BCMath decimal-string operations and never binary floating-point arithmetic. Expense total is the realization total. Income is shown separately. Proposal budget is read from the latest approved Proposal. Difference is proposal budget minus expense realization. No derived total is persisted.

An over-budget realization displays a warning. It is not blocked because the approved product and database contracts define no automatic enforcement rule.

## Evidence and privacy

Optional receipts use the private `financial_receipts` disk rooted at `storage/app/financial-receipts`. Uploads accept JPEG, PNG, WebP, or PDF up to 8 MB and use randomized names. Browser access goes through nested, authorized Manager or Verifier routes with private no-store headers. Models hide receipt paths from serialization. No public symlink or raw path is exposed.

## Audit and notifications

Creation, changes, item changes, submission, version creation, and Verifier decisions are recorded through Spatie Activitylog without receipt paths. Review notifications use the SIPORA database notification channel and contain only report/program identifiers, version, and status. Amounts and evidence paths are excluded from notification payloads.

## Visual reference

The Manager E-LPJ surfaces follow the Program detail visual language in `Detail Program - Program Pemuda Digital 2026 _ SIPORA v2.png`. The Verifier queue and review detail follow `Verifier Dashboard - Tim Kurasi & Pengawasan Dindikpora Kab. Pemalang _ SIPORA v2.png`. Prototype finance/activity coupling and generated scoring were excluded because they conflict with the approved domain boundaries.
