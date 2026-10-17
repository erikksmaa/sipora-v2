// Documentation interactions only; no writes to application endpoints.
import { showAlert } from './alerts';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import './statistics';

const setTheme = (theme) => {
    document.documentElement.dataset.theme = theme;
    window.dispatchEvent(new Event('sipora:theme'));
    const button = document.getElementById('ds-theme');
    if (button) {
        button.setAttribute('aria-pressed', String(theme === 'dark'));
        button.textContent = theme === 'dark' ? 'Light mode' : 'Dark mode';
    }
    for (const link of document.querySelectorAll('.ds-capture-link, #ds-capture-all')) {
        const url = new URL(link.href);
        url.searchParams.set('theme', theme);
        link.href = url.href;
    }
};
document.getElementById('ds-theme')?.addEventListener('click', () => setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark'));
document.getElementById('ds-jump')?.addEventListener('change', (event) => { location.hash = event.target.value; });
document.getElementById('ds-pause')?.addEventListener('click', (event) => {
    const paused = document.body.classList.toggle('ds-paused');
    event.currentTarget.setAttribute('aria-pressed', String(paused));
    event.currentTarget.textContent = paused ? 'Lanjutkan animasi' : 'Hentikan animasi';
    for (const iframe of document.querySelectorAll('iframe')) {
        iframe.contentDocument?.body.classList.toggle('ds-paused', paused);
    }
});
document.getElementById('ds-responsive-kind')?.addEventListener('change', (event) => {
    for (const iframe of document.querySelectorAll('[data-responsive-frame]')) {
        const url = new URL(iframe.src);
        url.searchParams.set('preview', event.target.value);
        url.searchParams.set('theme', document.documentElement.dataset.theme);
        iframe.src = url.href;
    }
});
document.addEventListener('submit', (event) => {
    if (!event.target.closest('[data-demo-form]')) return;
    event.preventDefault();
    showAlert('info', 'Filter contoh diterapkan hanya sebagai preview dokumentasi.');
});
document.addEventListener('click', (event) => {
    const demoLink = event.target.closest('a');
    if (demoLink && (/\/showcase-(activity|community|opportunity|program)(?:[/?#]|$)/.test(demoLink.href) || demoLink.href.includes('demo-page='))) {
        event.preventDefault();
        showAlert('info', 'Data contoh dokumentasi; tidak terhubung ke record aplikasi.');
    }
    const toast = event.target.closest('[data-demo-toast]');
    if (toast) showAlert(toast.dataset.demoToast, 'Preview feedback SIPORA. Tidak ada data aplikasi yang diubah.');
    const dialog = event.target.closest('[data-demo-dialog]');
    if (dialog) Swal.fire({
        titleText: dialog.dataset.demoDialog === 'destructive' ? 'Hapus data contoh?' : 'Konfirmasi tindakan contoh',
        text: 'Demo dokumentasi saja. Tidak ada data yang disimpan, dihapus, atau dikirim.',
        icon: 'warning', showCancelButton: true, focusCancel: true, reverseButtons: true,
        allowOutsideClick: false, heightAuto: false,
        confirmButtonText: 'Selesai preview', cancelButtonText: 'Batal',
    });
    const pressed = event.target.closest('[data-demo-pressed], [data-demo-follow]');
    if (pressed) {
        const selected = pressed.getAttribute('aria-pressed') !== 'true';
        pressed.setAttribute('aria-pressed', String(selected));
        if (pressed.hasAttribute('data-demo-follow')) pressed.textContent = selected ? 'Mengikuti' : 'Ikuti';
    }
    const category = event.target.closest('[data-demo-select]');
    if (category) {
        for (const item of category.parentElement.querySelectorAll('[data-demo-select]')) {
            item.classList.toggle('workspace-nav-active', item === category);
            item.setAttribute('aria-pressed', String(item === category));
        }
    }
});
const luminance = (hex) => {
    const values = hex.replace('#', '').match(/.{2}/g).map(value => parseInt(value, 16) / 255).map(value => value <= .04045 ? value / 12.92 : ((value + .055) / 1.055) ** 2.4);
    return .2126 * values[0] + .7152 * values[1] + .0722 * values[2];
};
for (const sample of document.querySelectorAll('[data-contrast-color]')) {
    const values = [luminance(sample.dataset.contrastColor), luminance(sample.dataset.contrastBg)].sort((a, b) => b - a);
    sample.querySelector('[data-contrast-value]').textContent = `${((values[0] + .05) / (values[1] + .05)).toFixed(2)} : 1`;
}
const indexLinks = [...document.querySelectorAll('.ds-index a')];
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        const entry = entries.find(item => item.isIntersecting);
        if (!entry) return;
        for (const link of indexLinks) {
            if (link.hash === `#${entry.target.id}`) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        }
        const jump = document.getElementById('ds-jump');
        if (jump) jump.value = entry.target.id;
    }, { rootMargin: '-80px 0px -65% 0px' });
    document.querySelectorAll('.ds-section').forEach(section => observer.observe(section));
}
