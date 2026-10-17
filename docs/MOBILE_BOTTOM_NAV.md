> Superseded by PUBLIC_NAVIGATION_REFACTOR.md: bottom navigation is now public-only, with Beranda, Pemuda, Komunitas, Program and Forum.

# Mobile bottom navigation

Implemented 17 October 2026. No automatic commit or database changes.

## Destinations

| Label | Existing route |
|---|---|
| Beranda | youth.home for Youth, home for guests |
| Jelajah | search.index (activities, opportunities and programs) |
| Komunitas | communities.index |
| Chat | chat.global |
| Profil | youth.profile.show |

Guests follow existing authentication middleware for Chat and Profil. No new routes or permissions were added. Navigation is present on public/base layouts for guests and Youth, and the Youth workspace. Internal staff workspaces are excluded. Existing hamburger menus and desktop navigation remain intact.

## Implementation

- resources/views/components/mobile-bottom-nav.blade.php: exactly five semantic links, visible labels, one active route selected by ordered matching, aria-current.
- resources/css/mobile-bottom-nav.css: fixed 68px bar below 768px, theme variables, restrained orange active state, focus outline, 160ms color transition, reduced-motion override, safe-area height/padding.
- resources/js/mobile-bottom-nav.js: positions the existing accessibility widget above the bar inside its Shadow DOM; page CSS cannot reach this control.
- resources/views/layouts/{public,base,workspace}.blade.php: shared component and viewport-fit=cover. Workspace renders only for Youth.
- resources/views/components/ui/icon.blade.php: home icon in existing icon system.
- resources/css/app.css and resources/js/app.js: imports.

Chat routes take precedence over community routes. Profile/portfolio/passport/account/readiness routes select Profil, discovery routes select Jelajah, community routes select Komunitas, dashboard/home select Beranda. Secondary pages can have no primary active item.

## Spacing and chat

Mobile body padding reserves 68px plus env(safe-area-inset-bottom). Focusable fields have bottom scroll margin. The existing chat composer is in normal document flow, not fixed or sticky; reserved document space protects its final send action without introducing a second fixed composer. Back-to-top and the accessibility control are moved above the bar. The public hamburger panel reserves bar space as well.

## Validation

- npm run build: passed (88 modules).
- php artisan view:cache: passed.
- PHP/Blade smoke checks: 12 route cases plus staff exclusion passed using in-memory users and loaded role relations, without database access. Script: output/mobile-bottom-nav/check-navigation.php.
- Browser: 390x844, 360x800 and 412x915 fit all five items with no horizontal overflow. Targets are approximately 59px high. Light/dark checked; original light theme restored.
- Single active labels checked in browser on home, search and community directory.
- Keyboard Tab focus outline checked. At 768px and 1280px, bar is display:none with zero added body padding. Viewport override reset.
- Accessibility widget bottom measured at 760px with bar top 776px on 390x844, leaving 16px clearance.
- Guest Chat correctly redirects to login. Existing documented main demo credentials were rejected, so authenticated Youth/chat browser QA could not be completed. No passwords were changed.
- Actual virtual keyboard and physical device safe-area behavior still require device testing; browser viewport resizing does not emulate a mobile keyboard or nonzero safe-area inset.
- Screenshots: output/mobile-bottom-nav/home-light.png and home-dark.png.
