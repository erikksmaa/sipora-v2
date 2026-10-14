@extends('layouts.auth')
@section('title', 'Daftar Akun · SIPORA')
@section('content')
<div class="registration-experience">
    <aside class="registration-guide">
        <a class="inline-flex min-h-11 items-center gap-2 text-sm text-white" href="{{ route('home') }}"><x-ui.icon name="chevron-left" class="size-4" /> Kembali ke Beranda</a>
        <h1 class="mt-6 text-3xl font-extrabold">Mulai perjalananmu bersama SIPORA</h1>
        <p class="mt-4 leading-7 text-white/80">Buat akun, lengkapi persiapanmu, lalu temukan ruang bertumbuh di ekosistem pemuda Pemalang.</p>
        <ol class="registration-steps">
            @foreach([['Daftar Akun', 'Nama, email dan password'], ['Verifikasi Email', 'Konfirmasi akses email'], ['Profil & Identitas', 'Biodata, dokumen dan peninjauan'], ['Minat, Skill & Portfolio', 'Arah pengembangan dan bukti pengalaman'], ['Jelajahi Ekosistem', 'Community, Activity dan Opportunity']] as $index => [$title, $copy])
                <li @if($index === 0) aria-current="step" @endif><span>{{ $index + 1 }}</span><div><strong>{{ $title }}</strong><p>{{ $copy }}</p></div></li>
            @endforeach
        </ol>
        <img class="registration-photo" src="{{ asset('media/demo/08A_SOCIAL_FEED/feed-002-workshop-recap.jpg') }}" alt="Foto demo pemuda berkolaborasi dalam workshop" width="1200" height="800" loading="lazy">
    </aside>
    <section class="registration-form-panel" aria-labelledby="registration-title">
        <p class="registration-progress">Langkah 1 dari 5 · Daftar Akun</p>
        <h2 id="registration-title" class="mt-4 text-3xl font-bold">Buat akun SIPORA</h2>
        <p class="mb-6 mt-3 text-sm leading-6 text-text-secondary">Cukup isi data akun terlebih dahulu. Biodata dan dokumen identitas dilengkapi setelah email terverifikasi.</p>
        @if(config('services.google.client_id') && config('services.google.client_secret'))
            <a class="btn-outline mb-3 flex w-full justify-center" href="{{ route('google.redirect') }}">Daftar dengan Google</a>
            <p class="mb-6 text-xs leading-5 text-text-secondary">Google memudahkan akses akun; verifikasi identitas SIPORA tetap dilakukan melalui peninjauan dokumen.</p>
            <p class="mb-6 text-center text-sm text-text-muted">Atau gunakan email</p>
        @endif
        <x-auth-form :action="route('register.store')" recaptcha="register">
            <x-auth-input name="name" label="Nama" autocomplete="name" />
            <x-auth-input name="email" label="Email" type="email" autocomplete="email" />
            <x-auth-input name="password" label="Password (minimal 8 karakter)" type="password" autocomplete="new-password" />
            <x-auth-input name="password_confirmation" label="Konfirmasi password" type="password" autocomplete="new-password" />
            <p class="text-xs leading-6 text-text-secondary">Setelah akun dibuat, Anda akan diarahkan untuk verifikasi email. Pendaftaran akun tidak otomatis memverifikasi identitas.</p>
            <x-slot:button>Buat Akun & Lanjutkan</x-slot:button>
        </x-auth-form>
        <p class="mt-6 text-sm text-text-secondary">Sudah memiliki akun? <a class="font-bold text-interactive underline underline-offset-4" href="{{ route('login') }}">Masuk</a></p>
    </section>
</div>
@endsection
