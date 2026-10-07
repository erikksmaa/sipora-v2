@extends('layouts.manager')
@section('title', 'Permintaan Bergabung · '.$organization->name)
@section('manager-content')
<div class="space-y-6">
    <x-workspace.page-header eyebrow="Community" title="Permintaan Bergabung" :description="'Tinjau permintaan bergabung ke '.$organization->name.'.'">
        <a class="btn-secondary" href="{{ route('manager.members.index', $organization) }}">Lihat anggota</a>
    </x-workspace.page-header>
    <section class="sipora-card">
        <div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary">Menunggu keputusan</h2><span class="rounded-full bg-orange-100 px-3 py-1 text-sm font-bold text-orange-800">{{ $pending->total() }} pending</span></div>
        <div class="mt-5 space-y-3">
            @forelse($pending as $joinRequest)
                <article class="flex flex-col justify-between gap-4 rounded-sm border border-border bg-surface-soft p-4 sm:flex-row sm:items-center">
                    <div><h3 class="font-bold text-text-primary">{{ $joinRequest->user->profile?->full_name ?? $joinRequest->user->name }}</h3><p class="text-sm text-text-secondary">{{ $joinRequest->user->email }}</p><p class="mt-1 text-xs text-text-muted">Diajukan {{ $joinRequest->requested_at?->translatedFormat('d M Y, H:i') }}</p></div>
                    <div class="flex flex-wrap gap-2"><form method="post" action="{{ route('manager.join-requests.review', [$organization, $joinRequest]) }}">@csrf<input type="hidden" name="decision" value="accepted"><button class="btn-primary" type="submit">Terima</button></form><form method="post" action="{{ route('manager.join-requests.review', [$organization, $joinRequest]) }}"><input type="hidden" name="decision" value="rejected">@csrf<button class="btn-secondary !text-red-700" type="submit">Tolak</button></form></div>
                </article>
            @empty
                <x-ui.empty-state title="Tidak ada permintaan pending" description="Permintaan baru akan tampil di sini." icon="users" />
            @endforelse
        </div>
        <div class="mt-5">{{ $pending->links() }}</div>
    </section>
</div>
@endsection
