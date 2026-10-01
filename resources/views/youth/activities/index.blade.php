@extends('layouts.youth')
@section('title', 'Pendaftaran Activity Saya · SIPORA')
@section('content')
<div class="space-y-6">
    <x-workspace.page-header eyebrow="Activity" title="Pendaftaran Saya" description="Pantau status pendaftaran dan pengalaman Activity milikmu.">
        <a class="btn-secondary" href="{{ route('activities.index') }}"><x-ui.icon name="globe" />Jelajahi Activity</a>
    </x-workspace.page-header>

    @php
        $tabs = ['' => 'Semua', 'pending' => 'Menunggu', 'accepted' => 'Diterima', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
    @endphp
    <nav class="flex gap-2 overflow-x-auto border-b border-slate-200 pb-3" aria-label="Filter pendaftaran Activity">
        @foreach($tabs as $value => $label)
            <a href="{{ route('youth.activities.index', $value ? ['status' => $value] : []) }}" @class(['min-h-10 whitespace-nowrap rounded-lg px-4 py-2 text-sm font-bold transition', 'bg-primary text-white' => $status === $value, 'text-slate-600 hover:bg-indigo-50 hover:text-primary' => $status !== $value]) @if($status === $value) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>

    <div class="grid gap-4 xl:grid-cols-2">
        @forelse($participations as $participation)
            @php
                $activity = $participation->activity;
                $registrationLabel = match($participation->registration_status) { 'pending' => 'Menunggu', 'accepted' => 'Diterima', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan', default => ucfirst($participation->registration_status) };
                $tone = match($participation->registration_status) { 'accepted' => 'success', 'pending' => 'warning', 'rejected', 'cancelled' => 'danger', default => 'neutral' };
            @endphp
            <article class="overflow-hidden rounded-2xl border border-indigo-100 bg-white shadow-card">
                <div class="grid sm:grid-cols-[150px_1fr]">
                    <x-public.media-fallback icon="activity" :label="$activity->category?->name ?? 'Activity'" class="min-h-36 rounded-none" />
                    <div class="p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-interactive">{{ $activity->category?->name ?? 'Activity' }}</p><h2 class="mt-1 text-lg font-extrabold text-text-primary">{{ $activity->title }}</h2><p class="mt-1 text-sm text-text-secondary">{{ $activity->organization->name }}</p></div><x-ui.badge :tone="$tone">{{ $registrationLabel }}</x-ui.badge></div>
                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-slate-500"><span class="inline-flex items-center gap-1.5"><x-ui.icon name="calendar" class="size-4" />{{ $activity->start_at->translatedFormat('d M Y') }}</span><span>Completion: {{ $participation->completion_status === 'completed' ? 'Selesai' : ($participation->completion_status === 'no_show' ? 'Tidak hadir' : 'Berjalan') }}</span></div>
                        <div class="mt-5 flex flex-wrap gap-2"><a class="btn-secondary !min-h-9 !px-3 text-sm" href="{{ route('activities.show', $activity) }}">Lihat Activity</a>@if($participation->completion_status === 'completed')<a class="btn-secondary !min-h-9 !px-3 text-sm" href="{{ route('youth.passport.show', $participation) }}">Lihat Passport</a>@endif @if($participation->certificate)<a class="btn-primary !min-h-9 !px-3 text-sm" href="{{ route('youth.certificates.show', $participation->certificate) }}">Lihat Sertifikat</a>@endif</div>
                    </div>
                </div>
            </article>
        @empty
            <x-ui.empty-state class="xl:col-span-2" title="Belum ada pendaftaran" description="Activity yang kamu daftar akan muncul di ruang ini." icon="activity" :href="route('activities.index')" action="Jelajahi Activity" />
        @endforelse
    </div>
    {{ $participations->links() }}
</div>
@endsection
