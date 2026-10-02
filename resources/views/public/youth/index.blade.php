@extends('layouts.public')
@section('title', 'Direktori Pemuda · SIPORA')
@section('meta_description', 'Temukan Portfolio publik pemuda dalam ekosistem SIPORA Kabupaten Pemalang.')
@section('canonical', route('youth-directory.index'))
@section('content')
<x-public.page-intro eyebrow="Direktori Publik" title="Pemuda SIPORA" description="Temukan profil dan rekam pengalaman pemuda yang memilih membagikan Portfolio-nya secara publik." />
<section class="bg-surface-soft px-5 py-12"><div class="mx-auto max-w-7xl">
    <form method="GET" action="{{ route('youth-directory.index') }}" class="grid gap-3 rounded-2xl border border-border bg-white p-4 shadow-xs md:grid-cols-[minmax(0,1fr)_260px_auto]" role="search">
        <label><span class="field-label">Cari nama, bio, atau skill publik</span><input class="field" type="search" name="q" value="{{ $query }}" maxlength="120" placeholder="Cari pemuda..."></label>
        <label><span class="field-label">Area domisili</span><select class="field" name="area"><option value="">Semua area</option>@foreach($areas as $area)<option value="{{ $area }}" @selected($selectedArea === $area)>{{ $area }}</option>@endforeach</select></label>
        <div class="flex items-end gap-2"><button class="landing-btn-primary flex-1" type="submit">Terapkan</button>@if($query || $selectedArea)<a class="btn-secondary !min-h-11" href="{{ route('youth-directory.index') }}">Reset</a>@endif</div>
    </form>
    <div class="mt-8 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Portfolio yang dibagikan</p><h2 class="public-section-title mt-1">{{ $profiles->total() }} profil publik</h2></div><p class="max-w-xl text-sm text-text-secondary">Urutan nama bersifat netral dan bukan peringkat aktivitas atau capaian.</p></div>
    <div class="mt-7 grid gap-5 md:grid-cols-2 xl:grid-cols-3">@forelse($profiles as $profile)<x-discovery.youth-card :profile="$profile" />@empty<x-ui.empty-state class="md:col-span-2 xl:col-span-3" icon="users" title="Belum ada profil pemuda publik yang sesuai." description="Coba kata pencarian atau area lain." />@endforelse</div>
    @if($profiles->hasPages())<div class="mt-8">{{ $profiles->links() }}</div>@endif
</div></section>
@endsection
