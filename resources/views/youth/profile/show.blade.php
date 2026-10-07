@extends('layouts.youth')
@section('title', 'Profil Saya · SIPORA')
@section('content')
<div class="space-y-6">

<x-workspace.page-header eyebrow="Profil" title="Profil Saya" description="Kelola identitas publik, minat, keahlian, dan riwayat yang membentuk Portfolio SIPORA.">
    <a class="btn-secondary" href="{{ route('youth.identity-verification') }}"><x-ui.icon name="shield-check" class="size-4" /> Verifikasi identitas</a>
    @if($onboarding['profileComplete'])<a class="btn-secondary" href="{{ route('youth.profile.privacy') }}"><x-ui.icon name="shield-check" class="size-4" /> Privasi</a>@endif
    <a class="btn" href="{{ route('youth.profile.edit') }}"><x-ui.icon name="edit" class="size-4" /> Edit profil</a>
</x-workspace.page-header>

@if(!$onboarding['profileComplete'])
<section class="sipora-card"><h2 class="text-lg font-extrabold">Lengkapi Profil terlebih dahulu</h2><p class="mt-2 text-sm text-text-secondary">Isi status dan bio singkat untuk membuka Keahlian, Minat, serta bagian Portfolio lainnya.</p><a class="btn mt-4" href="{{ route('youth.profile.edit') }}">Lengkapi Profil</a></section>
@endif

{{-- Hero card --}}
<section class="sipora-card">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
        <div class="grid size-28 shrink-0 place-items-center overflow-hidden rounded-full bg-[#eef0ff] text-3xl font-black text-[#243378]">
            @if($user->profile?->profile_photo_path)
                <img data-media-fallback class="size-full object-cover" src="{{ route('youth.profile.photo') }}" alt="Foto profil {{ $user->profile->full_name }}">
            @else
                {{ str($user->profile?->full_name ?? $user->name)->substr(0,1)->upper() }}
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-3xl font-extrabold">{{ $user->profile?->full_name ?? $user->name }}</h2>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->identity?->verification_status === 'verified' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $user->identity?->verification_status === 'verified' ? 'Youth terverifikasi' : 'Belum terverifikasi' }}
                </span>
            </div>
            <p class="mt-2 text-slate-600">
                {{ $user->profile?->occupation_title ?: 'Tambahkan pekerjaan atau institusi' }}
                @if($user->primaryDomicile) · {{ $user->primaryDomicile->administrativeArea->name }}@endif
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($user->interests as $interest)
                    <span class="interest-chip">{{ $interest->name }}</span>
                @endforeach
                @if($user->interests->isEmpty())
                    <a class="text-sm font-semibold text-[#3346a8]" href="{{ route('youth.interests.edit') }}">+ Tambah minat</a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Profile completion --}}
@if($onboarding['profileComplete'])<x-profile-completion :completion="$completion" />@endif

<div>

    {{-- Tentang saya --}}
    <section class="sipora-card">
        <h2 class="text-xl font-bold">Tentang saya</h2>
        <p class="mt-3 whitespace-pre-line text-slate-600">{{ $user->profile?->bio ?: 'Bio belum diisi.' }}</p>
        <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
            <div><dt class="font-semibold text-slate-500">Status</dt><dd class="mt-1">{{ $user->profile?->occupation_status ?: 'Belum diisi' }}</dd></div>
            <div><dt class="font-semibold text-slate-500">Kelayakan usia</dt><dd class="mt-1">{{ $eligibility['eligible'] === true ? 'Segmen inti Youth' : ($eligibility['eligible'] === false ? 'Di luar segmen inti' : 'Belum dihitung') }}</dd></div>
        </dl>
    </section>

</div>

@if($onboarding['profileComplete'])
<section class="sipora-card">
    <h2 class="text-xl font-extrabold">Lanjutkan Portfolio</h2>
    <p class="mt-2 text-sm text-text-secondary">Kelola setiap catatan dan bukti di halaman Portfolio masing-masing.</p>
    <div class="mt-4 flex flex-wrap gap-2">
        <a class="btn-secondary" href="{{ route('youth.portfolio.show') }}">Portfolio Saya</a>
        <a class="btn-secondary" href="{{ route('youth.skills.edit') }}">Keahlian</a>
        <a class="btn-secondary" href="{{ route('youth.interests.edit') }}">Minat</a>
        <a class="btn-secondary" href="{{ route('youth.portfolio.sections.show', 'education') }}">Pendidikan</a>
        <a class="btn-secondary" href="{{ route('youth.portfolio.sections.show', 'experience') }}">Pengalaman</a>
        <a class="btn-secondary" href="{{ route('youth.portfolio.sections.show', 'achievements') }}">Prestasi</a>
        <a class="btn-secondary" href="{{ route('youth.portfolio.sections.show', 'training') }}">Pelatihan &amp; Sertifikasi</a>
    </div>
</section>
@endif
</div>
@endsection
