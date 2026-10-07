import Swal from 'sweetalert2/dist/sweetalert2.js';
import { installConfirmations } from './confirmation';

const dialog = Swal.mixin({
    buttonsStyling: false,
    heightAuto: false,
    confirmButtonText: 'Mengerti',
    cancelButtonText: 'Batal',
    customClass: {
        popup: 'sipora-alert',
        title: 'sipora-alert-title',
        htmlContainer: 'sipora-alert-message',
        actions: 'sipora-alert-actions',
        confirmButton: 'sipora-alert-button sipora-alert-confirm',
        cancelButton: 'sipora-alert-button sipora-alert-cancel',
    },
});

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    showCloseButton: true,
    timer: 5000,
    timerProgressBar: true,
    heightAuto: false,
    customClass: { popup: 'sipora-toast', title: 'sipora-toast-title' },
    didOpen: (element) => {
        element.addEventListener('mouseenter', Swal.stopTimer);
        element.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

export function showAlert(icon, text, titleText) {
    const titles = { success: 'Berhasil!', error: 'Proses belum berhasil', warning: 'Perhatian', info: 'Informasi' };
    return toast.fire({ icon, titleText: titleText || titles[icon] || titles.info, text, timer: icon === 'error' ? 8000 : 5000 });
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
