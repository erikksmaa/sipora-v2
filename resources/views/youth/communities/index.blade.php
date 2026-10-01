@extends('layouts.youth')
@section('title', 'Community Saya · SIPORA')
@section('content')
<div class="space-y-8">
    <x-workspace.page-header eyebrow="Community" title="Community Saya" description="Lihat keanggotaan aktif dan pantau pengajuan Community yang kamu buat.">
        <a class="btn-secondary" href="{{ route('communities.index') }}"><x-ui.icon name="globe" class="size-4" /> Jelajahi</a>
        <a class="btn" href="{{ route('youth.communities.create') }}"><x-ui.icon name="plus" class="size-4" /> Buat pengajuan</a>
    </x-workspace.page-header>

    <section aria-labelledby="active-communities">
        <div class="flex items-end justify-between gap-4"><div><h2 id="active-communities" class="text-xl font-black text-text-primary">Keanggotaan aktif</h2><p class="mt-1 text-sm text-text-secondary">Peran di bawah bersifat kontekstual pada setiap Community.</p></div><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-primary">{{ $memberships->count() }} Community</span></div>
        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($memberships as $membership)
                <article class="sipora-card flex flex-col"><div class="flex items-start justify-between gap-3"><span class="grid size-11 place-items-center rounded-xl bg-indigo-50 font-black text-primary">{{ mb_substr($membership->organization->name, 0, 1) }}</span><x-ui.badge tone="green">{{ ucfirst($membership->access_role) }}</x-ui.badge></div><h3 class="mt-4 text-lg font-black text-text-primary">{{ $membership->organization->name }}</h3><p class="mt-1 text-sm text-text-secondary">{{ $membership->position_title ?: $membership->organization->category?->name }}</p><div class="mt-auto flex flex-wrap gap-3 pt-5"><a class="public-link" href="{{ route('communities.show', $membership->organization) }}">Lihat publik</a>@if(in_array($membership->access_role, [\App\Models\OrganizationMembership::ROLE_LEADER, \App\Models\OrganizationMembership::ROLE_MANAGER], true))<a class="public-link text-orange-700" href="{{ route('manager.dashboard', $membership->organization) }}">Buka workspace</a>@endif</div></article>
            @empty
                <x-ui.empty-state class="md:col-span-2 xl:col-span-3" title="Belum bergabung dengan Community" description="Jelajahi Community publik dan ajukan permintaan bergabung." action="Jelajahi Community" :href="route('communities.index')" />
            @endforelse
        </div>
    </section>

    <section aria-labelledby="community-applications">
        <div><h2 id="community-applications" class="text-xl font-black text-text-primary">Pengajuan komunitas</h2><p class="mt-1 text-sm text-text-secondary">Draft dan status verifikasi Community yang kamu ajukan.</p></div>
        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($communities as $community)
                <a href="{{ route('youth.communities.show', $community) }}" class="sipora-card block transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md"><div class="flex items-start justify-between gap-3"><div class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-xl bg-indigo-50 font-extrabold text-primary">@if($community->logo_path)<img class="size-full object-cover" src="{{ route('youth.communities.logo', $community) }}" alt="">@else{{ mb_substr($community->name, 0, 1) }}@endif</div><x-community.status-badge :status="$community->review_status" /></div><h3 class="mt-4 text-lg font-bold text-text-primary">{{ $community->name }}</h3><p class="mt-1 text-sm text-slate-500">{{ $community->category->name }}</p><p class="mt-3 line-clamp-2 text-sm text-slate-600">{{ $community->description ?: 'Deskripsi belum dilengkapi.' }}</p></a>
            @empty
                <x-ui.empty-state class="md:col-span-2 xl:col-span-3" title="Belum ada pengajuan Community" description="Buat draft Community baru, lalu kirim untuk diverifikasi." action="Buat pengajuan" :href="route('youth.communities.create')" />
            @endforelse
        </div>
        @if($communities->hasPages())<div class="mt-6">{{ $communities->links() }}</div>@endif
    </section>
</div>
@endsection
