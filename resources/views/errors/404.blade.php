@extends('layouts.public')
@section('title', 'Halaman tidak ditemukan · SIPORA')
@section('meta_description', 'Halaman yang diminta tidak ditemukan di SIPORA.')
@section('content')
<section class="grid min-h-[65vh] place-items-center bg-surface-soft px-5 py-16 text-center"><div class="max-w-xl"><span class="mx-auto grid size-16 place-items-center rounded-2xl bg-primary-50 text-primary"><x-ui.icon name="search" class="size-8" /></span><p class="mt-5 font-mono text-sm font-bold text-accent-700">404</p><h1 class="font-heading mt-2 text-4xl font-bold text-text-primary">Halaman tidak ditemukan</h1><p class="mt-4 leading-7 text-text-secondary">Tautan mungkin sudah berubah atau konten belum tersedia untuk publik.</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a class="landing-btn-primary" href="{{ route('home') }}">Kembali ke beranda</a><a class="btn-secondary" href="{{ route('search.index') }}">Cari di SIPORA</a></div></div></section>
@endsection
