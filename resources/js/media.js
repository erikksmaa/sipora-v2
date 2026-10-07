// Only media-marked presentation images; never rewrite a private URL into a public one.
function fallback(image) {
    if (!(image instanceof HTMLImageElement) || !image.hasAttribute('data-media-fallback')) return;
    const replacement = document.createElement('span');
    replacement.className = image.className + ' media-failed';
    replacement.setAttribute('role', 'img');
    replacement.setAttribute('aria-label', image.alt || 'Visuel tidak tersedia');
    replacement.textContent = image.dataset.mediaInitials || 'Visual tidak tersedia';
    image.replaceWith(replacement);
}
document.addEventListener('error', event => fallback(event.target), true);
function checkMedia() {
    document.querySelectorAll('img[data-media-fallback]').forEach(image => {
        if (image.complete && image.naturalWidth === 0) fallback(image);
    });
}
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', checkMedia, { once: true });
else checkMedia();

