# Database Implementation Coverage

The approved contract is `sipora_v2_mysql_backend_driven_clean_v2.sql`. Laravel implements the contract incrementally by feature phase.

| Approved Table / Concept | Implementation Status | Implemented As | Phase | Notes |
| --- | --- | --- | --- | --- |
| `users` | Implemented | Laravel migration and `User` model | 1 | UUIDv7 `BINARY(16)` |
| system roles / permissions | Replaced by framework/package | Spatie Permission tables | 1 | Global roles: youth, verifier, admin |
| audit log | Replaced by framework/package | Spatie Activitylog `activity_log` | 1 | Privacy-safe event properties |
| youth profiles and visibility | Implemented | `user_profiles`, addresses, interests, skills, education, experience, achievements, visibility | 2–3 | Profile completion is derived |
| identity | Implemented | `user_identities`, `user_identity_verifications` | 2–4 | Documents remain private |
| organizations | Implemented | Organization, category, document, verification request tables | 5 | Community domain name in product UI |
| memberships | Implemented | `organization_memberships` | 5–7 | Contextual leader/manager/member roles |
| activities | Implemented | Activity, category and review tables | 8 | Approved lifecycle dimensions retained |
| sessions | Implemented | `activity_sessions` | 9 | SQL clean_v2 used; stale DBML excluded |
| participations | Implemented | `activity_participations` | 10, 12 | Registration and completion dimensions are independent |
| attendances | Implemented | `activity_attendances` | 11 | Per-session evidence |
| Activity Passport | Implemented | Derived read model from completed accepted participation | 12 | No persistence table exists in approved SQL |
| certificates | Implemented | Approved `user_certificates` table plus dynamic private PDF | 13 | Public verification uses immutable database code |
| Youth Portfolio | Implemented read model | Composed from profile, visibility, enrichment, membership, Passport, and certificate data | 14 | No Portfolio persistence table exists in approved SQL |
| Discovery / Search / Personalization | Implemented query layer | Public Activity and Community queries plus deterministic Youth suggestions | 15 | No search, recommendation, behavior, or history persistence; MySQL queries use existing indexes |
| Program core | Implemented | `program_categories`, `programs`, and Activity → Program FK | 16 | Organization-owned Program; nullable Activity relation |
| Program Proposal | Implemented | `program_proposals`, private documents, versioned review workflow | 17 | Submit actor is represented by the privacy-safe activity-log causer because the approved table has no submitter column |
| Program Logbook | Implemented | `program_logbooks`, private `program_logbook_media`, review workflow, factual monitoring service | 18 | Progress percentage is manager-reported data from the approved schema, not a generated score |
| Program Finance / E-LPJ | Implemented | `financial_reports`, `financial_items`, private evidence, versioned review workflow | 19 | Totals are derived with decimal-string arithmetic; E-LPJ approval does not complete Program |
| Program Evaluation | Implemented | `program_evaluations`, eligibility service, Verifier decision workflow, and controlled Program completion | 20 | 1:N immutable decision history; approved final evaluation completes the Program |
| Government Program workflow | Complete at domain level | Program Core → Proposal → Logbook → Financial / E-LPJ → Final Evaluation | 16–20 | Public Program discovery, Opportunity, analytics, and ranking remain outside this completed internal workflow |
| Opportunities | Future | Approved Opportunity domain tables | Future | Added only in its feature phase |
