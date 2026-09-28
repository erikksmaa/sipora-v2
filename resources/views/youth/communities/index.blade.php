@extends('layouts.youth')
@section('title', 'Pengajuan Komunitas · SIPORA')
@section('content')
<div class="space-y-6">
    <section class="overflow-hidden rounded-3xl bg-[#243378] p-6 text-white shadow-lg sm:p-8">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><p class="text-sm font-bold text-orange-300">Komunitas SIPORA</p><h1 class="mt-2 text-3xl font-extrabold">Pengajuan komunitas</h1><p class="mt-2 max-w-2xl text-indigo-100">Siapkan profil komunitas sebagai draft, lalu kirim untuk diverifikasi.</p></div><a class="landing-btn-primary" href="{{ route('youth.communities.create') }}">+ Buat pengajuan</a></div>
    </section>
    @forelse($communities as $community)
        @if($loop->first)<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">@endif
        <a href="{{ route('youth.communities.show', $community) }}" class="sipora-card block transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
            <div class="flex items-start justify-between gap-3"><div class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-xl bg-indigo-50 font-extrabold text-[#243378]">@if($community->logo_path)<img class="size-full object-cover" src="{{ route('youth.communities.logo', $community) }}" alt="">@else{{ mb_substr($community->name, 0, 1) }}@endif</div><x-community.status-badge :status="$community->review_status" /></div>
            <h2 class="mt-4 text-lg font-bold text-[#243378]">{{ $community->name }}</h2><p class="mt-1 text-sm text-slate-500">{{ $community->category->name }}</p><p class="mt-3 line-clamp-2 text-sm text-slate-600">{{ $community->description ?: 'Deskripsi belum dilengkapi.' }}</p>
        </a>
        @if($loop->last)</div>{{ $communities->links() }}@endif
    @empty
        <section class="sipora-card py-14 text-center"><div class="mx-auto grid size-16 place-items-center rounded-2xl bg-indigo-50 text-3xl">◎</div><h2 class="mt-4 text-xl font-bold">Belum ada pengajuan komunitas</h2><p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">Mulai dengan membuat draft. Draft tidak langsung aktif dan harus melalui verifikasi.</p><a class="btn mt-6" href="{{ route('youth.communities.create') }}">Buat draft pertama</a></section>
    @endforelse
</div>
@endsection
