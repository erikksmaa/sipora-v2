# Floating Chat SIPORA - 17 October 2026

## Result and original architecture

Previously ChatController rendered a full workspace page with an initial ChatService listing. resources/js/chat.js owned four-second cursor polling, history, nonce-protected sending, reporting, deletion and hidden-tab backoff. Global and Community permissions came from ChatRoomPolicy. These remain the authority; no new messaging domain or framework was introduced.

The primary experience is now an authenticated floating launcher and panel. Public browsing, Youth, Community, Admin and Verifier layouts include the same component. Guests, unverified/inactive accounts, authentication layouts and existing full chat pages do not receive it. Full chat pages omit it specifically to avoid running a second polling client beside the existing page.

## Launcher and panel

The launcher uses the existing message-circle icon, a 52px navy control and clear focus outline. Desktop panel width is 400px with height capped at 720px/78dvh. It does not block the application; outside click closes it. Phone screens use a full-width sheet starting 64px below the visual viewport top. Public bottom navigation remains visible below it; workspaces use the available bottom edge.

The room list has local name search, Chat Global, and Community rooms from active OrganizationMembership records only. This membership restriction also applies to Admin discovery, despite their broader existing moderation authority. Verifier receives no private room merely by role. Soft-deleted organizations and memberships are excluded by native model scopes. Community logos use the existing public route only when publication/operational status permits; otherwise initials appear.

Room items show a safe context subtitle rather than querying latest-message previews. There is no unread badge, online count, read-receipt model, direct messaging, attachment support or background room-list polling.

## Existing endpoints and thin discovery adapter

- GET chat.rooms (/chat/rooms): new authenticated, verified, role-protected and throttled discovery endpoint. ChatRoomDirectory builds one membership-scoped organization query. Global authorization is checked by the existing policy.
- Existing chat.global, chat.community and chat.room GET routes retain HTML responses. Accept: application/json returns their already-authorized initial configuration instead of HTML. It contains the existing messages/send/full-page URLs and initial bounded listing.
- Poll/send/report/delete endpoints, CSRF checks, nonce behavior, throttles, cross-room checks and moderation authority remain unchanged.
- Full administrative report management remains on the existing Admin moderation page.

No schema, dependencies, seeder, role or authorization-policy changes.

## Reuse, polling and state

Both full pages and the floating panel include chat/_conversation.blade.php. Every opened room uses the same siporaChat controller from chat.js; there is no second polling algorithm. Widget-created controllers start paused. Only the selected room resumes; closing, returning to the list or switching rooms pauses the earlier controller. Four-second incremental polling, bounded pagination, hidden-tab pause/backoff, nonce retries, reader-position behavior and new-message indicator are retained.

Previously opened rooms, loaded messages, scroll containers and unsent drafts stay in page memory. Reopening refreshes membership discovery and requests updates from the saved cursor. Revoked rooms are evicted. An expired/denied session clears cached content. Late room loads cannot reopen a closed panel, and a poll finishing after pause cannot overwrite the saved cursor. No private message content is written to localStorage or sessionStorage.

The native browser confirm for deletion was replaced by an accessible SIPORA dialog in the shared view. It calls the same authorized DELETE endpoint and retains soft-delete/audit semantics. Reporting continues to use the existing report workflow. Sender avatars retain the safe slug-based handling from the preceding phase.

## Mobile, accessibility and motion

The composer is a fixed flex child of the conversation panel; only the message stream scrolls. VisualViewport resize/scroll updates the sheet's available height and bottom offset. When a keyboard reduces the visual viewport while a field is focused, the sheet uses that viewport instead of reserving a second public-navigation gap. Safe-area padding is included. Desktop Enter/Shift+Enter and coarse-pointer mobile Enter behavior are unchanged.

Phone launchers reserve bottom-navigation height and stack above the accessibility control. Back-to-top moves above the stack. When the sheet is open, the accessibility control remains visible in the 64px top gap with a 44px effective button target. Its existing Shadow DOM receives a scoped positioning rule; it is not hidden.

The panel has a labelled dialog role, mobile focus trap, search/field labels, visible focus states, Escape/outside-click closing, focus restoration after Alpine reveals the launcher, and focus on the room header rather than automatically opening the mobile keyboard. Native report/delete dialogs retain their own modal focus handling. Incoming messages scroll only when appropriate and do not steal focus.

