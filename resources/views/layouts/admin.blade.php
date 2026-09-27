<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin SIPORA')</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebar: false }" class="min-h-screen bg-[#f5f6fc] text-slate-900 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:z-50 focus:bg-white focus:p-3">Lewati ke konten</a>
<div class="min-h-screen lg:grid lg:grid-cols-[270px_1fr]">
    <aside :class="sidebar ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-[270px] border-r border-indigo-100 bg-white transition lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
        <div class="flex h-20 items-center gap-3 border-b border-indigo-100 px-6"><span class="grid size-10 place-items-center rounded-xl bg-[#243378] text-sm font-black text-white">SP</span><div><strong class="block text-xl text-[#243378]">SIPORA</strong><small class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Super Administrator</small></div></div>
        <nav class="space-y-7 p-5 text-sm" aria-label="Navigasi admin">
            <div><p class="mb-2 px-3 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">Utama</p><a href="{{ route('admin.dashboard') }}" @class(['admin-nav-link', 'admin-nav-active' => request()->routeIs('admin.dashboard')])>▦ Overview / Dashboard</a></div>
            <div><p class="mb-2 px-3 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">Verifikasi</p><a href="{{ route('admin.identity-verifications.index') }}" @class(['admin-nav-link', 'admin-nav-active' => request()->routeIs('admin.identity-verifications.*')])>▣ Verifikasi Identitas Pemuda</a><span class="admin-nav-link cursor-not-allowed text-slate-400">Validasi Komunitas</span></div>
            <div><p class="mb-2 px-3 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">Sistem & Audit</p><span class="admin-nav-link cursor-not-allowed text-slate-400">Log Audit</span><span class="admin-nav-link cursor-not-allowed text-slate-400">Master Data</span></div>
        </nav>
    </aside>
    <div class="min-w-0">
        <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-indigo-100 bg-white/95 px-5 backdrop-blur sm:px-8"><button class="grid size-10 place-items-center rounded-lg border lg:hidden" @click="sidebar = !sidebar" aria-label="Buka navigasi">☰</button><div class="hidden sm:block"><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Dindikpora Kabupaten Pemalang</p><p class="font-extrabold text-[#18245c]">Pusat Tata Kelola Kepemudaan</p></div><div class="flex items-center gap-3"><span class="hidden text-right text-sm sm:block"><strong class="block">{{ auth()->user()->name }}</strong><small class="text-slate-500">Administrator</small></span><form method="post" action="{{ route('logout') }}">@csrf<button class="btn-secondary !min-h-10 !px-4" type="submit">Keluar</button></form></div></header>
        <main id="main" class="p-5 sm:p-8">@if(session('status'))<div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('status') }}</div>@endif @if($errors->any())<div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800"><p class="font-bold">Periksa kembali keputusan:</p><ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif @yield('content')</main>
    </div>
</div>
</body></html>
