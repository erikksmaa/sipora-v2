# Prior SQL reference and Phase 1

Reference: `sipora_v2_mysql_backend_driven_clean_v2.sql`, supplied during implementation from the user's Downloads folder. Reviewed as historical design input; **not executed or imported**. The PRD, approved decisions and Phase 1 contract remain authoritative.

| Reference | Phase 1 decision |
|---|---|
| Application UUIDv7, BINARY(16), no byte swapping | Retained; RFC byte order, equivalent to UUID_TO_BIN(uuid, 0). |
| UTC DATETIME(6), InnoDB, utf8mb4, backend workflows | Retained. |
| Database sipora | Dedicated sipora_v2 and sipora_v2_testing; no SQL import. |
| system_roles/user_system_roles with UUID role IDs | Approved Spatie tables, BIGINT role/permission IDs and binary model pivots. |
| Users without name; required password; account status/soft deletion | Phase 1 name/email, nullable hashed password, verification and remember token only. No moderation or profile workflow. |
| Custom audit_logs | Approved Spatie activity_log with binary causer/subject references and explicit logging only. |
| Custom notifications | Deferred. Later use Laravel Database Notifications with binary notifiable references. Phase 1 sends auth email notifications only. |
| No social account/session foundation | Added as required by Phase 1, with binary user references. |
| utf8mb4_0900_ai_ci | utf8mb4_unicode_ci in this foundation; both satisfy the approved utf8mb4 requirement. No data migration. |
| Full business-domain schema | Out of Phase 1 scope; review again in the respective approved phases. |

These are differences from the earlier SQL reference, not unapproved deviations from the current contract. No existing legacy database was targeted.
