# Global & Community Chat — implementation and QA

Branch: `feature/global-community-chat`. No automatic commit.

## Architecture and scope

1. **Architecture:** `ChatService` centralizes room creation, bounded history/change queries, idempotent sends, reports and moderation. `ChatRoomPolicy` uses existing Spatie global roles and active `OrganizationMembership` records. Blade/Alpine and native fetch implement polling. Forum, Feed, notifications and Community workflows remain separate. No dependency was added.
2. **Schema:** `chat_rooms`, `chat_messages`, `chat_message_reports`, all with binary UUIDv7 primary/domain foreign keys and `DATETIME(6)`. A generated unique global slot prevents duplicate Global rooms; unique Community FK prevents duplicate Community rooms; a check constraint enforces valid room scope. Message nonce has a unique `(room,user,nonce)` constraint for retries. Indexes cover room/created-at/id, room/updated-at/id, message sender, report status/time and message/reporter uniqueness.
3. **Global:** authenticated, email-verified Youth, Verifier and Admin can read/send. Contextual Community actors use their own authenticated user identity. Existing inactive-account/session/password-change enforcement remains active.
4. **Community:** room is created lazily only after authorization. Active members, leaders/managers and Admin may read. Only active members may send, and the Community must remain operationally active. Admin is a read/moderation actor unless also an active member. Verifier has no special access to private conversations.
5. **Polling:** every 4 seconds; paused when hidden, resumed on visibility, backed off up to 30 seconds on failures. A timestamp/UUID cursor requests only committed changes, including deletion tombstones. Updates drain in batches of 50. Brief room shared/exclusive locks and monotonic update timestamps prevent a late-committing send/delete from being skipped by cursor advancement. No WebSocket/Reverb/Pusher.
6. **History:** initial latest 30 messages displayed oldest first; explicit “Muat pesan sebelumnya” uses a stable created-at/UUID cursor. Loading history does not rewind the live cursor. Client deduplicates messages, retains scroll position while prepending and shows a “Pesan baru” indicator instead of moving an older-history reader.
7. **Access:** every retrieval/mutation is authorized server-side, with cross-room message checks. Guests cannot poll. Left/removed/pending/rejected memberships do not grant access. Inaccessible lazy requests create no rooms. JSON responses are private/no-store.
8. **Community moderation:** leader/manager may soft-delete messages in their own room. They cannot moderate another Community or others' Global messages. Users can delete their own visible messages. No restoration or edit flow was introduced.
9. **Admin moderation:** `/admin/chat/reports` supports status filters, pagination, room context, sender, reason, reporter count, dismissal and soft deletion. A paginated room list supports read-only/active state. Normal users never receive deleted bodies, moderation notes or reporter identities.
10. **Reports:** reason allowlist reuses simple Indonesian moderation language. One report per sender/message prevents repeated active reports or repeat-report spam. Deletion marks pending reports actioned; dismissal preserves the message. Activitylog records deletion, report action/dismissal and room-state changes, without message bodies or secrets. Ordinary sends are not copied to Activitylog and do not trigger notifications.
11. **Rate limits:** separate named per-user buckets: send 30/min, polling 90/min, reports 10/min, moderation 20/min. Polling does not consume send quota. CSRF remains in Laravel's web middleware. Client busy state plus a stable retry nonce prevents accidental double sends.
12. **Privacy/security:** messages are plain text, trimmed, nonempty and bounded to 1,500 characters. Blade `@js` safely transfers initial data and Alpine `x-text` renders it; no raw HTML interpolation. Requests cannot inject room/user IDs. Responses contain only message/display fields and action flags, never email, NIK, date of birth, phone, roles or documents. Portfolio/photo links are included only when existing visibility permits. No chat projection was added to public discovery, profiles, search, Forum or Feed.
13. **Responsive:** checked desktop 1440x1000, tablet 768x1024, mobile 390x844; document scroll widths remained within viewport (1426/754/377 px). Long uninterrupted messages wrap; composer has a label, bounded textarea and busy state. Desktop Enter sends and Shift+Enter inserts a newline; coarse-pointer/mobile Enter remains a newline. Virtual device checks do not emulate every real phone keyboard.
14. **Dark mode/accessibility:** uses existing SIPORA tokens and slate surfaces, subtle orange own-message tint, visible text/timestamps, labeled report dialog and keyboard buttons. Polling never calls focus. New-message and error feedback are accessible. Native dialog manages report focus; existing confirmation infrastructure handles Admin decisions.
15. **Demo:** `ChatRoomSeeder` creates exactly one global room. `DemoChatSeeder` adds 12 natural Indonesian Global messages and 8 messages in Komunitas Pemuda Digital Pemalang, using existing demo users/members. Deterministic nonces make reseeding idempotent, including soft-deleted demo messages. Demo seeding refuses production/non-development databases; no credentials or memberships are changed. Competition seeding includes it, and the standard seeder includes the global room only. Manual QA added three clearly marked messages; one was soft-deleted by moderation.

## Files, migrations and routes

