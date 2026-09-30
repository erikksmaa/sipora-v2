# Phase 21 — Opportunity Hub, Notifications, and Analytics

## Scope

Phase 21 implements the approved Opportunity domain, a shared notification center over the existing notification table, and factual aggregate dashboards for Admin and Verifier. It does not implement application tracking, ranking, gamification, predictive analytics, or automated government decisions.

## Database contract

`docs/database/sipora_v2_mysql_backend_driven_clean_v2.sql` is authoritative. Migration `2026_09_30_000019_create_opportunity_tables.php` implements exactly:

- `opportunity_categories`
- `opportunities`
- `opportunity_bookmarks`

Identifiers and foreign keys use UUIDv7 `BINARY(16)`. Timestamps use `DATETIME(6)`. Publication status, date, and JSON constraints remain enforced in MySQL. Existing `notifications` is reused. Analytics and Verifier queue summaries are derived at runtime and add no persistence tables.

## Opportunity workflow

Admin creates a draft and may edit, publish, or archive it. Public and Youth discovery only read records with `publication_status = published` and `published_at <= now()`. Registration remains on the provider's external URL. Youth can save a public Opportunity once, remove it, and restore a previously soft-deleted bookmark.

Filters are title/provider/description/category/location search, category, district, and open/expired/all deadline state. The default and Youth Home order is deterministic: non-null nearest deadline, then publication date and title. There is no opaque relevance score. Interest-based matching is deferred because no approved Interest-to-Opportunity-category mapping exists.

## Notification safety

All notification queries are scoped through the authenticated user relationship. Individual read actions verify ownership, and mark-all affects only that relationship. `NotificationActionResolver` ignores arbitrary URLs in JSON and generates links only for known notification types after checking that the referenced entity is public, owned, or contextually managed by the current user.

## Analytics

Admin sees factual aggregates for Youth, identity states, active/pending Community records, active memberships, published/upcoming Activity, registrations/completions, Program execution states, and published/open Opportunity records. Verifier sees only operational queue counts for existing Verifier workflows. No identity-document data, review note, attendance note, NIK, financial evidence, ranking, recommendation, or prediction is included.

## UI references

- `Landing Page SIPORA v2.png`
- `Detail Peluang - Program Magang Digital 2026 _ Diskominfo Kab. Pemalang.png`
- `Admin Dashboard & Identity Verification - Super Admin Dindikpora Kab. Pemalang _ SIPORA v2.png`
- `Verifier Dashboard - Tim Kurasi & Pengawasan Dindikpora Kab. Pemalang _ SIPORA v2.png`
- `Youth Home SIPORA v2.png`

The screens use existing Blade components, Tailwind tokens, Alpine-enabled layouts, and responsive card/filter patterns. Business and authorization rules take precedence over prototype content.

## Query and indexing review

Public Opportunity queries use the approved publication/deadline and category/deadline indexes. Bookmark ownership uses the approved unique `(user_id, opportunity_id)` key. Notification unread and recent lists use the existing user/read/deleted/created indexes. Dashboard aggregates are independent count/group queries intended for the current administrative overview; caching or pre-aggregation should be considered only after production measurement.
