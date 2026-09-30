@extends('layouts.public')
@section('title', 'Halaman tidak ditemukan · SIPORA')
@section('meta_description', 'Halaman yang diminta tidak ditemukan di SIPORA.')
@section('content')
<section class="grid min-h-[65vh] place-items-center bg-[#f5f6fc] px-5 py-16 text-center">
    <div class="max-w-xl"><p class="text-sm font-black uppercase tracking-[.2em] text-orange-600">404</p><h1 class="mt-3 text-4xl font-black text-[#18245c]">Halaman tidak ditemukan</h1><p class="mt-4 leading-7 text-slate-600">Tautan mungkin sudah berubah atau konten belum tersedia untuk publik.</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a class="landing-btn-primary" href="{{ route('home') }}">Kembali ke beranda</a><a class="landing-btn-secondary" href="{{ route('search.index') }}">Cari di SIPORA</a></div></div>
</section>
@endsection
