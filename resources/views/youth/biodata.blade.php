@extends('layouts.youth')
@section('title', 'Biodata · SIPORA')
@section('content')
@php
    $initialStep = 0;
    foreach (['administrative_area_id' => 1, 'village_id' => 1, 'interests' => 3, 'address_line' => 1, 'rt' => 1, 'rw' => 1, 'postal_code' => 1, 'phone' => 2, 'instagram' => 2, 'facebook' => 2, 'linkedin' => 2] as $field => $number) {
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
                <div data-step="0" x-show="step === 0" x-cloak class="grid gap-5 sm:grid-cols-2" x-data="nikAssistance(@js(array_merge($registrationReference, ['initial'=>['birth_date'=>old('birth_date', $user->profile?->birth_date?->toDateString())]])))">
                    @if(!$user->identity?->nationalId())<label class="block sm:col-span-2"><span class="field-label">NIK *</span><input class="field" name="national_id" x-model="nik" @input="nikChanged()" inputmode="numeric" pattern="[0-9]{16}" minlength="16" maxlength="16" autocomplete="off" required><small>Isi 16 digit angka. NIK tidak ditampilkan secara publik.</small>@error('national_id')<span class="field-error">{{ $message }}</span>@enderror</label>@else<p class="sm:col-span-2">NIK tersimpan: <strong>{{ $user->identity->maskedNik() }}</strong></p>@endif

                    <label class="block"><span class="field-label">Nama lengkap <span aria-hidden="true">*</span></span><input class="field" name="full_name" value="{{ old('full_name', $user->profile?->full_name ?? $user->name) }}" maxlength="160" required>@error('full_name')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Tanggal lahir <span aria-hidden="true">*</span></span><input class="field" type="date" name="birth_date" x-model="birthDate" @input="dobEdited = true" value="{{ old('birth_date', $user->profile?->birth_date?->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required><small class="mt-2 block text-text-secondary" x-text="dobHelp"></small>@error('birth_date')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Tempat lahir</span><input class="field" name="birth_place" value="{{ old('birth_place', $user->profile?->birth_place) }}" maxlength="120">@error('birth_place')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Jenis kelamin</span><select class="field" name="gender" @required(!$user->identity?->nationalId())><option value="">Pilih</option>@foreach(['male'=>'Laki-laki','female'=>'Perempuan'] as $value=>$label)<option value="{{ $value }}" @selected(old('gender', $user->profile?->gender)===$value)>{{ $label }}</option>@endforeach</select>@error('gender')<span class="field-error">{{ $message }}</span>@enderror</label>
                </div>
                <div data-step="1" x-show="step === 1" x-cloak class="grid gap-5 sm:grid-cols-2" x-data="dependentAddress(@js($registrationReference['villages']), @js(['district'=>old('administrative_area_id', $user->primaryDomicile?->administrativeArea?->uuid()), 'village'=>old('village_id', $user->primaryDomicile?->village?->uuid())]))">
                    <label class="block sm:col-span-2"><span class="field-label">Kabupaten</span><input class="field" value="Kabupaten Pemalang" readonly></label>
                    <label class="block"><span class="field-label">Kecamatan domisili <span aria-hidden="true">*</span></span><select class="field" name="administrative_area_id" x-model="district" @change="changeDistrict()" required><option value="">Pilih kecamatan</option>@foreach($areas as $area)<option value="{{ $area->uuid() }}" @selected(old('administrative_area_id', $user->primaryDomicile?->administrativeArea?->uuid()) === $area->uuid())>{{ $area->name }}</option>@endforeach</select>@error('administrative_area_id')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Desa/Kelurahan</span><select class="field" name="village_id" @required(!$user->identity?->nationalId()) x-model="village" :disabled="!district"><option value="">Pilih desa/kelurahan</option><template x-for="v in available" :key="v.id"><option :value="v.id" x-text="v.name"></option></template></select>@error('village_id')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="sm:col-span-2"><span class="field-label">Alamat detail <span class="font-normal">(opsional, privat)</span></span><textarea class="field" name="address_line" maxlength="500">{{ old('address_line', $user->primaryDomicile?->address_line) }}</textarea>@error('address_line')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">RT</span><input class="field" name="rt" value="{{ old('rt', $user->primaryDomicile?->rt) }}" inputmode="numeric" maxlength="4">@error('rt')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">RW</span><input class="field" name="rw" value="{{ old('rw', $user->primaryDomicile?->rw) }}" inputmode="numeric" maxlength="4">@error('rw')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Kode pos</span><input class="field" name="postal_code" value="{{ old('postal_code', $user->primaryDomicile?->postal_code) }}" inputmode="numeric" maxlength="10">@error('postal_code')<span class="field-error">{{ $message }}</span>@enderror</label>
                </div>
                <div data-step="2" x-show="step === 2" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label class="block"><span class="field-label">WhatsApp / telepon <span aria-hidden="true">*</span></span><input class="field" type="tel" name="phone" value="{{ old('phone', $user->profile?->phone) }}" maxlength="32" autocomplete="tel" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Instagram (opsional)</span><input class="field" name="instagram" value="{{ old('instagram', $user->contactLinks?->instagram) }}" maxlength="30" placeholder="nama_pengguna">@error('instagram')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">Facebook (opsional)</span><input class="field" name="facebook" value="{{ old('facebook', $user->contactLinks?->facebook) }}" maxlength="255" placeholder="nama pengguna atau URL profil">@error('facebook')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <label class="block"><span class="field-label">LinkedIn (opsional)</span><input class="field" name="linkedin" value="{{ old('linkedin', $user->contactLinks?->linkedin) }}" maxlength="255" placeholder="nama pengguna atau URL /in/">@error('linkedin')<span class="field-error">{{ $message }}</span>@enderror</label>
                    <p class="text-sm text-text-secondary sm:col-span-2">Semua kontak ini privat. Email akun dikelola terpisah di Informasi Akun dan tidak tampil pada Portfolio publik.</p>
                </div>
                <div data-step="3" x-show="step === 3" x-cloak class="space-y-5 rounded-lg border border-border bg-surface-soft p-5" x-data="interestSelection(@js($registrationReference['interests']), @js(old('interests', $user->interests->map->uuid()->all())))" :data-profile-selected="selected.length" @profiling-invalid="stepError = selected.length ? 'Pilih maksimal 5 bidang minat.' : 'Pilih minimal satu bidang minat.'">
                    <h3 class="font-extrabold">Profiling</h3><p class="text-sm text-text-secondary">Pilih bidang yang paling kamu minati (maksimal 5). Minat ini langsung dipakai oleh Development Pathway.</p>
                    <p class="text-sm font-bold" aria-live="polite" x-text="selected.length + ' dari 5 dipilih'"></p>
                    <div class="registration-interest-grid"><template x-for="item in @js($registrationReference['interests'])" :key="item.id"><button class="registration-interest" type="button" :aria-pressed="selected.includes(item.id)" @click="toggleInterest(item.id)" x-text="item.name"></button></template></div>
                    <template x-for="id in selected" :key="id"><input type="hidden" name="interests[]" :value="id"></template>
                    <p x-text="stepError" role="alert" class="text-sm text-danger"></p>@error('interests')<p class="field-error">{{ $message }}</p>@enderror
                    <p class="text-xs text-text-secondary">Setelah menyimpan Biodata, lanjutkan pengajuan bukti identitas privat untuk ditinjau Admin.</p>
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
