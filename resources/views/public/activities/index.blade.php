@extends('layouts.public')
@section('title', 'Jelajahi Activity · SIPORA')
@section('meta_description', 'Temukan Activity terverifikasi yang dipublikasikan Community di SIPORA Kabupaten Pemalang.')
@section('canonical', route('activities.index'))
@section('content')
<section class="bg-[#18245c] px-5 py-14 text-white"><div class="mx-auto max-w-7xl"><p class="text-xs font-black uppercase tracking-[.18em] text-orange-300">Eksplorasi publik</p><h1 class="mt-3 text-4xl font-black sm:text-5xl">Temukan Activity untuk bertumbuh</h1><p class="mt-4 max-w-2xl text-lg leading-8 text-indigo-100">Cari kegiatan terverifikasi yang telah dipublikasikan oleh Community di SIPORA.</p></div></section>
<div class="mx-auto max-w-7xl px-5 py-10">
    <form method="GET" action="{{ route('activities.index') }}" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm" aria-label="Filter Activity">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <label class="xl:col-span-2"><span class="label">Cari Activity</span><input class="field" type="search" name="q" value="{{ $filters['q'] }}" maxlength="120" placeholder="Judul, Community, kategori, atau lokasi"></label>
            <label><span class="label">Kategori</span><select class="field" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected($filters['category']===$category->slug)>{{ $category->name }}</option>@endforeach</select></label>
            <label><span class="label">Kecamatan</span><select class="field" name="location"><option value="">Semua lokasi</option>@foreach($locations as $location)<option value="{{ $location->code }}" @selected($filters['location']===$location->code)>{{ $location->name }}</option>@endforeach</select></label>
            <label><span class="label">Mode</span><select class="field" name="mode"><option value="">Semua mode</option>@foreach(['offline'=>'Offline','online'=>'Online','hybrid'=>'Hybrid'] as $value=>$label)<option value="{{ $value }}" @selected($filters['mode']===$value)>{{ $label }}</option>@endforeach</select></label>
            <label><span class="label">Pendaftaran</span><select class="field" name="registration"><option value="">Semua mekanisme</option><option value="open" @selected($filters['registration']==='open')>Langsung diterima</option><option value="approval_required" @selected($filters['registration']==='approval_required')>Memerlukan persetujuan</option></select></label>
            <label><span class="label">Urutkan</span><select class="field" name="sort"><option value="upcoming" @selected($filters['sort']==='upcoming')>Paling dekat</option><option value="newest" @selected($filters['sort']==='newest')>Terbaru dipublikasikan</option><option value="closing" @selected($filters['sort']==='closing')>Pendaftaran segera tutup</option></select></label>
            <label class="flex items-end gap-3 pb-3"><input class="size-5 rounded border-slate-300 text-[#243378] focus:ring-orange-400" type="checkbox" name="availability" value="open" @checked($filters['availability']==='open')><span class="text-sm font-semibold text-slate-700">Pendaftaran tersedia</span></label>
        </div>
        <div class="mt-5 flex flex-wrap gap-3"><button class="landing-btn-primary" type="submit">Terapkan filter</button><a class="landing-btn-secondary" href="{{ route('activities.index') }}">Reset</a></div>
    </form>
    <div class="mt-8 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-black uppercase tracking-wider text-orange-600">Activity publik</p><h2 class="mt-1 text-2xl font-black text-[#18245c]">{{ $activities->total() }} hasil ditemukan</h2></div><a class="text-sm font-bold text-[#3346a8]" href="{{ route('communities.index') }}">Jelajahi Community →</a></div>
    <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">@forelse($activities as $activity)<x-discovery.activity-card :activity="$activity" />@empty<div class="col-span-full rounded-3xl border-2 border-dashed border-slate-200 bg-white p-12 text-center"><h2 class="text-xl font-black text-[#18245c]">Tidak ada Activity yang cocok</h2><p class="mt-2 text-slate-500">Ubah kata pencarian atau longgarkan filter untuk melihat pilihan lain.</p></div>@endforelse</div>
    @if($activities->hasPages())<div class="mt-10">{{ $activities->links() }}</div>@endif
</div>
@endsection
