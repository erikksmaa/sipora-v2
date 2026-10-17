# Component entrance - 17 October 2026

Supersedes the earlier PUBLIC_GSAP_MOTION.md behavior. Implements the supplied Omah Nini reference using CSS transitions and IntersectionObserver.

- Visible components enter on load/reload without a scroll event.
- Fade-in and upward travel: 20px desktop / 14px mobile, 550ms, cubic-bezier(.16,1,.3,1).
- Cards cascade by 70ms, capped at 210ms and reset per rendered grid row.
- Offscreen components enter once at 10% visibility. The hidden initial state has no transition, preventing an accidental fade-out before entrance.
- Nested animations, forms, tables and workspaces are excluded; map/chart wrappers fade without transforms.
- Public main-wrapper entrance is disabled to avoid stacking animations. Navigation, sidebar and back-to-top behavior is preserved.
- Reduced motion, keyboard focus, history restoration and no-JavaScript fallbacks keep content visible. Statistics retain final source values.
- Animation runtime chunk: 1.79 kB (0.86 kB gzip); this module no longer imports GSAP.

Changed: resources/js/animations.js, resources/css/scroll-motion.css, resources/css/ui-motion.css and tests/Frontend/public-motion.test.mjs.

Validation: production build and nine focused frontend tests passed. Browser QA checked load/reload, initial viewport entrance without scrolling, CSS duration/delays, offscreen state and Activity grid entrance. No database changes or automatic commit.
