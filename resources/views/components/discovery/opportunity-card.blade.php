@props(['opportunity', 'compact' => false])
<article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="flex items-start justify-between gap-3">
        <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-black text-orange-700">{{ $opportunity->category->name }}</span>
        @if ($opportunity->deadline_at)
            <span class="text-xs font-semibold {{ $opportunity->deadline_at->isPast() ? 'text-slate-400' : 'text-[#3346a8]' }}">{{ $opportunity->deadline_at->isPast() ? 'Ditutup' : $opportunity->deadline_at->diffForHumans() }}</span>
        @endif
    </div>
    <h3 class="mt-4 {{ $compact ? 'text-lg' : 'text-xl' }} font-black text-[#18245c]">{{ $opportunity->title }}</h3>
    <p class="mt-2 text-sm font-semibold text-slate-600">{{ $opportunity->provider_name }}</p>
    @unless ($compact)<p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">{{ $opportunity->description }}</p>@endunless
    <div class="mt-auto pt-5 text-sm text-slate-500">
        <p>{{ $opportunity->location_text ?: $opportunity->administrativeArea?->name ?: 'Lokasi tidak ditentukan' }}</p>
        <p class="mt-1">Batas: {{ $opportunity->deadline_at?->translatedFormat('d M Y, H:i') ?? 'Tidak ditentukan' }}</p>
    </div>
    <a class="mt-5 font-bold text-[#3346a8]" href="{{ route('opportunities.show', $opportunity) }}">Lihat peluang →</a>
</article>
