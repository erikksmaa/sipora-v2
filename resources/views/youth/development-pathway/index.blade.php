@extends('layouts.youth')
@section('title', 'Development Pathway · SIPORA')
@section('content')
<div class="mx-auto max-w-7xl space-y-7">
    <x-workspace.page-header eyebrow="Ruang bertumbuh" title="Development Pathway" description="Lihat arah minatmu, pengalaman yang sudah tercatat, dan langkah berikutnya dari ekosistem SIPORA.">
        <a class="btn-secondary" href="{{ route('youth.portfolio.show') }}">Buka Portfolio</a>
    </x-workspace.page-header>

    <section class="sipora-card border-l-4 border-l-accent-500 p-5 sm:p-7" aria-labelledby="pathway-direction">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">01 · Di mana kamu sekarang</p>
        <h2 id="pathway-direction" class="mt-2 font-heading text-2xl font-bold text-text-primary">Arah Pengembanganmu</h2>
        <p class="mt-2 text-sm leading-6 text-text-secondary">Berdasarkan minat yang kamu pilih sendiri. Ini bukan penilaian kemampuan atau prediksi karier.</p>
        @if($pathway['interests'])
            <div class="mt-5 flex flex-wrap gap-2">@foreach($pathway['interests'] as $interest)<span class="border border-border bg-surface-soft px-3 py-2 text-sm font-bold text-text-primary">{{ $interest }}</span>@endforeach</div>
        @else
            <p class="mt-5 border border-dashed border-border bg-surface-soft p-4 text-sm text-text-secondary">Belum ada minat yang dipilih.</p>
        @endif
    </section>

    <section class="sipora-card p-5 sm:p-7" aria-labelledby="pathway-assets">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">02 · Yang sudah kamu miliki</p>
        <h2 id="pathway-assets" class="mt-2 font-heading text-2xl font-bold text-text-primary">Jejak pengembanganmu</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Minat</p><strong class="mt-2 block text-2xl text-text-primary">{{ count($pathway['interests']) }}</strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="{{ route('youth.interests.edit') }}">Kelola minat</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Keahlian</p><strong class="mt-2 block text-2xl text-text-primary">{{ count($pathway['skills']) }}</strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="{{ route('youth.skills.edit') }}">Kelola keahlian</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Activity selesai</p><strong class="mt-2 block text-2xl text-text-primary">{{ $pathway['completedCount'] }}</strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="{{ route('youth.passport.index') }}">Lihat Passport</a></div>
            <div class="border border-border bg-surface-soft p-4"><p class="text-sm text-text-secondary">Sertifikat SIPORA</p><strong class="mt-2 block text-2xl text-text-primary">{{ $pathway['certificateCount'] }}</strong><a class="mt-3 inline-block text-sm font-bold text-interactive underline" href="{{ route('youth.certificates.index') }}">Lihat sertifikat</a></div>
        </div>
        @if($pathway['skills'])<div class="mt-5 flex flex-wrap gap-2" aria-label="Keahlian yang tercatat">@foreach($pathway['skills'] as $skill)<span class="border border-border bg-surface px-3 py-1.5 text-xs font-semibold text-text-primary">{{ $skill }}</span>@endforeach</div>@endif
    </section>

    <section aria-labelledby="pathway-next">
        <div class="mb-4"><p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">03 · Apa yang bisa kamu lakukan</p><h2 id="pathway-next" class="mt-2 font-heading text-2xl font-bold text-text-primary">Langkah Berikutnya</h2><p class="mt-2 text-sm text-text-secondary">Pilihan di bawah didasarkan pada kategori dan istilah yang terkait dengan minat, keahlian, atau Activity yang pernah kamu selesaikan.</p></div>
        @unless($pathway['ready'])
            <div class="sipora-card p-6 sm:p-8"><h3 class="font-heading text-xl font-bold text-text-primary">Lengkapi minat dan keahlianmu</h3><p class="mt-2 max-w-2xl text-sm leading-6 text-text-secondary">Tambahkan minat dan keahlian agar SIPORA dapat memberikan rekomendasi yang lebih relevan. Kami belum menampilkan pilihan acak untukmu.</p><div class="mt-5 flex flex-wrap gap-3"><a class="btn-primary" href="{{ route('youth.interests.edit') }}">Kelola Minat</a><a class="btn-secondary" href="{{ route('youth.skills.edit') }}">Kelola Keahlian</a></div></div>
        @else
            <div class="grid gap-5 xl:grid-cols-3">
                @foreach(['activity' => ['Activity', 'Kegiatan yang masih menerima pendaftaran', 'activities.show', 'activities.index'], 'community' => ['Community', 'Komunitas aktif untuk bertumbuh bersama', 'communities.show', 'communities.index'], 'opportunity' => ['Opportunity', 'Peluang publik yang masih tersedia', 'opportunities.show', 'opportunities.index']] as $type => [$label, $description, $showRoute, $indexRoute])
                    <section class="sipora-card min-w-0 p-5" aria-labelledby="pathway-{{ $type }}">
                        <div class="flex items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-widest text-interactive">{{ $label }}</p><h3 id="pathway-{{ $type }}" class="mt-1 font-heading text-xl font-bold text-text-primary">{{ $description }}</h3></div><span class="border border-border bg-surface-soft px-2 py-1 text-xs font-bold text-text-primary">{{ count($pathway['recommendations'][$type]) }}</span></div>
                        <div class="mt-5 space-y-3">
                            @forelse($pathway['recommendations'][$type] as $recommendation)
                                @php($item = $recommendation['item'])
                                <article class="border border-border bg-surface-soft p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-interactive">{{ $item->category?->name ?? $label }}</p>
                                    <h4 class="mt-1 text-base font-extrabold text-text-primary">{{ $type === 'community' ? $item->name : $item->title }}</h4>
                                    <p class="mt-1 text-xs text-text-secondary">@if($type === 'activity') Pendaftaran dibuka · {{ $item->start_at->translatedFormat('d M Y') }} @elseif($type === 'community') Community aktif @else Tersedia{{ $item->deadline_at ? ' · Batas '.$item->deadline_at->translatedFormat('d M Y') : '' }} @endif</p>
                                    <ul class="mt-3 space-y-1 border-t border-border pt-3 text-xs leading-5 text-text-secondary">@foreach($recommendation['reasons'] as $reason)<li>✓ {{ $reason }}</li>@endforeach</ul>
                                    <a class="btn-secondary mt-4 !min-h-9 !px-3 text-xs" href="{{ route($showRoute, $item) }}">Lihat {{ $label }}</a>
                                </article>
                            @empty
                                <p class="border border-dashed border-border p-4 text-sm leading-6 text-text-secondary">Belum ada {{ $label }} tersedia yang terkait dengan data profilmu saat ini.</p>
                            @endforelse
                        </div>
                        <a class="mt-5 inline-block text-sm font-bold text-interactive underline" href="{{ route($indexRoute) }}">Jelajahi semua {{ $label }}</a>
                    </section>
                @endforeach
            </div>
        @endunless
    </section>

    <section class="sipora-card p-5 sm:p-7" aria-labelledby="pathway-history">
        <p class="text-xs font-extrabold uppercase tracking-[.15em] text-interactive">04 · Riwayat nyata</p>
        <h2 id="pathway-history" class="mt-2 font-heading text-2xl font-bold text-text-primary">Riwayat Pengembangan</h2>
        <p class="mt-2 text-sm text-text-secondary">Diambil dari Activity Passport yang sudah selesai. Sertifikat ditampilkan hanya jika benar-benar diterbitkan.</p>
        @if($pathway['activeParticipations']->isNotEmpty())
            <h3 class="mt-5 font-bold text-text-primary">Sedang diikuti</h3>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">@foreach($pathway['activeParticipations'] as $entry)<div class="border border-border bg-surface-soft p-4"><p class="text-xs font-bold uppercase tracking-wider text-interactive">Partisipasi diterima</p><p class="mt-1 font-bold text-text-primary">{{ $entry->activity->title }}</p><p class="mt-1 text-xs text-text-secondary">{{ $entry->activity->organization?->name }}</p></div>@endforeach</div>
        @endif
        @if($pathway['joinedCommunities']->isNotEmpty())
            <h3 class="mt-5 font-bold text-text-primary">Community yang diikuti</h3>
            <div class="mt-3 flex flex-wrap gap-2">@foreach($pathway['joinedCommunities'] as $membership)<span class="border border-border bg-surface-soft px-3 py-2 text-sm font-semibold text-text-primary">{{ $membership->organization?->name }}</span>@endforeach</div>
        @endif
        <div class="mt-5 space-y-3">
            @forelse($pathway['history'] as $entry)
                <article class="flex flex-wrap items-center justify-between gap-4 border border-border bg-surface-soft p-4"><div><p class="text-xs font-bold uppercase tracking-wider text-interactive">{{ $entry->activity->category?->name ?? 'Activity' }} · {{ $entry->completed_at?->translatedFormat('d M Y') }}</p><h3 class="mt-1 font-bold text-text-primary">{{ $entry->activity->title }}</h3><p class="mt-1 text-sm text-text-secondary">{{ $entry->activity->organization?->name }} · {{ $entry->present_count }} dari {{ $entry->activity->sessions_count }} sesi hadir</p></div><div class="flex flex-wrap gap-2"><a class="btn-secondary !min-h-9 !px-3 text-xs" href="{{ route('youth.passport.show', $entry) }}">Passport</a>@if($entry->certificate)<a class="btn-secondary !min-h-9 !px-3 text-xs" href="{{ route('youth.certificates.show', $entry->certificate) }}">Sertifikat</a>@endif</div></article>
            @empty
                <p class="border border-dashed border-border p-4 text-sm text-text-secondary">Belum ada Activity selesai di Passport-mu.</p>
            @endforelse
        </div>
        @if($pathway['completedCount'] > count($pathway['history']))<a class="mt-5 inline-block text-sm font-bold text-interactive underline" href="{{ route('youth.passport.index') }}">Lihat seluruh riwayat Passport</a>@endif
    </section>
</div>
@endsection
