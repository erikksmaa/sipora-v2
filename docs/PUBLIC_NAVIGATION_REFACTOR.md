# Public navigation and chat avatars - 17 October 2026

## Result and earlier ambiguity

Previously the mobile bar changed Beranda into Youth Dashboard on authentication and also appeared in the Youth workspace. Account controls differed between public and workspace layouts. These are now separate concepts: public exploration retains the same routes for guests and every authenticated role; workspace access is explicit in the shared account dropdown.

## Public header

The compact institutional row shows Dindikpora Kabupaten Pemalang identity and Masuk for guests, or notification/account controls for authenticated users. The second row has the SIPORA brand, public primary links, secondary Jelajahi dropdown, search, theme toggle and mobile hamburger. No supplied official institutional logo was available, so the institution uses text identity.

Desktop primary links: Beranda, Pemuda, Komunitas, Kegiatan, Program, Peluang, Forum. Secondary links: Statistik, Peta Ekosistem, Social Feed, Chat Global, Tentang SIPORA and Kontak. Below 1280px the hamburger supplies navigation; at tablet widths it includes the primary links because bottom navigation is hidden. On phones the primary links remain in the bottom bar and the drawer groups secondary destinations. Authentication pages, which have no bottom bar, retain primary links in their drawer.

## Shared account dropdown

The same Blade component is used by the public header, Youth/Community/Verifier/Admin workspace header and legacy base header. It shows a circular existing photo or first/last initials (Erik Kusuma Rais becomes ER), name and dropdown indicator. Broken images fall back to initials.

Youth actions: Profil Saya, Youth Passport, Pengaturan Akun, Buka Youth Workspace. Admin and Verifier receive only their respective account/security and workspace actions. Existing stage middleware still controls access to Youth profile/passport; no requirements were bypassed.

Community workspace links come from the existing ManagedCommunityService, which selects active leader/manager contextual memberships. No global community_manager role is introduced. All dropdowns include Kembali ke Situs Publik. Logout remains a POST form with CSRF token.

## Public bottom navigation

| Label | Existing route |
|---|---|
| Beranda | home |
| Pemuda | youth-directory.index |
| Komunitas | communities.index |
| Program | programs.index |
| Forum | forum.index |

Beranda never changes with auth state. Public directory/detail routes select exactly one matching item; public portfolio details select Pemuda. Secondary pages can have no primary active item. Bottom navigation is rendered only by the public layout, for every role. Youth, Community, Verifier, Admin and legacy base workspaces render none. Profil and Chat are no longer bottom-nav destinations.

The existing 68px mobile bar, theme support, focus outline and safe-area handling are retained. Mobile page padding reserves bar height plus bottom safe area. Back-to-top/accessibility controls remain above the bar. Chat uses workspace layouts and its in-flow composer has no fixed public bar to overlap it.

## Chat photograph fix

ChatService previously passed a UserProfile model to portfolio.photo and portfolio.show. The model route key is a UUID, while those controllers resolve public_slug. Both links now use the slug explicitly. The shared avatar helper verifies public profile visibility, show_photo and file existence before returning a public photo URL. Publicly shared account photos also use that existing authorized endpoint, so incomplete Youth profile stages do not redirect the avatar request. Private account photos retain the existing authenticated endpoint. No private storage paths are exposed.

The shared global/community chat template now falls back to initials on image errors. Public photo visibility remains authoritative in both rooms. Browser QA verified actual Erik images loaded with nonzero naturalWidth in Global Chat and Community Chat, including existing deleted-message entries.

## Files

- app/Support/Media/UserAvatar.php: safe photo URLs and initials.
- app/Services/ChatService.php: slug-based profile links and photo helper.
- resources/views/chat/show.blade.php: image error fallback.
- resources/views/components/account-dropdown.blade.php: universal account control.
- resources/views/components/public/navbar.blade.php: two-row public header and drawer.
- resources/views/components/mobile-bottom-nav.blade.php: public-only five-item mapping.
- resources/views/layouts/workspace.blade.php: shared account control, no public bottom bar; fixes an existing literal @js expression emitted in an icon component attribute by passing data-group instead.
- resources/views/layouts/base.blade.php: shared account control and no public bottom bar.
- resources/css/public-navigation.css and resources/css/app.css: responsive, themed header/account styling.
- tests/Unit/PublicNavigationTest.php: role, route, CSRF, community-link contract, visibility/file fallback and chat projection regression checks without database access.
- tests/Frontend/public-motion.test.mjs: row-stagger assertion accepts the user's configurable timing instead of hard-coding the earlier 70ms setting. Existing 120ms animation configuration is retained.

The existing public layout already renders the shared bottom component and needs no new integration point. No routes, business authorization rules, database schema, dependencies or credentials were changed. No automatic commit.

## Validation

- Production Vite build passed (88 modules).
- Unit suite: 10 passed, 108 assertions. Six navigation/avatar tests include multiple role and route cases; four existing UUID tests also pass.
- Frontend suite: 17 passed, including chat polling/drafts, native navigation, component motion and validation.
- Blade cache compilation and PHP lint passed. git diff --check passed (line-ending normalization notices only).
- The full Feature suite was not run: phpunit.xml forces DB_DATABASE=sipora and tests/TestCase.php rejects other databases. RefreshDatabase feature tests could reset the existing golden demo dataset. Unit tests use in-memory users, mocked read relations and isolated fake storage, with no database operations.

## Browser QA

Guest and authenticated public layouts were checked. Actual existing fixture accounts exercised Youth, contextual Community manager, Admin and Verifier workspace/account controls. Public Beranda remained the public home for all roles. Workspace switching, Kembali ke Situs Publik, POST logout and unique Program/Forum active states worked. Youth/Community/Admin/Verifier workspace pages had no public bottom navigation.

Viewports checked: 1440x1000, 768x1024, 390x844, 360x800 and 412x915. No horizontal page overflow; desktop primary links remain on one row. Phone header is approximately 114px, desktop/tablet approximately 118px. Light and dark modes checked, theme restored to light. Account Escape and outside click behavior and keyboard focus were checked. Header, dropdown, drawer and fixed bottom bar have no content animation annotations.

Images under output/public-navigation/: desktop.png, youth-desktop.png, youth-mobile-account.png, mobile-dark.png, admin-public-account.png, community-chat-mobile.png and community-chat-desktop.png.

## Limits

Physical-device virtual keyboard/nonzero safe-area behavior was not simulated by the desktop browser. No messages, proposals or portfolio records were changed during QA. The app's existing SweetAlert2 heightAuto/toast warning is unrelated to this phase. No new route or permission exceptions were added.
