# UI motion polish

The existing public, base, auth and shared workspace layouts now mark their main content with `data-page-motion`. Youth, Community, Verifier and Admin continue to use the same workspace navigation and Alpine state.

- Content entry: fade from .65 to 1 and translateY(4px) to 0, 120ms ease-out (shortened following user feedback).
- Content exit: opacity .85 over 60ms, without intercepting or delaying native navigation.
- Loading feedback: a non-blocking 3px progress strip after 350ms for native internal page links and GET forms. No synthetic skeleton or artificial wait. Async actions retain their existing loading states.
- Sidebar: existing 200ms width/transform transition, label fade/4px slide, 180ms group fade, stable horizontal icon alignment. Mobile labels remain available even when desktop collapse is persisted.
- Active feedback: pending link color and a 200ms moving accent on same-group navigation when the previous position is within 400px. Other transitions keep the static active state. Optional session storage failures do not prevent navigation.
- Reduced motion: CSS disables the new animations, movement and exit dimming. The existing global reduced-motion rule also covers Alpine transitions. Content has no permanent hidden starting state.
- Modified clicks, same-page anchors, external links, downloads and cancelled actions bypass navigation feedback. Page history restoration, focus and a recovery timeout clear pending feedback.

Validation: Vite production build and Blade view compilation passed. Four Node tests cover native navigation, exclusions, recovery and GET form behavior. Browser checks verified public and Youth content entry at 220ms, desktop sidebar collapse to 76px and expansion to 264px, same-group active indicator animation, and mobile labels with a persisted collapsed preference. Other roles inherit the inspected shared layout; their authenticated flows were not retested. Reduced-motion CSS was inspected, without changing the operating system preference. Preview: `output/ui-motion/workspace-preview.jpg`.

No new motion library, database changes or automatic commit.
