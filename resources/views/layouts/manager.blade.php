@extends('layouts.base')
@section('content')
<div class="grid gap-6 lg:grid-cols-[250px_1fr]">
    <aside class="h-fit rounded-2xl bg-[#eef0ff] p-4 lg:sticky lg:top-24"><div class="mb-5 rounded-xl bg-white/70 px-3 py-3"><p class="text-xs font-extrabold uppercase tracking-wider text-[#243378]">Kelola komunitas</p><p class="mt-1 text-xs text-emerald-700">● Akses kontekstual</p></div><nav aria-label="Navigasi pengelola"><span class="admin-nav-link cursor-not-allowed text-slate-400">Ringkasan</span><a class="admin-nav-link admin-nav-active" href="{{ url()->current() }}">Anggota & Permintaan</a><span class="admin-nav-link cursor-not-allowed text-slate-400">Kegiatan</span></nav></aside>
    <div class="min-w-0">@yield('manager-content')</div>
</div>
@endsection
