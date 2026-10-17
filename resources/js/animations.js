// Omah Nini-style component entrance: native IntersectionObserver + CSS states.
// No scroll scrubbing and no permanently hidden raw HTML.
export function initializePublicMotion(root) {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (reduced.matches || !('IntersectionObserver' in window)) return;
    const tracked = new Set();
    let observer;
    const finish = element => {
        element.classList.remove('component-enter', 'component-enter-visible');
        element.style.removeProperty('--component-enter-delay');
        tracked.delete(element);
    };
    const completeAll = () => {
        observer?.disconnect();
        [...tracked].forEach(finish);
    };
    const eligible = element => !element.closest('form, table, [data-workspace]')
        && !element.parentElement.closest('[data-animate], [data-hero]');
    const candidates = [...root.querySelectorAll('[data-animate], [data-hero]')].filter(eligible);
    const groups = new Map();
    candidates.forEach(element => {
        const rect = element.getBoundingClientRect();
        // Preserve history restoration and elements hidden by Alpine/tab panels.
        if (rect.bottom <= 0 || rect.width === 0 || rect.height === 0) return;
        const group = element.closest('[data-motion-hero]') || element.parentElement;
        if (!groups.has(group)) groups.set(group, []);
        groups.get(group).push(element);
    });
    groups.forEach((elements, group) => {
        // Restart the cascade on each visual row, including responsive grids.
        const rows = new Map();
        elements.forEach(element => {
            const top = group.hasAttribute('data-motion-hero') ? 0 : Math.round(element.getBoundingClientRect().top);
            if (!rows.has(top)) rows.set(top, []);
            rows.get(top).push(element);
        });
        rows.forEach(row => row.forEach((element, index) => {
            element.style.setProperty('--component-enter-delay', `${Math.min(index * 120, 360)}ms`);
            element.classList.add('component-enter');
            tracked.add(element);
            element.addEventListener('transitionend', event => {
                if (event.target === element && event.propertyName === 'opacity') finish(element);
            });
        }));
    });
    const show = element => {
        if (!tracked.has(element)) return;
        element.classList.add('component-enter-visible');
        observer.unobserve(element);
    };
    observer = new IntersectionObserver(entries => {
        entries.forEach(entry => { if (entry.isIntersecting) show(entry.target); });
    }, { threshold: 0.1 });
    // Commit the initial state once, then start the visible entrance on the next frame.
    // This also animates cards/headings above the fold on load and reload.
    if (tracked.size) root.getBoundingClientRect();
    window.requestAnimationFrame(() => {
        if (reduced.matches) { completeAll(); return; }
        tracked.forEach(element => {
            const rect = element.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) show(element);
            else observer.observe(element);
        });
    });
    // Never let keyboard focus land on a transparent control.
    root.addEventListener('focusin', event => {
        for (const element of tracked) {
            if (element.contains(event.target)) { observer.unobserve(element); finish(element); }
        }
    });
    reduced.addEventListener('change', event => { if (event.matches) completeAll(); });
    window.addEventListener('pagehide', completeAll);
    window.addEventListener('pageshow', event => { if (event.persisted) completeAll(); });
}
