const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const backToTop = document.querySelector('[data-back-to-top]');

if (backToTop) {
    let pending = false;
    const update = () => {
        backToTop.hidden = window.scrollY < 400;
        pending = false;
    };
    window.addEventListener('scroll', () => {
        if (!pending) {
            pending = true;
            window.requestAnimationFrame(update);
        }
    }, { passive: true });
    window.addEventListener('pageshow', update);
    backToTop.addEventListener('click', () => {
        document.querySelector('#main')?.focus({ preventScroll: true });
        window.scrollTo({ top: 0, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
    });
    update();
}

// Public animation code is downloaded separately; workspace pages never initialize it.
const motionRoot = document.querySelector('[data-public-motion]');
if (motionRoot && !reducedMotion.matches) {
    import('./animations').then(({ initializePublicMotion }) => initializePublicMotion(motionRoot))
        .catch(() => { /* HTML remains visible if the optional animation chunk fails. */ });
}
