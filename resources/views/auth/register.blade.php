@extends('layouts.auth')
@section('title', 'Daftar Youth · SIPORA')
@section('content')
    @php
        $googleOnboarding = $googleOnboarding ?? false;
        $registrationReference['initial'] = old() ? array_intersect_key(old(), array_flip(['name', 'email', 'birth_place', 'birth_date', 'gender', 'administrative_area_id', 'village_id', 'interests'])) : ($registrationInitial ?? []);
        if ($googleOnboarding)
            $registrationReference['initial']['email'] = auth()->user()->email;
        $registrationReference['stepUrl'] = url('/registration/steps');
        $useRecaptcha = !$googleOnboarding && config('recaptcha.enabled');
    @endphp
    <div class="registration-experience registration-consolidated" x-data="youthRegistration(@js($registrationReference))">
        <aside class="registration-guide">
            <a class="inline-flex min-h-11 items-center gap-2 text-sm text-white" href="{{ route('home') }}"><x-ui.icon
                    name="chevron-left" class="size-4" /> Kembali ke Beranda</a>
            <h1 class="mt-6 text-3xl font-extrabold">Mulai perjalananmu bersama SIPORA</h1>
            <p class="mt-4 text-sm leading-7">
                {{ $googleOnboarding ? 'Lengkapi pendaftaranmu, lalu ajukan bukti identitas untuk ditinjau Admin.' : 'Lengkapi pendaftaranmu, verifikasi email, lalu ajukan bukti identitas untuk ditinjau Admin.' }}
            </p>
            <ol class="registration-flow-steps" aria-label="Langkah pendaftaran Youth">
                <template x-for="(label,index) in steps" :key="label">
                    <li :aria-current="step === index ? 'step' : null"
                        :aria-disabled="index > step ? 'true' : null"
                        :class="{'is-current':step === index,'is-done':index < step && completed.includes(index)}">
                        <span x-text="index < step && completed.includes(index) ? '✓' : index + 1"></span>
                        <strong x-text="label"></strong>
                    </li>
                </template>
            </ol>
            <p class="mt-6 text-xs leading-6">NIK disimpan terenkripsi dan tidak ditampilkan secara publik. Status email dan
                identitas diperiksa secara terpisah.</p>
        </aside>
        <section class="registration-form-panel min-w-0" aria-labelledby="registration-title">
            @if($googleOnboarding)
                <div class="mb-5 rounded-lg border border-border bg-surface-soft p-4 text-sm"><strong>Selamat datang,
                        {{ auth()->user()->name }}</strong>
                    <p class="mt-2">Akun Google Anda sudah terhubung. Lengkapi data berikut untuk menyiapkan profil SIPORA.</p>
                </div>
            @endif
            <p class="registration-progress" x-text="'Langkah ' + (step + 1) + ' dari 4'"></p>
            <h2 id="registration-title" class="mt-4 text-2xl font-bold" x-text="steps[step]">Data Diri</h2>
            <p class="mb-6 mt-3 text-sm text-text-secondary">
                {{ $googleOnboarding ? 'Lengkapi profil Youth dengan data yang benar. Akun Google terhubung tidak berarti identitas SIPORA terverifikasi.' : 'Buat akun Youth dengan data yang benar. Verifikasi dokumen dilakukan setelah email terverifikasi.' }}
            </p>
            @if($errors->any())
                <div role="alert" class="mb-5 rounded-lg border border-border bg-surface-soft p-4 text-sm text-danger">
                    <p class="font-bold">Periksa kembali data pendaftaran.
                        {{ $googleOnboarding ? 'Isi ulang NIK demi keamanan.' : 'Isi ulang NIK dan kata sandi demi keamanan.' }}
                    </p>
                    <ul class="mt-2 list-disc pl-5">@foreach($errors->all() as $message)
                    <li>{{ $message }}</li>@endforeach
                    </ul>
            </div>@endif
            <form method="post"
                action="{{ $googleOnboarding ? route('youth.registration.update') : route('register.store') }}" novalidate
                data-confirm="false" @if($useRecaptcha)
                    x-data="recaptchaForm(@js(config('recaptcha.site_key')), @js(config('recaptcha.actions.register')))"
                @submit.prevent="if (validateAll($el)) submit()" @else x-data="{busy:false}"
                @submit="if (!validateAll($el)) $event.preventDefault()" @endif>
                @csrf
                @if($googleOnboarding) @method('PUT') @endif
                <div data-registration-step="0" x-show="step === 0" class="grid gap-5 sm:grid-cols-2">
                    <label class="block sm:col-span-2"><span class="field-label">Nama lengkap *</span><input class="field"
                            name="name" autocomplete="name" maxlength="160" required x-model="name"></label>
                    <label class="block sm:col-span-2"><span class="field-label">NIK *</span><input class="field"
                            name="national_id" aria-label="NIK" inputmode="numeric" autocomplete="off" pattern="[0-9]{16}"
                            minlength="16" maxlength="16" required x-model="nik" @input="nikChanged()"><small
                            class="mt-2 block text-text-muted">16 digit angka. Pemeriksaan format bukan verifikasi
                            Dukcapil.</small></label>
                    <label class="block sm:col-span-2"><span class="field-label">Tempat lahir *</span><input class="field"
                            name="birth_place" aria-label="Tempat lahir" placeholder="Contoh: Pemalang" maxlength="160"
                            required x-model="birthPlace"></label>
                    <label class="block"><span class="field-label">Tanggal lahir *</span><input class="field"
                            name="birth_date" aria-label="Tanggal lahir" type="date" required x-model="birthDate"
                            @input="dobEdited = true" min="{{ now()->subYears(31)->addDay()->toDateString() }}"
                            max="{{ now()->subYears(16)->toDateString() }}"><small class="mt-2 block text-text-secondary"
                            x-text="dobHelp"></small></label>
                    <label class="block"><span class="field-label">Jenis kelamin *</span><select class="field" name="gender"
                            aria-label="Jenis kelamin" required x-model="gender">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select><small class="mt-2 block text-text-muted">Pilih secara manual sesuai data diri
                            Anda.</small></label>
                    <label class="block sm:col-span-2"><span class="field-label">Email *</span><input class="field"
                            name="email" type="email" autocomplete="email" maxlength="255" required x-model="email"
                            @readonly($googleOnboarding)></label>
                    @unless($googleOnboarding)
                        <label class="block"><span class="field-label">Kata sandi *</span><x-password-input
                                aria-label="Kata sandi" name="password" autocomplete="new-password" minlength="8" maxlength="72"
                                required /></label>
                        <label class="block"><span class="field-label">Konfirmasi kata sandi *</span><x-password-input
                                aria-label="Konfirmasi kata sandi" name="password_confirmation" autocomplete="new-password"
                                minlength="8" maxlength="72" required /></label>
                        @if(config('services.google.client_id') && config('services.google.client_secret'))
                            <div class="sm:col-span-2"><a class="btn-outline flex w-full items-center justify-center gap-3"
                                    href="{{ route('google.redirect') }}"><x-google-icon /> Daftar dengan Google</a>
                                <p class="mt-2 text-xs text-text-secondary">Pengguna Google melengkapi NIK dan Biodata setelah
                                    masuk. Verifikasi identitas tetap diperlukan.</p>
                        </div>@endif
                    @endunless
                </div>
                <div data-registration-step="1" x-show="step === 1" x-cloak class="grid gap-5 sm:grid-cols-2">
                    <label class="block sm:col-span-2"><span class="field-label">Kabupaten</span><input class="field"
                            value="Kabupaten Pemalang" readonly></label>
                    <label class="block"><span class="field-label">Kecamatan *</span><select class="field"
                            name="administrative_area_id" x-model="district" @change="districtChanged()" required>
                            <option value="">Pilih kecamatan</option><template x-for="item in reference.districts"
                                :key="item.id">
                                <option :value="item.id" x-text="item.name"></option>
                            </template>
                        </select></label>
                    <label class="block"><span class="field-label">Desa/Kelurahan *</span><select class="field"
                            name="village_id" x-model="village" :disabled="!district" required>
                            <option value="">Pilih desa/kelurahan</option><template x-for="item in villages" :key="item.id">
                                <option :value="item.id" x-text="item.name"></option>
                            </template>
                        </select></label>
                    <label class="block sm:col-span-2"><span class="field-label">Alamat detail (opsional,
                            privat)</span><textarea class="field" name="address_line"
                            maxlength="500">{{ old('address_line', $savedAddress->address_line ?? '') }}</textarea></label>
                    <label class="block"><span class="field-label">RT</span><input class="field" name="rt"
                            inputmode="numeric" pattern="[0-9]{1,4}" maxlength="4"
                            value="{{ old('rt', $savedAddress->rt ?? '') }}"></label>
                    <label class="block"><span class="field-label">RW</span><input class="field" name="rw"
                            inputmode="numeric" pattern="[0-9]{1,4}" maxlength="4"
                            value="{{ old('rw', $savedAddress->rw ?? '') }}"></label>
                    <label class="block"><span class="field-label">Kode pos</span><input class="field" name="postal_code"
                            inputmode="numeric" pattern="[0-9]{5}" maxlength="5"
                            value="{{ old('postal_code', $savedAddress->postal_code ?? '') }}"></label>
                </div>
                <div data-registration-step="2" x-show="step === 2" x-cloak class="space-y-5">
                    <p class="text-sm">Pilih bidang yang paling kamu minati (maksimal 5).</p>
                    <p class="font-bold text-interactive" aria-live="polite" x-text="selected.length + ' dari 5 dipilih'">
                    </p>
                    <div class="registration-interest-grid"><template x-for="item in reference.interests"
                            :key="item.id"><button type="button" class="registration-interest"
                                :aria-pressed="selected.includes(item.id)" @click="toggleInterest(item.id)"
                                x-text="item.name"></button></template></div>
                    <template x-for="id in selected" :key="id"><input type="hidden" name="interests[]"
                            :value="id"></template>
                </div>
                <div data-registration-step="3" x-show="step === 3" x-cloak class="space-y-5">
                    <p class="text-sm text-text-secondary">Periksa ringkasan sebelum mengirim pendaftaran. Anda masih dapat kembali
                        untuk memperbaiki data.</p>
                    <dl class="registration-summary"><template
                            x-for="[key,label] in Object.entries({name:'Nama',nik:'NIK',birthPlace:'Tempat lahir',birthDate:'Tanggal lahir',gender:'Jenis kelamin',email:'Email',district:'Kecamatan',village:'Desa/Kelurahan',interests:'Bidang minat'})"
                            :key="key">
                            <div>
                                <dt x-text="label"></dt>
                                <dd x-text="summary[key]"></dd>
                            </div>
                        </template></dl>
                    <p class="rounded-lg border border-border bg-surface-soft p-4 text-sm">
                        {{ $googleOnboarding ? 'Email akun Google sudah terverifikasi. Identitas SIPORA belum terverifikasi sampai bukti dokumen ditinjau Admin.' : 'Setelah akun dibuat, verifikasi email Anda. Identitas belum terverifikasi sampai bukti dokumen ditinjau Admin.' }}
                    </p>
                </div>
                <p x-cloak x-show="stepError" x-text="stepError" role="alert" class="mt-5 text-sm text-danger"></p>
                @if($useRecaptcha)<input type="hidden" name="recaptcha_token" x-ref="token">
                <p x-cloak x-show="error" x-text="error" role="alert" class="mt-4 text-sm text-danger"></p>@endif
                <div class="mt-7 flex flex-wrap items-center justify-between gap-3 border-t border-border pt-5">
                    <button type="button" class="btn-outline" x-show="step > 0" x-cloak @click="previous()"
                        :disabled="busy || validating">Sebelumnya</button>
                    <button type="button" class="btn-primary ml-auto" x-show="step < 3"
                        :disabled="busy || validating" @click="next($el.closest('form'))">Selanjutnya <x-ui.icon name="chevron-right"
                            class="size-4" /></button>
                    <button type="submit" class="btn-primary ml-auto" x-show="step === 3" x-cloak :disabled="busy || validating"
                        x-text="busy ? 'Memverifikasi…' : 'Kirim pendaftaran'">Kirim pendaftaran</button>
                </div>
                @if($useRecaptcha)
                    <p class="mt-5 text-xs text-text-muted">Dilindungi reCAPTCHA. Google <a
                            href="https://policies.google.com/privacy" class="underline">Kebijakan Privasi</a> dan <a
                            href="https://policies.google.com/terms" class="underline">Persyaratan Layanan</a> berlaku.</p>
                @endif
            </form>
            @if($googleOnboarding)
                <form method="post" action="{{ route('logout') }}" class="mt-6">@csrf<button class="text-sm underline"
                        type="submit">Keluar</button></form>
            @else
                <p class="mt-6 text-sm text-text-secondary">Sudah memiliki akun? <a class="font-bold text-interactive underline"
                        href="{{ route('login') }}">Masuk</a></p>
            @endif
        </section>
    </div>
    @if($useRecaptcha && config('recaptcha.site_key'))
        @push('head')
            <script src="https://www.google.com/recaptcha/api.js?render={{ urlencode(config('recaptcha.site_key')) }}"
                defer></script>
        @endpush
    @endif
@endsection