Desktop entry uses a 200ms fade/8px movement; mobile uses a 240ms sheet transition. prefers-reduced-motion disables those transitions/transforms. Slate theme tokens cover the launcher, panel, room list, bubbles, composer and dialogs. No reveal/GSAP annotations are applied to the widget.

## Performance

No room discovery or message requests occur while the widget is unused. Opening the list performs a bounded-count discovery query, not per-room message queries. Opening a room uses the existing initial latest-message limit; older history remains on demand. Previously opened inactive rooms have no polling timer. Full chat pages render only the full-page controller. No WebSocket or new library was added.

## Files

- app/Services/ChatRoomDirectory.php: membership-scoped discovery query.
- app/Http/Controllers/ChatController.php: room list and negotiated JSON configuration.
- routes/chat.php: authenticated chat.rooms route.
- resources/js/chat.js: pause/resume and shared confirmation-dialog behavior.
- resources/js/floating-chat.js: lazy list, cached room controllers, lifecycle, focus and visual viewport handling.
- resources/js/app.js: Alpine registration.
- resources/js/mobile-bottom-nav.js: accessibility control coordination.
- resources/views/components/floating-chat.blade.php: reusable launcher, room list and panel.
- resources/views/chat/_conversation.blade.php and show.blade.php: shared conversation/fallback page.
- resources/views/layouts/{public,workspace,base}.blade.php: integration; auth layout is excluded.
- resources/css/floating-chat.css and app.css: themed responsive presentation.
- tests/Frontend/floating-chat.test.mjs, tests/Frontend/chat.test.mjs and tests/Unit/PublicNavigationTest.php: lifecycle, history, actions, visibility and membership-query regression coverage.

## Verification

- Production Vite build passed: 89 modules.
- PHP Unit suite: 12 passed, 135 assertions, without database operations.
- Frontend suite: 24 passed, including existing chat tests and new lifecycle/report/delete tests.
- PHP lint, Blade cache compilation and focused git diff --check passed.
- Full database Feature suite was not run because phpunit.xml forces the active sipora database and the base test case rejects a separate database. RefreshDatabase could reset the golden demo dataset. Existing Chat feature tests remain unchanged.

## Browser QA

Two independent authenticated sessions were used: Aditya at 127.0.0.1 and Nabila at localhost, with separate host cookies. Nabila sent one explicitly labelled DATA QA message to Global and one to the existing Digital Community room. Both appeared in Aditya's floating panel through polling, without navigating away from the Youth dashboard. Aditya's unsent draft survived close/reopen.

Aditya reported the Global QA message; the UI confirmed acceptance. Nabila soft-deleted that message and Aditya received the tombstone. Aditya, an existing active Community manager, soft-deleted the Community QA message, and Nabila received the tombstone. Only these newly created QA messages were deleted; existing messages and Community records were not changed. Their soft-deleted audit records and the QA report are retained by the native workflow.

The full-page Community deep link still rendered correctly and had no floating launcher. The launcher was present in the Community workspace. Admin and Verifier discovery were checked in their respective workspaces and each returned only Global for the tested accounts with no Community memberships. Guest and authentication pages had no launcher. Focus returned to Buka Chat SIPORA on close and Escape. Both QA sessions were logged out and the temporary viewport override was reset. No new Alpine errors were observed; the existing SweetAlert2 toast/heightAuto warning is unrelated.

Responsive checks on the authenticated public conversation at 1440x1000, 768x1024, 390x844, 360x800 and 412x915 found no horizontal overflow. Every composer remained above its panel bottom. At 390x844, the public sheet occupied y64..776 above the 68px bottom nav. Accessibility control bottom was y60 when open, safely above the sheet; when closed it remained at y760 while the launcher ended at y676. Light and dark mobile captures and desktop Community capture are saved under output/floating-chat/.

## Remaining practical limits

Actual phone keyboard behavior and nonzero safe-area insets still require a physical device; viewport resizing cannot reproduce every mobile browser/keyboard combination. Current fixture rooms have fewer than the initial latest-message limit, so older-history anchoring was verified by frontend tests rather than adding dozens of artificial messages. No direct messages, read receipts or latest-message preview polling were added. No automatic commit.

Floating controls: the back-to-top, chat launcher, and accessibility launcher share a 56 px circular footprint, a common right inset, and a 16 px vertical gap. Mobile controls reserve the bottom-navigation height; the accessibility launcher remains above the open chat sheet.
