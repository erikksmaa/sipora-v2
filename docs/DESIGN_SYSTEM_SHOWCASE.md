# SIPORA Visual Design System / UI Kit Board

Route: `/design-system`, named `design-system`, registered only in `local` and `testing`. It is intentionally absent from public navigation. Open locally at `http://127.0.0.1:8000/design-system`.

This is documentation of the current frontend. It does not redesign application pages or refactor domain workflows. Showcase CSS and JS are separate Vite entries, loaded only by the board. Product components and `app.css` remain unchanged.

## Capture

- Entire board: `/design-system?capture=1&theme=light`
- Individual section: `/design-system?capture=1&section=12&theme=light`
- Dark capture: change `theme=dark`.
- Preview modes: `navbar`, `components`, `dashboard`, `landing`, `statistics`, `map` through `?preview=...&theme=...`.
- The toolbar includes section selection, theme comparison, and a documentation-only pause control. Section capture hides the index and toolbar.
- Section 27 embeds real CSS viewports at 1280, 768, and 375px; choose the page pattern to inspect.

All numbers, names, and domain objects are documentation fixtures. The fixture models are never saved and relations are explicitly assigned. Showcase actions use browser state, toast, or a demo dialog. Fixture links and sample pagination links are intercepted. Public navbar previews reuse the real component and follow the current browser authentication state; authenticated account/notification context is not fabricated.

## Sections

| No. | Board |
| --- | --- |
| 01–07 | Cover, colors, typography, spacing/layout, borders/radius/shadows, icons, pixel/playground assets |
| 08–12 | Buttons, form controls, badges/status, alerts/toast/feedback, card gallery |
| 13–17 | Navigation, dropdown/details/menu, search/filter patterns, tables, modal/dialog |
| 18–21 | Public, Youth, Community, Admin/Verifier page compositions |
| 22–25 | Data visualization, ecosystem map UI, forum, social feed |
| 26–30 | Light/dark, responsive viewports, interaction states, motion, accessibility |
| 31–33 | Do/don’t, reconstructed wireframe vs current composition, screenshot capture guide |

## Reused components and source classes

- `x-brand-logo`, `x-public.navbar`, `x-ui.icon`, `x-ui.button`, `x-ui.badge`, `x-ui.card`, `x-ui.metric-card`, `x-ui.empty-state`.
- Discovery cards: Activity, Community, Opportunity, Program, Youth; media representative and fallback components; `x-public.stat-card`.
- Actual domain badges for Program, Proposal, Activity, Registration, Community, Completion, and Logbook.
- `x-public.filter-panel`, `x-workspace.filter-bar`, `x-workspace.page-header`, `x-public.page-intro`, and Laravel pagination markup.
- CSS tokens are extracted from the current `@theme` and dark override blocks. Aliases and literal brand Cream/White/Black are identified separately.
- Native fields, checkboxes/radios, `.table-shell`, `.workspace-nav-link`, pixel masks/sprites, `.px-scene`, `.interest-chip`, and native `details` compositions.
- `statistics.js` is reused directly with synthetic datasets. The renderer keeps the actual chart axes, palette, tooltip, donut, and theme behavior.
- Map controls use `ecosystem-map.css`. Map geometry is an explicitly labeled illustration, not real boundaries or user locations. Public privacy rule remains counts 1–4 → `<5`, with no individual Youth pins.
- Toast uses the existing `showAlert()`; demo confirmation uses the current SweetAlert2 class names and confirmation options.
- Responsive Youth shell composition uses the existing Alpine `workspaceShell`, including mobile focus/close behavior; no authentication bypass.

Page miniatures are cropped-style compositions derived from source view patterns, not full renders of account-specific pages. Each carries a source label. “Sketch to Final” uses reconstructed low-fidelity compositions and explicitly does not claim historical sketches.

## Observed inconsistencies / design debt

1. Naming: CSS `primary` is Slate, `accent` is Flame. Brand Cream `#EFEEEA` is mostly a decorative literal; `cream-100` is `#FAF8F3`.
2. `font-heading` maps to Pixelify Sans globally, including government workspaces; intended restrained heading usage is not encoded by role.
3. Badge implementations differ: shared badge hardcoded colors, semantic badge CSS, and domain status badges use different palettes and shapes. Some literal colors remain light in dark mode.
4. Radius tokens `md`, `lg`, `xl`, and `2xl` all resolve to 4px, while literal 10px radii and pills also exist. Global pixel frame normalization affects many cards across all roles.
5. Profile completion retains amber/violet literals (`#f59e0b`, `#e8eaf4`, `#243378`); several legacy workspace views have similar direct colors.
6. Light token contrast against `surface #FFFFFD`: text-primary 10.98:1, text-secondary 6.63:1, text-muted 4.42:1, accent-500 2.64:1. Muted normal-size body text misses 4.5:1 for this pair. These are token-pair calculations, not a full accessibility audit.
7. Chart canvas typography is still the Chart.js default. Legend is disabled; chart-empty handling hides the canvas rather than rendering a shared empty component.
8. SweetAlert2 cancel text uses the fixed primary token; in dark mode this can have poor contrast against the surface. Documented without changing product alert CSS.
9. UI button abstraction has no size/accent/trailing-icon/icon-only/loading props; these are existing CSS/markup variants. Frozen state samples live only in documentation CSS.
10. Tables, forum thread cards, feed ecosystem updates, notification rows, native details/context menus, form field variants, and page patterns are still assembled within individual views. There is no universal reusable abstraction for them.
11. No shared toggle/range, field success state, skeleton loader, generic accordion, notification dropdown, or user dropdown was found. Native contextual details menus do exist in manager activity sessions.
12. The requested accessibility widget was not found. Existing features include labels, semantic status text, skip/focus patterns, drawer focus handling, and reduced-motion CSS. Some compact controls are 36px rather than 44px.

These are inventory findings; no automatic design-debt refactor was performed.

## Recommended presentation captures

Use 01 (identity), 02 (palette), 03 (typography), 07 (pixel assets), 08 (buttons), 09 (forms), 12 (cards), 22 (charts), 23 (map/privacy), 26 (light/dark), and 32 (wireframe reconstruction). Pair screenshot crops with their visible headings and fixture/reconstruction labels. Live application pages should be used when a full final-page screenshot is required.

## Validation

- PHP syntax: `routes/public.php` and showcase `data.php` pass.
- Blade: `php artisan view:cache` passes.
- Assets: `npm run build` passes; build required the normal local filesystem outside the Windows sandbox after an EPERM realpath error.
- HTTP render: all 33 section captures and all six preview modes in both themes pass (45/45).
- Production route listing confirms no `design-system` route is registered.
- Browser: actual navbar mobile menu and Escape, demo dialog, mobile Youth drawer open/close, light/dark display, desktop card grid, and table horizontal overflow checked. Browser errors were absent during these checks.
- No migrations, seeders, RefreshDatabase tests, or domain mutations were run.

No commit was requested or created.
