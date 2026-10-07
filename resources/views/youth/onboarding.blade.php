@extends('layouts.youth')
@section('title', 'Alur Aktivasi Akun · SIPORA')
@section('content')
@php
    $steps = [
        ['key' => 'email', 'title' => 'Verifikasi Akun / Email', 'description' => 'Pastikan alamat email akunmu sudah terverifikasi.'],
        ['key' => 'biodata', 'title' => 'Lengkapi Biodata', 'description' => 'Isi data pribadi, domisili, dan kontak untuk keperluan administrasi.'],
        ['key' => 'identity', 'title' => 'Verifikasi Identitas', 'description' => 'Kirim kartu identitas privat. Admin SIPORA akan meninjau pengajuanmu.'],
        ['key' => 'profile', 'title' => 'Lengkapi Profil', 'description' => 'Tulis perkenalan dan statusmu setelah identitas disetujui.'],
        ['key' => 'portfolio', 'title' => 'Lengkapi Portfolio Dasar', 'description' => 'Pilih minimal satu Keahlian dan satu Minat. Bukti pengalaman bersifat opsional.'],
        ['key' => 'complete', 'title' => 'Mulai Jelajahi & Berpartisipasi', 'description' => 'Akunmu siap untuk Activity, Community, dan peluang lainnya.'],
    ];
@endphp
<div class="mx-auto max-w-4xl space-y-6">
    <x-workspace.page-header eyebrow="Perjalanan Youth" title="Alur Aktivasi Akun" description="Ikuti langkah yang terbuka. Progres dihitung dari data akunmu yang terbaru." />
    <div class="sipora-card flex items-center justify-between gap-4" role="status">
        <div><p class="text-xs font-extrabold uppercase tracking-widest text-interactive">Kesiapan Profil</p><p class="mt-1 text-xl font-extrabold">{{ count($onboarding['completedSteps']) }} dari 6 tahap selesai</p></div>
        <x-ui.icon name="badge-check" class="size-8 text-interactive" />
    </div>
    <ol class="onboarding-timeline" aria-label="Tahap aktivasi akun">
        @foreach($steps as $index => $step)
            @php
                $done = in_array($step['key'], $onboarding['completedSteps'], true);
                $current = $step['key'] === $onboarding['currentStep'];
                $locked = in_array($step['key'], $onboarding['lockedSteps'], true);
            @endphp
            <li class="onboarding-timeline-item" @if($current) aria-current="step" @endif>
                <span class="onboarding-timeline-marker {{ $done ? 'is-done' : ($current ? 'is-current' : '') }}" aria-hidden="true">
                    @if($done)<x-ui.icon name="check" class="size-4" />@else{{ $index + 1 }}@endif
                </span>
                <section class="sipora-card {{ $current ? 'onboarding-current' : 'onboarding-quiet' }}">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="text-base font-extrabold">{{ $step['title'] }}</h2>
                        <span class="text-xs font-bold uppercase tracking-wide {{ $done ? 'text-success' : ($current ? 'text-interactive' : 'text-text-muted') }}">{{ $done ? 'Selesai' : ($current ? 'Langkah aktif' : 'Terkunci') }}</span>
                    </div>
                    @if($current)
                        <p class="mt-3 text-sm text-text-secondary">{{ $step['description'] }}</p>
                        @if($step['key'] === 'identity' && $onboarding['identityStatus'] === 'pending')
                            <p class="mt-2 text-sm text-text-secondary">Pengajuan sedang ditinjau Admin. Profil akan terbuka setelah disetujui.</p>
                        @elseif($step['key'] === 'identity' && $onboarding['identityStatus'] === 'revision')
                            <p class="mt-2 text-sm text-text-secondary">Admin meminta revisi. Buka catatan peninjauan, lalu kirim ulang dokumen.</p>
                        @elseif($step['key'] === 'identity' && $onboarding['identityStatus'] === 'rejected')
                            <p class="mt-2 text-sm text-text-secondary">Pengajuan ditolak. Akun tetap aktif; periksa alasannya sebelum mengajukan kembali.</p>
                        @endif
                        <a class="btn mt-4" href="{{ route($onboarding['nextAction']['route']) }}">{{ $onboarding['nextAction']['label'] }}</a>
                    @elseif($locked)
                        <p class="mt-1 text-xs text-text-muted">Selesaikan langkah sebelumnya untuk membuka tahap ini.</p>
                    @endif
                </section>
            </li>
        @endforeach
    </ol>
    <a class="btn-secondary" href="{{ route('home') }}">Jelajahi halaman publik</a>
</div>
@endsection