16. **Files:** new `ChatRoom`, `ChatMessage`, `ChatMessageReport`, `ChatRoomPolicy`, `ChatService`, `ChatController`, `routes/chat.php`, `resources/js/chat.js`, chat/administrative Blade views, two migrations, two seeders, PHP and JavaScript tests, and this report. Integrations: `AppServiceProvider` named limiters; `app.js` Alpine registration; web/admin/manager routes; workspace navigation; Community public/member access links; standard/competition seeder and canonical SQL snapshot.
17. **Migrations:** `2026_10_15_000002_create_chat_tables` and `2026_10_15_000003_normalize_chat_datetime_columns` applied to development `sipora`. The follow-up safely upgrades installations that initially used Laravel timestamp helpers; fresh creation already uses canonical datetime columns. No previous domain tables were changed.
18. **Routes:**

| Method | Route | Purpose |
|---|---|---|
| GET | `/chat` | Global Chat |
| GET | `/chat/communities/{organization}` | Authorized member Community Chat |
| GET | `/chat/rooms/{room}` | Authorized room entry |
| GET | `/chat/rooms/{room}/messages` | Initial/history/change cursor API |
| POST | `/chat/rooms/{room}/messages` | Send text |
| DELETE | `/chat/rooms/{room}/messages/{message}` | Authorized soft deletion |
| POST | `/chat/rooms/{room}/messages/{message}/reports` | Report visible message |
| GET | `/manage/{organization}/chat` | Community manager shell |
| GET | `/admin/chat/reports` | Moderation and room list |
| POST | `/admin/chat/reports/{report}` | Dismiss/action report |
| PATCH | `/admin/chat/rooms/{room}` | Active/read-only status |

Navigation: Youth “Aktivitas Saya → Chat Global”, active memberships “Chat Community”, authorized public Community button, Community workspace “Community → Chat Community”, Admin “Konten → Moderasi Chat” and “Sistem → Chat Global”, Verifier “Akun → Chat Global”. No anonymous navbar item.

## Tests and QA

19. **Added tests:** 20 feature tests in `tests/Feature/ChatTest.php` cover roles, guests, verification, valid/invalid/oversized content, plain-text UGC, compact privacy projection, send idempotency, cursor pagination/deletion propagation, membership states, room uniqueness, cross-room IDOR, Verifier exclusion, Admin/manager scope, report duplication/dismissal/action, room disabling, rate limits, CSRF, public-search isolation, eager loading and demo idempotency. Five dependency-free Node tests in `tests/Frontend/chat.test.mjs` cover retry nonce/draft, deduplication/tombstones, reader position/new-message indicator, hidden-tab polling and stale history protection.
20. **Results:** final full regression **354 passed, 3,065 assertions, 549.12 seconds**; frontend **5 passed**. Tests run on isolated `sipora_attendance_test`, never with a reset/refresh of development `sipora`. Temporary test guard/config edits are restored. The first full run exposed the datetime contract failure and was corrected rather than weakening the existing test.
21. **Build:** Vite production build passed: 80 modules, 27.63 seconds. PHP syntax checks, route listing, Blade compilation and five Node tests passed. No new frontend library.
22. **Manual two-session QA:** separate host cookies (`localhost` Nabila / `127.0.0.1` Aditya). Global message arrived without navigation/reload and appeared exactly once. Community message arrived once and preserved receiver draft. Report submitted in member UI appeared in Admin with one reporter; confirmed moderation changed status to actioned and the receiver received a tombstone without original body on its next poll. Manager entry retained `data-workspace="manager"` and active “Chat Community”; nonmember Community access returned 403. Desktop/tablet/mobile and light/dark checks completed. Screenshots saved in `storage/app/chat-*.png`. A temporary review-usage limit interrupted QA; work resumed through the same browser after its stated recovery time.
23. **Limitations:** no load test at production concurrency. Short room locks serialize mutations for reliable polling and may need measurement at larger traffic. Real mobile keyboards were not emulated. No direct messaging, uploads, reactions, edits, typing/read indicators or per-message notifications. No automatic commit.

## Query sanity check

Measured against the actual development Global room (13 messages at measurement), sender roles warmed:

| Request | Queries | Duration | JSON payload |
|---|---:|---:|---:|
| Initial 13 messages | 5 | 87.65 ms | 5,295 bytes |
| Empty incremental poll | 2 | 7.27 ms | 158 bytes |

`EXPLAIN` uses `idx_chat_history` / `idx_chat_changes`, access type `ref`. These are local single-request observations, not a production performance guarantee. A separate test with 12 distinct senders ensures query count remains bounded rather than growing per sender.

## Commands

```text
php artisan migrate
php artisan db:seed --class=ChatRoomSeeder
php artisan db:seed --class=DemoChatSeeder
node --test tests/Frontend/chat.test.mjs
npm run build
php artisan route:list --path=chat
```

Use an isolated database and the project's test safety guard when running `php artisan test`; do not run RefreshDatabase against a development database containing needed data.

## Final validation output

```text
Full regression: 354 passed (3065 assertions), 549.12 seconds
Frontend:        5 passed (node --test), no added dependency
Vite:            PASS, 80 modules, 27.63 seconds
PHP syntax:      PASS
Blade compile:   PASS
Routes:          11 chat routes listed
```

Manual history check used a temporary loopback server on port 8002, a separate session cookie and only `sipora_attendance_test` after regression completed. One disposable QA account and 35 messages were created there. Browser initial load showed messages 06–35 (30 rows); clicking the older-history button produced messages 01–35 (35 rows, 35 distinct message IDs). The temporary tab/server were closed; no extra history-test messages were added to development `sipora`.

Development server remains on port 8000. QA did not reset any existing account credentials or alter memberships. Test configuration and safety guard were restored; generated test view caches and helper scripts were removed after validation.
