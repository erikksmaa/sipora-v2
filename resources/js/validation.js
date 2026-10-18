// Translate the browser's existing constraints; do not introduce new rules.
export function nativeValidationMessage(field) {
    const state = field.validity;
    if (!state || state.valid) return '';
    const label = (field.getAttribute('data-validation-label') || field.getAttribute('aria-label') || field.labels?.[0]?.textContent || '').replace(/\*/g, '').replace(/\s+/g, ' ').trim();
    const passwordLabels = {password: 'kata sandi', password_confirmation: 'konfirmasi kata sandi', current_password: 'kata sandi saat ini', admin_password: 'kata sandi Administrator'};
    const name = passwordLabels[field.name] || (label && label.length <= 80 ? label : 'ini');
    if (state.valueMissing && field.type === 'radio') return `Pilih salah satu ${name}.`;
    if (state.valueMissing) return field.type === 'checkbox' ? `Pilihan ${name} wajib disetujui.` : `Kolom ${name} wajib diisi.`;
    if (state.typeMismatch) return field.type === 'email' ? `Kolom ${name} harus berupa alamat email yang valid.` : `Kolom ${name} harus berupa URL yang valid.`;
    if (state.tooShort) return `Kolom ${name} minimal harus berisi ${field.minLength} karakter.`;
    if (state.tooLong) return `Kolom ${name} maksimal hanya boleh berisi ${field.maxLength} karakter.`;
    if (state.rangeUnderflow) return `Kolom ${name} minimal ${field.min}.`;
    if (state.rangeOverflow) return `Kolom ${name} maksimal ${field.max}.`;
    if (state.badInput) return `Kolom ${name} harus berisi nilai yang valid.`;
    if (state.stepMismatch) return `Nilai pada kolom ${name} tidak sesuai kelipatan yang diperbolehkan.`;
    if (state.patternMismatch) return `Format ${name} tidak valid.`;
    return '';
}

export function installNativeValidation(root) {
    const translated = new WeakSet();
    root.addEventListener('invalid', (event) => {
        const field = event.target;
        if (!field.matches?.('input, select, textarea')) return;
        const message = nativeValidationMessage(field);
        if (message) {
            field.setCustomValidity(message);
            translated.add(field);
        }
    }, true);
    const clearField = (field) => {
        if (translated.has(field)) {
            field.setCustomValidity('');
            translated.delete(field);
        }
    };
    const clear = (event) => {
        const field = event.target;
        clearField(field);
        if (field.type === 'radio' && field.name) {
            // Selecting one radio satisfies its entire group. Invalid events may
            // have localized every option, including those that remain unchecked.
            const candidates = field.form?.elements || root.querySelectorAll?.('input[type="radio"]') || [];
            for (const option of candidates) {
                if (option.type === 'radio' && option.name === field.name && option.form === field.form) clearField(option);
            }
        }
    };
    root.addEventListener('input', clear);
    root.addEventListener('change', clear);
    root.addEventListener('reset', (event) => {
        for (const field of event.target.elements || []) clear({target: field});
    });
}

if (typeof document !== 'undefined') installNativeValidation(document);
