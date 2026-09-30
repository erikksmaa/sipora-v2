@extends('layouts.public')
@section('title', 'Jelajahi Community · SIPORA')
@section('meta_description', 'Jelajahi Community aktif dan terverifikasi di SIPORA Kabupaten Pemalang.')
@section('canonical', route('communities.index'))
@section('content')
<section class="bg-[#18245c] px-5 py-14 text-white"><div class="mx-auto max-w-7xl"><p class="text-xs font-black uppercase tracking-[.18em] text-orange-300">Tumbuh bersama</p><h1 class="mt-3 text-4xl font-black sm:text-5xl">Temukan Community yang tepat</h1><p class="mt-4 max-w-2xl text-lg leading-8 text-indigo-100">Jelajahi Community aktif dan terverifikasi di seluruh Kabupaten Pemalang.</p></div></section>
<div class="mx-auto max-w-7xl px-5 py-10">
    <form method="GET" action="{{ route('communities.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm" aria-label="Filter Community">
        <div class="grid gap-4 md:grid-cols-3"><label><span class="label">Cari Community</span><input class="field" type="search" name="q" value="{{ $filters['q'] }}" maxlength="120" placeholder="Nama, kategori, deskripsi, atau lokasi"></label><label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected($filters['category']===$category->slug)>{{ $category->name }}</option>@endforeach</select></label><label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option>@foreach($locations as $location)<option value="{{ $location->code }}" @selected($filters['location']===$location->code)>{{ $location->name }}</option>@endforeach</select></label></div>
        <div class="mt-5 flex flex-wrap gap-3"><button class="landing-btn-primary" type="submit">Terapkan filter</button><a class="landing-btn-secondary" href="{{ route('communities.index') }}">Reset</a></div>
    </form>
    <div class="mt-8 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-black uppercase tracking-wider text-orange-600">Community publik</p><h2 class="mt-1 text-2xl font-black text-[#18245c]">{{ $communities->total() }} hasil ditemukan</h2></div><a class="text-sm font-bold text-[#3346a8]" href="{{ route('activities.index') }}">Jelajahi Activity →</a></div>
    <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">@forelse($communities as $community)<x-discovery.community-card :community="$community" />@empty<div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-white p-12 text-center"><h2 class="text-xl font-black text-[#18245c]">Tidak ada Community yang cocok</h2><p class="mt-2 text-slate-500">Coba kata pencarian, kategori, atau lokasi lain.</p></div>@endforelse</div>
    @if($communities->hasPages())<div class="mt-10">{{ $communities->links() }}</div>@endif
</div>
@endsection
