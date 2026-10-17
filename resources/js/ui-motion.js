// Native links and form submissions keep their normal browser behavior.
const main = document.querySelector('[data-page-motion]');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
let loadingTimer;
let recoveryTimer;

const sidebar = document.querySelector('#workspace-sidebar');
const activeLink = sidebar?.querySelector('[aria-current="page"]');
try {
    const previous = JSON.parse(sessionStorage.getItem('sipora.motion.navigation') || 'null');
    sessionStorage.removeItem('sipora.motion.navigation');
    if (activeLink && previous && previous.target === location.pathname + location.search
        && previous.workspace === document.body.dataset.workspace
        && previous.group === document.body.dataset.currentGroup
        && Date.now() - previous.at < 10000 && !reducedMotion.matches) {
        requestAnimationFrame(() => {
            const offset = previous.top - activeLink.getBoundingClientRect().top;
            if (Math.abs(offset) < 400) {
                activeLink.style.setProperty('--indicator-offset', `${offset}px`);
                activeLink.classList.add('nav-indicator-arriving');
            }
        });
    }
} catch (_) { /* Storage is optional. */ }

function resetNavigation() {
    clearTimeout(loadingTimer);
    clearTimeout(recoveryTimer);
    main?.classList.remove('is-leaving');
    document.querySelector('[data-navigation-progress]')?.remove();
    document.querySelectorAll('.workspace-nav-pending').forEach((link) => link.classList.remove('workspace-nav-pending'));
}

function beginNavigation() {
    resetNavigation();
    main?.classList.add('is-leaving');
    loadingTimer = setTimeout(() => {
        const progress = document.createElement('div');
        progress.dataset.navigationProgress = '';
        progress.setAttribute('role', 'status');
        const label = document.createElement('span');
        label.className = 'sr-only';
        label.textContent = 'Memuat halaman…';
        progress.append(label);
        document.body.append(progress);
    }, 350);
    // Recover when a navigation turns into a download, is cancelled, or stalls.
    recoveryTimer = setTimeout(resetNavigation, 10000);
}

document.addEventListener('click', (event) => {
    const link = event.target.closest?.('a[href]');
    if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (link.hasAttribute('download') || link.hasAttribute('data-no-page-motion') || (link.target && link.target !== '_self')) return;
    const url = new URL(link.href, location.href);
    if (url.origin !== location.origin || !['http:', 'https:'].includes(url.protocol)) return;
    if (url.pathname === location.pathname && url.search === location.search) return;
    // Media/PDF endpoints commonly stream files rather than render a new page.
    if (/\.(pdf|png|jpe?g|webp|zip)$/i.test(url.pathname) || /\/(download|export|media)(\/|$)/.test(url.pathname)) return;
    beginNavigation();
    if (link.matches('.workspace-nav-link')) {
        link.classList.add('workspace-nav-pending');
        try {
            sessionStorage.setItem('sipora.motion.navigation', JSON.stringify({
                target: url.pathname + url.search,
                workspace: document.body.dataset.workspace,
                group: document.body.dataset.currentGroup,
                top: activeLink?.getBoundingClientRect().top ?? link.getBoundingClientRect().top,
                at: Date.now(),
            }));
        } catch (_) { /* Storage is optional. */ }
    }
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (event.defaultPrevented || (form.target && form.target !== '_self') || form.hasAttribute('data-no-page-motion')) return;
    const action = new URL(form.action, location.href);
    if (action.origin === location.origin && form.method.toLowerCase() === 'get') beginNavigation();
});

window.addEventListener('pageshow', resetNavigation);
window.addEventListener('pagehide', resetNavigation);
window.addEventListener('focus', resetNavigation);
reducedMotion.addEventListener('change', resetNavigation);
