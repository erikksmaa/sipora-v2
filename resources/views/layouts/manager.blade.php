@extends('layouts.base')
@section('content')
<div class="grid gap-6 lg:grid-cols-[250px_1fr]">
    <aside class="h-fit rounded-2xl bg-[#eef0ff] p-4 lg:sticky lg:top-24"><div class="mb-5 rounded-xl bg-white/70 px-3 py-3"><p class="text-xs font-extrabold uppercase tracking-wider text-[#243378]">Kelola komunitas</p><p class="mt-1 text-xs text-emerald-700">● Akses kontekstual</p></div>
        @if(($managedCommunities ?? collect())->count() > 1)<div class="mb-4" x-data><label for="community-switcher" class="mb-1 block text-xs font-bold uppercase text-slate-500">Ganti komunitas</label><select id="community-switcher" class="field !min-h-10 text-sm" @change="window.location = $event.target.value">@foreach($managedCommunities as $managedCommunity)<option value="{{ route('manager.dashboard', $managedCommunity) }}" @selected($managedCommunity->is($organization))>{{ $managedCommunity->name }}</option>@endforeach</select></div>@endif
        <nav aria-label="Navigasi pengelola"><a class="admin-nav-link {{ request()->routeIs('manager.dashboard') ? 'admin-nav-active' : '' }}" href="{{ route('manager.dashboard', $organization) }}">Dashboard</a><a class="admin-nav-link {{ request()->routeIs('manager.profile.*') ? 'admin-nav-active' : '' }}" href="{{ route('manager.profile.edit', $organization) }}">Profil Komunitas</a><a class="admin-nav-link {{ request()->routeIs('manager.members.*', 'manager.join-requests.*') ? 'admin-nav-active' : '' }}" href="{{ route('manager.members.index', $organization) }}">Anggota & Permintaan</a><a class="admin-nav-link {{ request()->routeIs('manager.activities.*') ? 'admin-nav-active' : '' }}" href="{{ route('manager.activities.index', $organization) }}">Activity</a></nav></aside>
    <div class="min-w-0">@yield('manager-content')</div>
</div>
@endsection
