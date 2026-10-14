# SIPORA public home & registration — implementation and QA

Date: 14 October 2026. No automatic commit. No schema, auth, eligibility, authorization or business workflow changes.

## 1. Home sections
Navbar → Hero → Apa itu SIPORA? → Quick Statistics → Ecosystem Maps preview → Alur Pendaftaran → latest Activity/Community/Opportunity + existing public Program → Youth Intelligence → Forum/Social Feed → Contact/Google Maps → Footer.
Existing search and interest discovery remain available inside the latest-content area. Existing discovery query ordering and public availability rules are preserved.

## 2. Apa itu SIPORA?
Responsive feature mockup on the left, explanation and account/discovery buttons on the right. The mockup is explicitly a feature illustration, with no invented personal records or testimonial. Hero and registration reuse a tracked SIPORA demo workshop photograph, labelled as demo imagery.

## 3. Registration and real onboarding
Desktop split guidance/form with a five-step overview: account → email verification → profile/identity review → interests/skills/Portfolio → ecosystem. The stepper explains the existing workflow; it is not a new client-side registration wizard or a way to skip onboarding gates. Mobile shows compact guidance and progress above a full-width form.
Submission still uses RegisterUser and RegistrationController and redirects to verification.notice. Identity is still reviewed separately. No auto-verification. Google OAuth remains the existing convenience flow and is explicitly distinguished from identity verification.

## 4. Actual fields
name, email, password, password_confirmation. Existing CSRF, server validation, old-input/error handling, password requirements and reCAPTCHA are reused. No NIK, religion or extra registration fields added.

## 5. Map and contact
The homepage and full map page reuse the same extracted Blade map panel, existing Leaflet bundle, existing district GeoJSON, and geographic aggregates. No individual location or invented coordinates.
Google Maps uses the project-owner supplied embed/CID in config/sipora.php. That embed names Dinas Pendidikan dan Kebudayaan Kabupaten Pemalang; SIPORA contact identity remains Dindikpora. No street address or contact number was guessed. Address/email/phone/hours/official-site are displayed only when configured.

## 6. Statistics sources and privacy
YouthStatisticsService::public() supplies suppressed Youth totals, interests, skills and geographic coverage; landing summary uses its existing totals rather than recomputing the Youth formula. PublicStatisticsService supplies public active Community, published Activity and published Opportunity counts using existing domain rules.
Counts 1–4 display <5. Zero stays zero. Suppressed interest/skill counts do not produce a proportional bar that could reveal their value; a neutral hatch is used. No scores or fake chart values. Dataset context is visible, and it does not claim to represent all Pemalang youth.
Forum highlights only active/locked threads in active categories; SocialPost::published() excludes hidden/removed/private posts and inactive/unapproved Community posts. Relations are eager loaded, and UGC is escaped by Blade.

## 7. Responsive behavior
Verified 1440×1000, 768×1024 and 390×844. Complex landing cards use one mobile/two tablet/three desktop columns; public directory grids use one mobile/two tablet/four large-desktop columns. Headline metrics use two mobile/four desktop columns. Registration guide collapses on mobile. Home onboarding is horizontal on desktop and stacked below the desktop breakpoint. No horizontal page overflow in the checked matrix.

## 8. Clickable cards
Existing full-anchor Activity/Community cards are preserved; Opportunity/Program/Youth/Forum/recommendation cards use a stretched primary link and separately stacked author/actions. No nested anchors found. Detail card actions use button styling. Shared focus and hover states remain.

## 9. Forum identity
Shared author component respects public-profile visibility, optional photo visibility and public slug availability. Private Youth uses the existing safe display alias and gets no public profile link/photo. Category badges use muted color palettes.
The existing Forum domain has Youth authors, not Community-authored threads; no unsupported Community authorship/schema was invented. Community Feed highlights use the Community name/detail link.

## 10. Social Feed media
Constrained responsive containers with object-fit: contain retain image proportions and avoid oversized desktop media. Existing media routes/storage/privacy are preserved.

## 11. Dark mode and palette
Plus Jakarta Sans for UI. Flame #FE7743 buttons with white text; Slate #273F4F; Cream #EFEEEA home background. Layered slate semantic surfaces, visible borders, readable muted text. White Community logo surfaces remain in both themes. Fixed hero/registration heading inheritance and dark registration CTA override. Hover retains the existing lighter orange token.

## 12. Accessibility
Semantic form labels, required fields, error associations, CSRF, accessible progress/current-step, keyboard links/buttons, focus styles, existing skip link and public accessibility widget are retained. Mobile filter/category toggles expose expanded state. Map dropdown provides an alternative to map shapes; full map keeps an accessible aggregate table.
The requested white text on bright Flame is retained; no claim of a complete WCAG contrast audit is made.

## 13. Modified files
The list below includes the ongoing frontend polish changes retained in this task, alongside this home/registration redesign. The user-modified notification fixture was not changed by this task.

