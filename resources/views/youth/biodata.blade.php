@extends('layouts.youth')
@section('title', 'Biodata · SIPORA')
@section('content')
@php
    $initialStep = 0;
    foreach (['administrative_area_id' => 1, 'address_line' => 1, 'rt' => 1, 'rw' => 1, 'postal_code' => 1, 'phone' => 2, 'instagram' => 2, 'facebook' => 2, 'linkedin' => 2] as $field => $number) {
        if ($errors->has($field)) $initialStep = max($initialStep, $number);
    }
@endphp
<div class="mx-auto max-w-5xl space-y-5" x-data="biodataStepper({{ $initialStep }})">
    <x-workspace.page-header eyebrow="Persiapan Akun" title="Lengkapi Biodata" description="Data administratif ini privat dan dipakai untuk menyiapkan verifikasi identitas." />
    <section class="sipora-card">
        <ol class="biodata-steps" aria-label="Langkah pengisian Biodata">
            <template x-for="(label, index) in steps" :key="label">
                <li :class="step === index ? 'is-current' : (step > index ? 'is-done' : '')"><span class="biodata-step-number" x-text="step > index ? '✓' : index + 1"></span><span x-text="label"></span></li>
            </template>
        </ol>
        <form method="post" action="{{ route('youth.biodata.update') }}" class="mt-6" novalidate>
            @csrf @method('PUT')
            <div x-ref="panels">
                <div data-step="0" x-show="step === 0" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">Nama lengkap <span aria-hidden="true">*</span></span><input class="field" name="full_name" value="{{ old('full_name', $user->profile?->full_name ?? $user->name) }}" maxlength="160" required>@error('full_name')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Tanggal lahir <span aria-hidden="true">*</span></span><input class="field" type="date" name="birth_date" value="{{ old('birth_date', $user->profile?->birth_date?->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required>@error('birth_date')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Tempat lahir</span><input class="field" name="birth_place" value="{{ old('birth_place', $user->profile?->birth_place) }}" maxlength="120">@error('birth_place')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Gender</span><select class="field" name="gender"><option value="">Pilih</option>@foreach(['male'=>'Laki-laki','female'=>'Perempuan','other'=>'Lainnya','prefer_not_to_say'=>'Tidak ingin menyebutkan'] as $value=>$label)<option value="{{ $value }}" @selected(old('gender', $user->profile?->gender)===$value)>{{ $label }}</option>@endforeach</select>@error('gender')<span class="field-error">{{ $message }}</span>@enderror</label>
                </div>
                <div data-step="1" x-show="step === 1" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">Kecamatan domisili <span aria-hidden="true">*</span></span><select class="field" name="administrative_area_id" required><option value="">Pilih kecamatan</option>@foreach($areas as $area)<option value="{{ $area->uuid() }}" @selected(old('administrative_area_id', $user->primaryDomicile?->administrativeArea?->uuid()) === $area->uuid())>{{ $area->name }}</option>@endforeach</select>@error('administrative_area_id')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="sm:col-span-2"><span class="field-label">Alamat detail <span class="font-normal">(opsional, privat)</span></span><textarea class="field" name="address_line" maxlength="500">{{ old('address_line', $user->primaryDomicile?->address_line) }}</textarea>@error('address_line')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">RT</span><input class="field" name="rt" value="{{ old('rt', $user->primaryDomicile?->rt) }}" inputmode="numeric" maxlength="4">@error('rt')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">RW</span><input class="field" name="rw" value="{{ old('rw', $user->primaryDomicile?->rw) }}" inputmode="numeric" maxlength="4">@error('rw')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Kode pos</span><input class="field" name="postal_code" value="{{ old('postal_code', $user->primaryDomicile?->postal_code) }}" inputmode="numeric" maxlength="10">@error('postal_code')<span class="field-error">{{ $message }}</span>@enderror</label>
                </div>
                <div data-step="2" x-show="step === 2" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label><span class="field-label">WhatsApp / telepon <span aria-hidden="true">*</span></span><input class="field" type="tel" name="phone" value="{{ old('phone', $user->profile?->phone) }}" maxlength="32" autocomplete="tel" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Instagram (opsional)</span><input class="field" name="instagram" value="{{ old('instagram', $user->contactLinks?->instagram) }}" maxlength="30" placeholder="nama_pengguna">@error('instagram')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">Facebook (opsional)</span><input class="field" name="facebook" value="{{ old('facebook', $user->contactLinks?->facebook) }}" maxlength="255" placeholder="nama pengguna atau URL profil">@error('facebook')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label><span class="field-label">LinkedIn (opsional)</span><input class="field" name="linkedin" value="{{ old('linkedin', $user->contactLinks?->linkedin) }}" maxlength="255" placeholder="nama pengguna atau URL /in/">@error('linkedin')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <p class="text-sm text-text-secondary sm:col-span-2">Semua kontak ini privat. Email akun dikelola terpisah di Informasi Akun dan tidak tampil pada Portfolio publik.</p>
                </div>
                <div data-step="3" x-show="step === 3" x-cloak class="rounded-lg border border-border bg-surface-soft p-5">
                    <h3 class="font-extrabold">Verifikasi Identitas</h3><p class="mt-2 text-sm text-text-secondary">Setelah menyimpan Biodata, lanjutkan dengan mengirim KTP, KIA, atau Kartu Pelajar pada langkah verifikasi identitas. Dokumen disimpan privat dan ditinjau Admin Dindikpora.</p>
                </div>
                <div data-step="4" x-show="step === 4" x-cloak class="rounded-lg border border-border bg-surface-soft p-5">
                    <h3 class="font-extrabold">Konfirmasi Biodata</h3><p class="mt-2 text-sm text-text-secondary">Periksa kembali isian pada setiap langkah. Menyimpan Biodata akan membuka pengiriman dokumen identitas; belum berarti identitasmu terverifikasi.</p>
                </div>
            </div>
            <div class="mt-7 flex flex-wrap gap-3 border-t border-border pt-5">
                <button x-show="step > 0" type="button" class="btn-secondary" @click="previous">Sebelumnya</button>
                <button x-show="step < steps.length - 1" type="button" class="btn" @click="next">Selanjutnya</button>
                <button x-show="step === steps.length - 1" x-cloak type="submit" class="btn">Simpan &amp; lanjut ke Identitas</button>
            </div>
        </form>
    </section>
</div>
@endsection
