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
| certificates | Future | Approved `user_certificates` table | 13 | Not migrated yet |
| Programs | Future | Approved Program domain tables | Future | Added only in its feature phase |
| Opportunities | Future | Approved Opportunity domain tables | Future | Added only in its feature phase |
