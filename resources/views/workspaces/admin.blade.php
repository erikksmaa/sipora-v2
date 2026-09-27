@extends('layouts.admin')
@section('title', 'Dashboard Admin · SIPORA')
@section('content')
<div class="mx-auto max-w-7xl">
    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#f59e0b]">Super Administrator</p>
    <h1 class="mt-2 max-w-3xl text-3xl font-black tracking-tight text-[#18245c] sm:text-4xl">Dashboard Tata Kelola & Verifikasi Identitas SIPORA v2</h1>
    <p class="mt-4 max-w-3xl leading-7 text-slate-600">Kelola antrean verifikasi identitas pemuda secara aman. Modul administrasi lain akan hadir sesuai fase implementasinya.</p>
    <div class="mt-10 grid gap-6 md:grid-cols-2">
        <section class="sipora-card border-l-4 border-l-[#f59e0b]"><span class="grid size-12 place-items-center rounded-xl bg-orange-50 text-2xl">▣</span><h2 class="mt-5 text-xl font-extrabold text-[#18245c]">Verifikasi Identitas Pemuda</h2><p class="mt-2 text-slate-600">Tinjau pengajuan KTP, KIA, atau kartu pelajar, lalu berikan keputusan yang tercatat dalam audit.</p><a href="{{ route('admin.identity-verifications.index') }}" class="landing-btn-primary mt-6">Buka antrean verifikasi →</a></section>
        <section class="sipora-card"><span class="grid size-12 place-items-center rounded-xl bg-indigo-50 text-2xl">⌁</span><h2 class="mt-5 text-xl font-extrabold text-[#18245c]">Keamanan Dokumen</h2><p class="mt-2 leading-7 text-slate-600">Dokumen hanya disajikan melalui route Admin yang terotorisasi, tanpa URL penyimpanan publik dan tanpa cache browser.</p></section>
    </div>
</div>
@endsection
