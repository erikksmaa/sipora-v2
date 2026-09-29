@props(['activity', 'reason' => null, 'compact' => false])
@php
    $registrationOpen = (!$activity->registration_open_at || $activity->registration_open_at->isPast())
        && (!$activity->registration_close_at || $activity->registration_close_at->isFuture());
    $capacityAvailable = $activity->quota === null || ($activity->accepted_participants_count ?? 0) < $activity->quota;
@endphp
<article {{ $attributes->class(['group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-orange-300 hover:shadow-lg']) }}>
    <a href="{{ route('activities.show', $activity) }}" class="block focus:outline-none focus-visible:ring-4 focus-visible:ring-orange-300" aria-label="Lihat Activity {{ $activity->title }}">
        @if(!$compact)
            <div class="relative h-48 overflow-hidden bg-gradient-to-br from-[#243378] to-[#4a5dc7]">
                @if($activity->poster_path)
                    <img src="{{ route('activities.poster', $activity) }}" alt="Poster {{ $activity->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                @else
                    <div class="grid h-full place-items-center px-6 text-center text-2xl font-black text-white/90">{{ $activity->category->name }}</div>
                @endif
                <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-extrabold text-[#243378] shadow">{{ $registrationOpen && $capacityAvailable ? 'Pendaftaran tersedia' : 'Lihat jadwal' }}</span>
            </div>
        @endif
        <div class="p-5">
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-wider"><span class="text-orange-600">{{ $activity->category->name }}</span><span class="text-slate-300">·</span><span class="text-slate-500">{{ ucfirst($activity->location_type) }}</span></div>
            <h2 class="mt-2 text-xl font-black leading-snug text-[#18245c]">{{ $activity->title }}</h2>
            <p class="mt-2 text-sm font-semibold text-slate-600">{{ $activity->organization->name }}</p>
            <dl class="mt-4 space-y-2 text-sm text-slate-600"><div class="flex gap-2"><dt aria-label="Tanggal">📅</dt><dd>{{ $activity->start_at->translatedFormat('d M Y, H:i') }}</dd></div><div class="flex gap-2"><dt aria-label="Lokasi">📍</dt><dd>{{ $activity->administrativeArea?->name ?? $activity->venue_name ?? 'Daring' }}</dd></div></dl>
            @if($reason)<p class="mt-4 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-semibold text-[#3346a8]">{{ $reason }}</p>@endif
            <span class="mt-5 inline-flex font-bold text-[#3346a8]">Lihat detail <span class="ml-2" aria-hidden="true">→</span></span>
        </div>
    </a>
</article>
