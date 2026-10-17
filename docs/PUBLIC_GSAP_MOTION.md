# Scroll synchronization correction � 15 October 2026

The reveal behavior below has been superseded by direct scroll progress following user feedback. Section and card animations now use `scrub: true`, linear progress, and a shared viewport range from `top 98%` to `top 65%`. There is no timed catch-up, autonomous onEnter playback or delayed card stagger. Cards in each rendered row move together. Movement is 22px desktop / 12px mobile, with a partially visible starting opacity of .35. Counter progress follows the same scroll position. Existing visible content is skipped at initialization to prevent a late entrance flash.

CSS opacity/transform transitions are disabled temporarily on active reveal targets so they cannot trail GSAP's inline updates. On completion, targets retain their final appearance, restore normal hover transitions and do not replay. Hero entrance remains a separate initial-load timeline. Reduced-motion cleanup and opacity-only map/chart wrappers remain supported.

Validation: production build passed; eight focused frontend tests passed. Browser measurements at scrollY 256 showed intermediate section opacities .7093/.6504 and travel 9.84/11.83px, confirming the reveal is between its start/end positions rather than starting a timed animation after scroll. See follow-up browser evidence under `output/public-motion/scroll-sync.jpg`.

---

# Public GSAP motion enhancement

Implemented 15 October 2026. No automatic commit or database changes.

## Integration and shared architecture

The installed GSAP 3.15 dependency is reused. `resources/js/scroll-motion.js` keeps the existing back-to-top behavior and dynamically imports `resources/js/animations.js` only inside a `data-public-motion` scope, when reduced motion is off. Vite emits a separate animation chunk (116.46 kB, 46.13 kB gzip). Workspace layouts do not initialize this module.

The module registers ScrollTrigger and implements `fade-up`, `fade-left`, `fade-right`, `scale-in`, `stagger-card`, `section-title`, and the opacity-only `map-reveal` pattern. Triggers start at `top 85%` and run once. Sibling cards are grouped by rendered row with one trigger per row and at most twelve cards per group. Nested reveal annotations inside animated sections are skipped to prevent double animation.

Implementation follows the official [ScrollTrigger](https://gsap.com/docs/v3/Plugins/ScrollTrigger/) and [matchMedia](https://gsap.com/docs/v3/GSAP/gsap.matchMedia%28%29/) APIs.

## Presentation behavior

- Hero: eyebrow at 0s, headline from the left at 0.10s, supporting copy at 0.20s, CTA buttons at 0.35s, visual from the right at 0.20s with scale .98. Desktop completion is approximately 1.06s, using power2.out.
- Section titles: fade up 20px over .55s on desktop. Split sections use predictable left/right entrances.
- Cards: row-based fade-up with .09s desktop stagger. Activity, Community, Opportunity, Program, home news previews, statistics, and registration-flow steps share the pattern.
- Statistics: existing server values remain the source of truth. Only annotated integer values count up over .65s; masked `<5` values, dates and labels remain static. Screen readers retain the final value. Media changes and page-cache cleanup restore counter text.
- Registration: the homepage flow reveals left-to-right on desktop and one visible row at a time from top-to-bottom on mobile. The registration page annotates only its informational guide; its form stays outside the motion scope.
- Map compatibility: the outer map panel animates opacity only. Leaflet containers, tiles, markers and canvas geometry receive no GSAP transforms. Dimensions remain unchanged, so no extra invalidateSize call is necessary. Statistics chart wrappers also use opacity only.
- CTA blocks use scale .98 and a small upward entrance. Footer body uses one fade-up, without separate link animations.
- Mobile below 768px: travel drops to 12–14px, reveal duration to .40s, hero duration to .45s, and stagger to .06s.
- Existing 120ms main-content entry and native navigation behavior remain intact. GSAP does not animate that main wrapper or intercept links.

## Accessibility and fallback

Raw HTML has no hidden reveal classes or opacity-zero styles. Optional import failure leaves content usable. Reduced motion prevents reveal initialization; changing the preference reverts existing GSAP contexts, clears their inline animation styles and restores original counts. Breakpoint changes retain a completed-element set so visited sections do not replay. Pagehide also reverts styles for browser history restoration. No continuous loops, particles, scroll hijacking, focus changes or business-rule changes were added.

## Files in this phase

- `resources/js/animations.js`: shared GSAP/ScrollTrigger implementation.
- `resources/js/scroll-motion.js`: optional public-module bootstrap; replaces the earlier IntersectionObserver reveal implementation.
- `resources/views/layouts/public.blade.php`: public scope marker.
- `resources/views/components/public/{page-intro,section-heading,stat-card,footer,ecosystem-map-panel}.blade.php`: reusable annotations.
- `resources/views/components/discovery/{activity-card,community-card,opportunity-card,program-card}.blade.php`: shared card annotations.
- `resources/views/home.blade.php`: hero, split introduction, statistics, registration, discovery cards, intelligence, search CTA and contact introduction.
- `resources/views/public/{activities,communities,opportunities}/show.blade.php`: detail heroes, titles and media.
- `resources/views/public/{statistics/index,about,contact}.blade.php`: chart wrappers, titles and selected informational cards/CTA.
- `resources/views/ecosystem-map/index.blade.php`: public intro; Admin excluded.
- `resources/views/auth/register.blade.php`: informational guide only.
- `tests/Frontend/public-motion.test.mjs`: reduced motion, once-only behavior, counter restoration, opacity-only map/chart effects and mobile travel.

No dependency installation was necessary during this phase. Existing unrelated working-tree changes were retained.

## Verification

- `npm run build`: passed, 91 modules.
- `php artisan view:cache`: passed.
- `php artisan test --testsuite=Unit`: 4 passed, 15 assertions. The sandbox initially could not read PHPUnit's bootstrap; rerunning this same Unit-only command outside the sandbox passed.
- `node --test tests/Frontend/*.test.mjs`: 13 passed (4 motion, 4 native navigation, 5 existing chat behavior tests).
- `git diff --check`: passed; existing CRLF normalization notices only.
- Browser QA: Home at 1920×1080 and 390×844, Activity/Community/Opportunity directory and representative detail pages, Statistics, Ecosystem Maps, About and Contact all rendered with content and no horizontal page overflow. Home hero reached final opacity 1. Mobile registration layout has one 344.8px column. Light and dark modes checked on mobile; theme and viewport restored afterward.
- Map: public container measured 810×542 with computed transform `none`. Its renderer uses Canvas, so an SVG path count of zero is expected. No map geometry or sizing logic changed.
- Evidence: `output/public-motion/qa.json`, `home-desktop.jpg`, `home-mobile.jpg`, `home-mobile-dark.jpg` and `preview.jpg`.

## Remaining verification limits

The full Feature suite was deliberately not run: `phpunit.xml` forces the live local demo database `sipora`, `tests/TestCase.php` enforces that database, and many Feature tests use RefreshDatabase. Running them would risk rebuilding the golden demo dataset. An isolated test database configuration is needed before a full run.

Reduced-motion behavior was exercised through the frontend harness, not by changing the operating-system preference. Registration form submission, Admin/Verifier workflows and every record were not retested. The QA run checks rendering and representative interactions; it is not a measured 30-FPS recording benchmark or an exhaustive performance trace.
