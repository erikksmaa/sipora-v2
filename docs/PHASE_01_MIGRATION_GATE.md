# Phase 1 migration safety gate

Reviewed before the first migration, 27 September 2026.

- `users.id` and `user_social_accounts.id`: application-generated UUIDv7, fixed `BINARY(16)`; no auto-increment or database UUID function.
- `BinaryUuid` is the single codec. `HasBinaryUuid` keeps raw 16-byte Eloquent keys for relationships and converts route, authentication, queue and JSON identifiers to canonical UUID strings.
- Spatie `model_has_roles.model_id` and `model_has_permissions.model_id`: `BINARY(16)`. Role/permission keys and their linking keys remain unsigned BIGINT. Polymorphic references deliberately do not have single-table foreign keys.
- Social account `user_id`: `BINARY(16)`, FK to users with cascade delete; unique provider identity and one account per provider/user.
- Database session `user_id`: nullable `BINARY(16)`, FK to users with SET NULL. `binary-database` handler converts Laravel's text auth identifier to bytes. Session IDs/payloads retain Laravel's own representation.
- Audit subject/causer: nullable `BINARY(16)` with indexed morph types; audit row IDs remain framework/package integer IDs. Audit subjects must be UUID-backed models; integer role/permission IDs can be logged as explicit properties, not polymorphic subjects.
- Application-owned datetime columns use `DATETIME(6)`. Framework queue/cache/session epoch counters remain integers as required by their native drivers; failed job datetime uses `DATETIME(6)`.
- Email notification delivery uses no database notification table in Phase 1. The later notifications migration must use a binary notifiable reference.
- MySQL is the application and integration-test target; UTC canonical storage, Asia/Jakarta display configuration. InnoDB/utf8mb4 are explicit.
- Pure unit tests passed before migration: application UUIDv7 generation, round-trip, model boundary representations, and actual MySQL grammar compilation to `binary(16)` / `datetime(6)`.
- No domain tables, triggers or views are introduced.

The user schema and identifier strategy are confirmed against the approved specification. Fresh migrations are limited to the dedicated, empty `sipora_v2` development database and `sipora_v2_testing` integration-test database. Existing legacy databases are not targeted. MySQL integration tests after migration verify physical column types, foreign keys and runtime relationship behavior.
