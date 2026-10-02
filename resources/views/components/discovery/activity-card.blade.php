@props(['activity', 'reason' => null, 'compact' => false])
@php
    $registrationOpen = (! $activity->registration_open_at || $activity->registration_open_at->isPast())
        && (! $activity->registration_close_at || $activity->registration_close_at->isFuture());
    $capacityAvailable = $activity->quota === null || ($activity->accepted_participants_count ?? 0) < $activity->quota;
@endphp
<article {{ $attributes->class(['group flex h-full flex-col overflow-hidden rounded-2xl border border-border bg-white shadow-xs transition duration-200 hover:-translate-y-1 hover:border-primary-200 hover:shadow-md']) }}>
    <a href="{{ route('activities.show', $activity) }}" class="flex h-full flex-col focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" aria-label="Lihat Activity {{ $activity->title }}">
        <div class="relative {{ $compact ? 'h-32' : 'h-52' }} overflow-hidden">
            @if($activity->poster_path)<img src="{{ route('activities.poster', $activity) }}" alt="Poster {{ $activity->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]" loading="lazy">@else<x-public.media-fallback :label="$activity->category->name" icon="activity" />@endif
            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full border border-white/60 bg-white/95 px-3 py-1.5 text-xs font-bold text-primary shadow-xs"><x-ui.icon :name="$registrationOpen && $capacityAvailable ? 'check' : 'calendar'" class="size-3.5" />{{ $registrationOpen && $capacityAvailable ? 'Pendaftaran tersedia' : 'Lihat jadwal' }}</span>
        </div>
        <div class="flex flex-1 flex-col p-5"><div class="flex flex-wrap items-center gap-2"><x-ui.badge tone="orange">{{ $activity->category->name }}</x-ui.badge><span class="text-xs font-semibold text-text-muted">{{ ucfirst($activity->location_type) }}</span></div><h2 class="mt-3 text-lg font-bold leading-snug text-text-primary">{{ $activity->title }}</h2><p class="mt-1.5 text-sm font-semibold text-primary">{{ $activity->organization->name }}</p><dl class="mt-4 space-y-2"><div class="public-meta"><dt><x-ui.icon name="calendar" class="size-4 text-text-muted" /><span class="sr-only">Tanggal</span></dt><dd>{{ $activity->start_at->translatedFormat('d M Y, H:i') }}</dd></div><div class="public-meta"><dt><x-ui.icon name="map-pin" class="size-4 text-text-muted" /><span class="sr-only">Lokasi</span></dt><dd>{{ $activity->administrativeArea?->name ?? $activity->venue_name ?? 'Daring' }}</dd></div></dl>@if($reason)<p class="mt-4 rounded-xl bg-primary-50 px-3 py-2 text-xs font-semibold text-primary">{{ $reason }}</p>@endif<span class="public-link mt-auto pt-4">Lihat detail <x-ui.icon name="chevron-right" class="size-4" /></span></div>
    </a>
</article>
