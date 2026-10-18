import {nativeValidationMessage} from './validation.js';

export function nikBirthDate(nik, today, minAge = 16, maxAge = 30) {
    if (!/^[0-9]{16}$/.test(nik)) return null;
    let day = Number(nik.slice(6, 8));
    if (day > 40) day -= 40;
    const month = Number(nik.slice(8, 10)), yy = Number(nik.slice(10, 12));
    const [ty, tm, td] = today.split('-').map(Number);
    for (const century of [1900, 2000, 2100]) {
        const year = century + yy;
        const date = new Date(Date.UTC(year, month - 1, day));
        if (date.getUTCFullYear() !== year || date.getUTCMonth() !== month - 1 || date.getUTCDate() !== day) continue;
        const age = ty - year - (tm < month || (tm === month && td < day) ? 1 : 0);
        if (age >= minAge && age <= maxAge) return `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
    }
    return null;
}
export function maskNik(nik) { return nik ? '*'.repeat(Math.max(0, nik.length - 4)) + nik.slice(-4) : 'Belum diisi'; }
export default function registration(reference) {
    const initial = reference.initial || {};
    return {
        step: 0, completed: [], steps: ['Data Diri', 'Alamat', 'Profiling', 'Konfirmasi'],
        name: initial.name || '', email: initial.email || '', nik: '', birthPlace: initial.birth_place || '', birthDate: initial.birth_date || '', gender: initial.gender || '',
        dobEdited: !!initial.birth_date, dobPrefilled: false, district: initial.administrative_area_id || '', village: initial.village_id || '',
        selected: Array.isArray(initial.interests) ? initial.interests.filter(id => reference.interests.some(i => i.id === id)).slice(0,5) : [],
        stepError: '', validating: false, reference,
        get villages() { return reference.villages.filter(v => v.district_id === this.district); },
        districtChanged() { if (!this.villages.some(v => v.id === this.village)) this.village = ''; },
        get parsedDob() { return nikBirthDate(this.nik, reference.today, reference.minAge, reference.maxAge); },
        nikChanged() {
            if (this.parsedDob && !this.dobEdited) { this.birthDate = this.parsedDob; this.dobPrefilled = true; }
            else if (!this.parsedDob && !this.dobEdited && this.dobPrefilled) { this.birthDate = ''; this.dobPrefilled = false; }
        },
        get dobHelp() {
            if (this.parsedDob && this.birthDate && this.birthDate !== this.parsedDob) return 'Tanggal lahir berbeda dari hasil pembacaan NIK. Pastikan data yang Anda masukkan sudah benar.';
            if (this.dobPrefilled && this.parsedDob === this.birthDate) return 'Tanggal lahir terisi otomatis berdasarkan NIK. Silakan koreksi jika diperlukan.';
            if (this.nik.length === 16 && !this.parsedDob) return 'Tanggal pada NIK belum dapat dibaca dalam rentang usia pemuda. Isi tanggal lahir secara manual.';
            return 'Pembacaan NIK hanya membantu pengisian tanggal lahir; bukan verifikasi Dukcapil.';
        },
        toggleInterest(id) {
            this.stepError = '';
            if (this.selected.includes(id)) this.selected = this.selected.filter(x => x !== id);
            else if (this.selected.length < 5) this.selected.push(id);
            else this.stepError = 'Pilih maksimal 5 bidang minat.';
        },
        focusField(field) {
            this.$nextTick(() => {
                field?.scrollIntoView?.({behavior:'smooth', block:'center'});
                field?.focus?.({preventScroll:true});
                field?.reportValidity?.();
            });
        },
        validStep(form, index) {
            const panel = form.querySelector(`[data-registration-step="${index}"]`);
            const fields = panel?.querySelectorAll('input,select,textarea') || [];
            for (const field of fields) {
                if (!field.checkValidity()) {
                    this.step = index;
                    this.stepError = nativeValidationMessage(field) || field.validationMessage || 'Periksa kembali kolom yang wajib diisi.';
                    this.focusField(field);
                    return false;
                }
            }
            if (index === 2 && (!this.selected.length || this.selected.length > 5)) {
                this.step = 2;
                this.stepError = this.selected.length ? 'Pilih maksimal 5 bidang minat.' : 'Pilih minimal satu bidang minat.';
                this.focusField(panel?.querySelector('.registration-interest'));
                return false;
            }
            return true;
        },
        async next(form) {
            if (this.validating || this.step >= 3) return;
            this.stepError = '';
            const index = this.step;
            for (let previous = 0; previous <= index; previous++) {
                if (previous < index && !this.completed.includes(previous)) { this.step = previous; return; }
                if (!this.validStep(form, previous)) return;
            }
            this.validating = true;
            try {
                const data = new FormData(form);
                data.delete('_method');
                const response = await fetch(`${reference.stepUrl}/${index}`, {
                    method:'POST', body:data, credentials:'same-origin', headers:{Accept:'application/json'},
                });
                const result = await response.json();
                if (!response.ok) {
                    const errors = result.errors || {};
                    this.stepError = Object.values(errors).flat()[0] || 'Validasi belum berhasil. Silakan coba lagi.';
                    if (errors.wizard) { this.step = 0; this.completed = []; }
                    const key = Object.keys(errors)[0]?.split('.')[0];
                    this.focusField(key === 'interests' ? form.querySelector('.registration-interest') : form.elements.namedItem(key));
                    return;
                }
                if (result.next_step !== index + 1) throw new Error('Invalid wizard response');
                this.completed = [...this.completed.filter(item => item < index), index];
                this.step = result.next_step;
            } catch {
                this.stepError = 'Validasi belum berhasil. Periksa koneksi Anda lalu coba lagi.';
            } finally { this.validating = false; }
        },
        previous() {
            if (!this.validating && this.step > 0 && this.completed.includes(this.step - 1)) this.step--;
            this.stepError = '';
        },
        validateAll(form) {
            if (this.validating) return false;
            if (this.step < 3) { this.stepError = 'Gunakan tombol Selanjutnya untuk melanjutkan langkah ini.'; return false; }
            for (let index = 0; index < 3; index++) {
                if (!this.completed.includes(index)) { this.step = index; this.stepError = 'Selesaikan langkah ini melalui tombol Selanjutnya.'; return false; }
                if (!this.validStep(form, index)) return false;
            }
            return true;
        },
        get summary() {
            return {name: this.name, email: this.email, nik: maskNik(this.nik), birthPlace: this.birthPlace, birthDate: this.birthDate,
                gender: {male:'Laki-laki',female:'Perempuan'}[this.gender] || '',
                district: reference.districts.find(d => d.id === this.district)?.name || '',
                village: this.villages.find(v => v.id === this.village)?.name || '',
                interests: reference.interests.filter(i => this.selected.includes(i.id)).map(i => i.name).join(', ')};
        },
    };
}

export function dependentAddress(villages, initial = {}) {
    return {district: initial.district || '', village: initial.village || '',
        get available() { return villages.filter(v => v.district_id === this.district); },
        changeDistrict() { if (!this.available.some(v => v.id === this.village)) this.village = ''; },
    };
}

export function nikAssistance(reference) {
    const source = registration(reference), result = {};
    for (const key of ['nik','birthDate','dobEdited','dobPrefilled','parsedDob','nikChanged','dobHelp']) Object.defineProperty(result, key, Object.getOwnPropertyDescriptor(source,key));
    return result;
}
export function interestSelection(items, initial = []) {
    const source = registration({today:'',districts:[],villages:[],interests:items,initial:{interests:initial}}), result = {};
    for (const key of ['selected','stepError','toggleInterest']) Object.defineProperty(result,key,Object.getOwnPropertyDescriptor(source,key));
    result.selected = [...new Set(initial.filter(id => items.some(item => item.id === id)))];
    if (result.selected.length > 5) result.stepError = 'Minat lama tetap tersimpan. Pilih maksimal 5 sebelum menyimpan perubahan.';
    return result;
}
