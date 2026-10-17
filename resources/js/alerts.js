import Swal from 'sweetalert2/dist/sweetalert2.js';
import { installConfirmations } from './confirmation';

const dialog = Swal.mixin({
    heightAuto: false,
    confirmButtonText: 'Mengerti',
    cancelButtonText: 'Batal',
});

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    showCloseButton: true,
    closeButtonAriaLabel: 'Tutup pemberitahuan',
    timer: 2000,
    timerProgressBar: true,
    didOpen: (element) => {
        element.addEventListener('mouseenter', Swal.stopTimer);
        element.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

export function showAlert(icon, text, titleText) {
    const titles = { success: 'Berhasil!', error: 'Proses belum berhasil', warning: 'Perhatian', info: 'Informasi' };
    return toast.fire({ icon, titleText: titleText || titles[icon] || titles.info, text });
}

function bootAlerts() {
    const data = document.getElementById('sipora-alert-data');
    const messages = data ? JSON.parse(data.textContent) : [];
    installConfirmations(document, (options) => dialog.fire({
        ...options, icon: 'warning', showCancelButton: true,
        focusCancel: true, reverseButtons: true, allowOutsideClick: false,
    }));
    // The server determines success: never announce success merely on submit.
    (async () => {
        for (const message of messages) await showAlert(message.icon, message.text);
    })();
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bootAlerts, { once: true });
else bootAlerts();
