@extends('layouts.base')
@section('content')
<div class="grid gap-6 lg:grid-cols-[250px_1fr]">
    <aside class="h-fit rounded-2xl bg-[#eef0ff] p-4 lg:sticky lg:top-24">
        <div class="mb-5 rounded-xl bg-white/70 px-3 py-3"><p class="text-xs font-extrabold uppercase tracking-wider text-[#243378]">Verifier Dinas</p><p class="mt-1 text-xs text-emerald-700">● Workspace aktif</p></div>
        <nav class="space-y-1 text-sm" aria-label="Navigasi Verifier">
            <a href="{{ route('verifier.dashboard') }}" class="admin-nav-link {{ request()->routeIs('verifier.dashboard') ? 'admin-nav-active' : '' }}">Beranda Verifier</a>
            <a href="{{ route('verifier.community-verifications.index') }}" class="admin-nav-link {{ request()->routeIs('verifier.community-verifications.*') ? 'admin-nav-active' : '' }}">Organisasi & Komunitas</a>
            <a href="{{ route('verifier.activity-verifications.index') }}" class="admin-nav-link {{ request()->routeIs('verifier.activity-verifications.*') ? 'admin-nav-active' : '' }}">Usulan Kegiatan</a>
            <span class="admin-nav-link cursor-not-allowed text-slate-400">Log Audit & Kepatuhan</span>
        </nav>
    </aside>
    <div class="min-w-0">@yield('verifier-content')</div>
</div>
@endsection
