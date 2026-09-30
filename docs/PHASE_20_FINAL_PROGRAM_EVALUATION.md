# Phase 20 — Final Program Evaluation

## Approved data contract

Phase 20 implements `program_evaluations` exactly from `docs/database/sipora_v2_mysql_backend_driven_clean_v2.sql`. It uses UUIDv7 `BINARY(16)` identifiers, `DATETIME(6)` timestamps, soft deletion, the approved foreign keys and index, and the exact decisions `approved`, `revision`, and `rejected`.

The schema has no uniqueness constraint on `program_id`, so Program-to-Evaluation cardinality is 1:N and decisions form immutable history. It has no numeric score, recommendation, or criteria columns. SIPORA therefore records a Verifier decision and optional plain-text findings without generated scoring, ranking, or weighted criteria.

## Eligibility and version selection

`ProgramEvaluationEligibilityService` is read-only and returns explicit blocking reasons. A Program is eligible only when:

- its execution status is `running`;
- its absolute latest Proposal version is `approved`;
- it has at least one approved Logbook; and
- its absolute latest Financial Report / E-LPJ version is `approved`.

The absolute latest version is selected by the existing version relationship. An older approved Proposal or E-LPJ cannot satisfy eligibility when a newer unresolved version exists. Cancelled and completed Programs fail the running-state check. Program start already requires linked Activity implementation context, and neither the PRD nor approved SQL defines an additional all-Activities-completed threshold, so Phase 20 does not invent one.

## Decisions and completion

A global Verifier submits the decision through a dedicated request and transactional action. The server derives `program_id`, `verifier_id`, and `evaluated_at`. The action locks the Program row, recalculates eligibility inside the transaction, and compares the last evaluation ID submitted by the rendered page with the actual latest record to reject stale or repeated decisions.

An `approved` evaluation changes the Program from `running` to `completed` in the same transaction. A `revision` or `rejected` evaluation leaves it `running`; both require notes. E-LPJ approval alone never completes a Program. No decision mutates Proposal history, Activity review or participation, Attendance, Certificates, Logbook review history, or Financial Report history.

## Authorization and completed-state safety

Only the global `verifier` role may decide an eligible Program. An active contextual leader or manager of that Program's Community cannot decide it even if the same account also has the Verifier role. Admin and ordinary Youth receive no implicit evaluation authority. Contextual leaders and managers may read the final evaluation only through their existing Program workspace.

Completed Programs block normal Program edits, Activity-to-Program relinking, new or changed Logbooks, new E-LPJ versions, and Financial Item changes. Activity behavior unrelated to Program linkage remains governed by its own lifecycle.

## Audit, notifications, and privacy

The workflow records `program_evaluation_created`, `program_evaluation_finalized`, and, for approval, `program_completed`. Audit properties contain Program ID, Evaluation ID, and decision; notes and financial evidence are excluded. Active contextual leaders and managers receive a database notification containing privacy-safe identifiers and the decision. No evaluation detail, internal note, evidence file, or Program completion is automatically made public.

## Visual references

The Verifier queue and consolidated evidence page use the visual language of `Verifier Dashboard - Tim Kurasi & Pengawasan Dindikpora Kab. Pemalang _ SIPORA v2.png`. The Manager factual summary follows `Detail Program - Program Pemuda Digital 2026 _ SIPORA v2.png`. Prototype scoring concepts were excluded because the approved SQL contains no score field.
