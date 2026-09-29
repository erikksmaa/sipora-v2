@props(['community', 'compact' => false])
<article {{ $attributes->class(['rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-orange-300 hover:shadow-lg']) }}>
    <a href="{{ route('communities.show', $community) }}" class="block rounded-2xl focus:outline-none focus-visible:ring-4 focus-visible:ring-orange-300" aria-label="Lihat Community {{ $community->name }}">
        <div class="flex items-start gap-4">
            <span class="grid size-16 shrink-0 place-items-center overflow-hidden rounded-2xl bg-[#243378] text-xl font-black text-white">
                @if($community->logo_path)<img src="{{ route('communities.logo', $community) }}" alt="Logo {{ $community->name }}" class="size-full object-cover" loading="lazy">@else{{ mb_substr($community->name, 0, 1) }}@endif
            </span>
            <div class="min-w-0"><div class="flex flex-wrap gap-2"><span class="text-xs font-black uppercase tracking-wider text-orange-600">{{ $community->category->name }}</span><span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Terverifikasi</span></div><h2 class="mt-1 text-xl font-black leading-snug text-[#18245c]">{{ $community->name }}</h2><p class="mt-2 text-sm text-slate-500">{{ $community->administrativeArea?->name ?? 'Kabupaten Pemalang' }}</p></div>
        </div>
        @unless($compact)<p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-600">{{ $community->description ?: 'Profil Community belum dilengkapi.' }}</p>@endunless
        <p class="mt-4 text-sm font-semibold text-slate-500">{{ $community->active_members_count }} anggota aktif · {{ $community->public_activities_count }} Activity publik</p>
        <span class="mt-4 inline-flex text-sm font-bold text-[#3346a8]">Lihat Community <span class="ml-2" aria-hidden="true">→</span></span>
    </a>
</article>
