@props(['program', 'compact' => false])
<article class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
    <div class="flex flex-wrap items-center justify-between gap-2"><span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-black text-[#3346a8]">{{ $program->category->name }}</span><span class="rounded-full {{ $program->execution_status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-orange-50 text-orange-700' }} px-3 py-1 text-xs font-black">{{ $program->execution_status === 'completed' ? 'Selesai' : 'Berjalan' }}</span></div>
    <h3 class="mt-4 {{ $compact ? 'text-lg' : 'text-xl' }} font-black text-[#18245c]">{{ $program->title }}</h3>
    <p class="mt-2 text-sm font-semibold text-slate-600">{{ $program->organization->name }}</p>
    @unless($compact)<p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">{{ $program->description ?: 'Deskripsi Program belum tersedia.' }}</p>@endunless
    <div class="mt-auto pt-5 text-sm text-slate-500"><p>{{ $program->start_date?->translatedFormat('d M Y') ?? 'Tanggal mulai belum ditentukan' }}@if($program->end_date) – {{ $program->end_date->translatedFormat('d M Y') }}@endif</p>@isset($program->public_activities_count)<p class="mt-1">{{ $program->public_activities_count }} Activity publik</p>@endisset</div>
    <a class="mt-5 font-bold text-[#3346a8]" href="{{ route('programs.show', $program) }}">Lihat Program →</a>
</article>
