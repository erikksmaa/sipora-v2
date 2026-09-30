@extends('layouts.public')
@section('title', 'Opportunity Hub · SIPORA')
@section('meta_description', 'Temukan informasi Opportunity eksternal yang dikurasi Admin SIPORA Kabupaten Pemalang.')
@section('canonical', route('opportunities.index'))
@section('content')
<section class="bg-[#18245c] px-5 py-14 text-white"><div class="mx-auto max-w-7xl"><p class="text-xs font-black uppercase tracking-[.18em] text-orange-300">Opportunity Hub</p><h1 class="mt-3 text-4xl font-black sm:text-5xl">Temukan peluang pengembangan diri</h1><p class="mt-4 max-w-2xl text-lg leading-8 text-indigo-100">Informasi peluang eksternal yang dikurasi Admin SIPORA. Pendaftaran dilakukan pada situs penyedia.</p></div></section>
<div class="mx-auto max-w-7xl px-5 py-10">
    <form method="GET" action="{{ route('opportunities.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm" aria-label="Filter Opportunity">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <label class="xl:col-span-2"><span class="label">Cari Opportunity</span><input class="field" type="search" name="q" value="{{ $filters['q'] }}" maxlength="120" placeholder="Judul, penyedia, kategori, atau lokasi"></label>
            <label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>{{ $category->name }}</option>@endforeach</select></label>
            <label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option>@foreach ($locations as $location)<option value="{{ $location->code }}" @selected($filters['location'] === $location->code)>{{ $location->name }}</option>@endforeach</select></label>
            <label><span class="label">Status batas waktu</span><select class="field" name="deadline"><option value="open" @selected($filters['deadline'] === 'open')>Masih tersedia</option><option value="expired" @selected($filters['deadline'] === 'expired')>Sudah lewat</option><option value="all" @selected($filters['deadline'] === 'all')>Semua</option></select></label>
        </div>
        <div class="mt-5 flex gap-3"><button class="landing-btn-primary" type="submit">Terapkan filter</button><a class="landing-btn-secondary" href="{{ route('opportunities.index') }}">Reset</a></div>
    </form>
    <div class="mt-8 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-black uppercase tracking-wider text-orange-600">Peluang terkurasi</p><h2 class="mt-1 text-2xl font-black text-[#18245c]">{{ $opportunities->total() }} hasil ditemukan</h2></div>@auth @role('youth')<a class="text-sm font-bold text-[#3346a8]" href="{{ route('youth.opportunities.bookmarks') }}">Opportunity tersimpan →</a>@endrole @endauth</div>
    <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">@forelse ($opportunities as $opportunity)<x-discovery.opportunity-card :opportunity="$opportunity" />@empty<div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-white p-12 text-center"><h2 class="text-xl font-black text-[#18245c]">Tidak ada Opportunity yang cocok</h2><p class="mt-2 text-slate-500">Ubah pencarian atau filter untuk melihat pilihan lain.</p></div>@endforelse</div>
    @if ($opportunities->hasPages())<div class="mt-10">{{ $opportunities->links() }}</div>@endif
</div>
@endsection
