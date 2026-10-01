@extends('layouts.youth')
@section('title', 'Privasi Portfolio · SIPORA')
@section('content')
@php
    $settings = [
        'is_profile_public' => ['Publikasikan Portfolio', 'Izinkan orang membuka halaman Portfolio publik Anda.'],
        'show_photo' => ['Foto profil', 'Tampilkan foto pada Portfolio publik.'],
        'show_bio' => ['Bio', 'Tampilkan ringkasan diri dan pekerjaan.'],
        'show_interests' => ['Minat', 'Tampilkan topik yang Anda minati.'],
        'show_skills' => ['Keahlian', 'Tampilkan keahlian yang Anda laporkan sendiri.'],
        'show_education' => ['Pendidikan', 'Tampilkan riwayat pendidikan.'],
        'show_organization_experience' => ['Pengalaman organisasi', 'Tampilkan pengalaman organisasi eksternal atau historis.'],
        'show_community_membership' => ['Community SIPORA', 'Tampilkan Community aktif beserta peran kontekstual Anda.'],
        'show_activity_passport' => ['Activity Passport', 'Tampilkan Activity SIPORA yang telah diselesaikan.'],
        'show_certificates' => ['Sertifikat', 'Tampilkan kredensial SIPORA yang telah diterbitkan.'],
        'show_achievements' => ['Prestasi', 'Tampilkan prestasi yang Anda laporkan.'],
    ];
@endphp
<div class="mx-auto max-w-5xl space-y-6">
    <x-workspace.page-header eyebrow="Profile" title="Kontrol Privasi" description="Pilih bagian Portfolio yang dapat dilihat publik. Data identitas, kontak, dan dokumen tetap privat.">
        <a class="btn-secondary" href="{{ route('youth.portfolio.show') }}">Pratinjau Portfolio</a>
    </x-workspace.page-header>
    <form method="post" action="{{ route('youth.profile.visibility.update') }}" class="rounded-2xl border border-indigo-100 bg-white shadow-card">
        @csrf @method('PUT')
        <div class="divide-y divide-slate-100">
            @foreach($settings as $key => [$label, $description])
                <label class="flex min-h-20 cursor-pointer items-center justify-between gap-5 px-5 py-4 hover:bg-indigo-50/40 sm:px-6">
                    <span><strong class="block text-sm text-text-primary">{{ $label }}</strong><span class="mt-1 block text-xs leading-5 text-text-secondary">{{ $description }}</span></span>
                    <span class="relative shrink-0"><input type="hidden" name="{{ $key }}" value="0"><input class="peer sr-only" type="checkbox" name="{{ $key }}" value="1" @checked($user->profileVisibility?->{$key} ?? true)><span class="block h-7 w-12 rounded-full bg-slate-300 transition peer-checked:bg-primary peer-focus-visible:ring-2 peer-focus-visible:ring-orange-400 peer-focus-visible:ring-offset-2"></span><span class="absolute left-1 top-1 size-5 rounded-full bg-white shadow transition peer-checked:translate-x-5"></span></span>
                </label>
            @endforeach
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-indigo-100 bg-surface-muted px-5 py-4 sm:px-6"><p class="text-xs text-slate-500">Perubahan berlaku pada halaman Portfolio publik.</p><button class="btn" type="submit">Simpan privasi</button></div>
    </form>
</div>
@endsection
