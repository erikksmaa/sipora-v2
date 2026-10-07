// Replay through requestSubmit so validation, submitter values, and Alpine still run.
export function installConfirmations(root, confirm) {
    const approved = new WeakSet();
    let pending = false;
    root.addEventListener('submit', async (event) => {
        const form = event.target;
        if (approved.delete(form) || event.defaultPrevented) return;
        const submitter = event.submitter;
        const method = (submitter?.getAttribute('formmethod') || form.method || 'get').toLowerCase();
        if (method === 'get' || method === 'dialog' || !form.hasAttribute('data-confirm') || form.dataset.confirm === 'false') return;
        event.preventDefault();
        event.stopImmediatePropagation();
        if (pending) return;
        pending = true;
        try {
            const deleting = form.querySelector('[name="_method"]')?.value.toUpperCase() === 'DELETE';
            const label = submitter?.textContent?.trim().replace(/\s+/g, ' ').slice(0, 100);
            const result = await confirm({
                titleText: form.dataset.confirmTitle || (label ? label + '?' : 'Konfirmasi tindakan'),
                text: form.dataset.confirm || (deleting ? 'Data akan dihapus atau dibatalkan. Pastikan tindakan ini sudah sesuai.' : 'Pastikan data dan pilihan Anda sudah benar sebelum melanjutkan.'),
                confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
            });
            if (result.isConfirmed && form.isConnected) {
                approved.add(form);
                try { form.requestSubmit(submitter || undefined); }
                finally { approved.delete(form); }
            }
        } finally {
            pending = false;
        }
    }, true);
}