- [app/Presenters/PublicLandingPresenter.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/app/Presenters/PublicLandingPresenter.php>)
- [config/sipora.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/config/sipora.php>)
- [resources/css/app.css](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/css/app.css>)
- [resources/css/ecosystem-map.css](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/css/ecosystem-map.css>)
- [resources/css/statistics.css](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/css/statistics.css>)
- [resources/views/auth/register.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/auth/register.blade.php>)
- [resources/views/components/discovery/activity-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/discovery/activity-card.blade.php>)
- [resources/views/components/discovery/community-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/discovery/community-card.blade.php>)
- [resources/views/components/discovery/opportunity-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/discovery/opportunity-card.blade.php>)
- [resources/views/components/discovery/program-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/discovery/program-card.blade.php>)
- [resources/views/components/discovery/youth-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/discovery/youth-card.blade.php>)
- [resources/views/components/forum/category-badge.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/forum/category-badge.blade.php>)
- [resources/views/components/media/community.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/media/community.blade.php>)
- [resources/views/components/portfolio/provenance-badge.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/portfolio/provenance-badge.blade.php>)
- [resources/views/components/public/author-identity.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/public/author-identity.blade.php>)
- [resources/views/components/public/ecosystem-map-panel.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/public/ecosystem-map-panel.blade.php>)
- [resources/views/components/public/filter-panel.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/public/filter-panel.blade.php>)
- [resources/views/components/public/footer.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/public/footer.blade.php>)
- [resources/views/components/public/navbar.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/public/navbar.blade.php>)
- [resources/views/components/ui/badge.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/components/ui/badge.blade.php>)
- [resources/views/design-system/data.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/design-system/data.php>)
- [resources/views/design-system/foundations.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/design-system/foundations.blade.php>)
- [resources/views/design-system/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/design-system/index.blade.php>)
- [resources/views/ecosystem-map/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/ecosystem-map/index.blade.php>)
- [resources/views/forum/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/forum/index.blade.php>)
- [resources/views/forum/show.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/forum/show.blade.php>)
- [resources/views/home.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/home.blade.php>)
- [resources/views/layouts/auth.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/layouts/auth.blade.php>)
- [resources/views/layouts/base.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/layouts/base.blade.php>)
- [resources/views/layouts/public.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/layouts/public.blade.php>)
- [resources/views/layouts/workspace.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/layouts/workspace.blade.php>)
- [resources/views/portfolio/show.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/portfolio/show.blade.php>)
- [resources/views/public/activities/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/activities/index.blade.php>)
- [resources/views/public/communities/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/communities/index.blade.php>)
- [resources/views/public/communities/show.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/communities/show.blade.php>)
- [resources/views/public/contact.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/contact.blade.php>)
- [resources/views/public/opportunities/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/opportunities/index.blade.php>)
- [resources/views/public/programs/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/programs/index.blade.php>)
- [resources/views/public/search/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/search/index.blade.php>)
- [resources/views/public/statistics/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/statistics/index.blade.php>)
- [resources/views/public/youth/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/public/youth/index.blade.php>)
- [resources/views/social/post-card.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/social/post-card.blade.php>)
- [resources/views/youth/development-pathway/index.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/youth/development-pathway/index.blade.php>)
- [resources/views/youth/home.blade.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/resources/views/youth/home.blade.php>)
- [tests/Feature/DesignSystemRenderingTest.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/tests/Feature/DesignSystemRenderingTest.php>)
- [tests/Feature/FrontendPolishTest.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/tests/Feature/FrontendPolishTest.php>)
- [tests/Feature/PublicLandingPageTest.php](<C:/laragon/www/projects/lomba kabupaten/sipora-v2/tests/Feature/PublicLandingPageTest.php>)

## 14. Tests and browser QA
- Targeted: 12 passed / 63 assertions, including landing ordering, real-domain data, registration fields, suppression, hidden Forum exclusion, map privacy and private author projection.
- Final full regression: 291 passed / 2,346 assertions, 222.15 seconds.
- Tests ran against isolated sipora_attendance_test; the repository's TestCase/phpunit settings were restored afterwards. Development sipora data was not reset.
- Browser: nine requested pages × three sizes × two themes (54 combinations). All rendered, no horizontal page overflow and no nested anchors. Two extra initial captures are included in browser-checks.json.
- Interactive smoke: home map Community layer + Pemalang selection, suppressed district details, mobile account-menu ordering, Forum category expansion, Statistics filter expansion and dark registration button color.
- Fixed and rechecked mobile Statistics filter: static hidden class had prevented display despite expanded=true.
- No browser console errors in the final home inspection.

## 15. Build and performance
npm run build: passed, 79 modules. Final app CSS approximately 198 KB uncompressed / 34 KB gzip. No new dependency.
Blade view:cache passed; PHP syntax checked; Pint applied to presenter/changed feature tests.
Measured development presenter: 59 queries, total SQL 148.22 ms, overall presenter 288.63 ms, with 14 district aggregates and two Forum/two Feed highlights. Measurements are local demo sanity checks, not load-test results. The existing full statistics aggregate is reused; it includes additional chart datasets beyond the compact landing preview, so there is room for a future focused service projection if needed. No per-card lazy author/profile queries were introduced.

## 16. Remaining information / limits
Official street address, email, phone, service hours and official external website remain unconfigured and are intentionally omitted. The owner-supplied map location is shown with its actual embed label. Google map/reCAPTCHA/font availability depends on their external services.
No schema changes, no new authentication steps, no automatic commit.

Evidence: storage/app/home-redesign-qa/ (screenshots, browser-checks.json, interaction-checks.json, query-performance.json).
Logs: storage/logs/home-redesign-targeted.log, home-redesign-regression.log, home-redesign-build.log.
