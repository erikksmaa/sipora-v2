@extends('layouts.public')
@section('title', 'SIPORA — Perjalanan Pemuda Pemalang')
@push('head')
    @vite(['resources/css/ecosystem-map.css', 'resources/js/ecosystem-map.js'])
@endpush
@section('content')
@php
    $primaryCta = auth()->check() ? route(\App\Support\Auth\HomeRoute::for(auth()->user())) : route('register');
    $displayCount = fn ($count) => $count === null ? '<5' : number_format($count, 0, ',', '.');
@endphp
<div class="public-home">
<section id="beranda" class="home-hero">
    <div class="home-container grid items-center gap-10 lg:grid-cols-2">
        <div>
            <p class="home-eyebrow">Ekosistem pemuda Kabupaten Pemalang</p>
            <h1>Satu Platform untuk Perjalanan <span>Pemuda Pemalang</span></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-white/85">Temukan ruang untuk berkembang. SIPORA menghubungkan pemuda, Community, Activity, Opportunity, dan pemerintah dalam satu ekosistem.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="landing-btn-primary" href="{{ $primaryCta }}">{{ auth()->check() ? 'Ruang Saya' : 'Daftar Sekarang' }} <x-ui.icon name="chevron-right" class="size-4" /></a>
                <a class="landing-btn-secondary !border-white/40 !bg-white/10 !text-white" href="{{ route('activities.index') }}">Jelajahi Activity</a>
            </div>
            <p class="mt-6 text-sm text-white/75">Belajar, berkarya, dan berkontribusi bersama pemuda Pemalang.</p>
        </div>
        <figure class="home-hero-photo">
            <img src="{{ asset('media/demo/08A_SOCIAL_FEED/feed-002-workshop-recap.jpg') }}" width="1200" height="800" alt="Foto demo pemuda berkolaborasi dalam workshop pengembangan web" fetchpriority="high">
            <figcaption><x-ui.icon name="users" class="size-5" /><div><strong>Perjalanan dimulai bersama</strong><span>Temukan komunitas dan pengalaman baru · Foto demo SIPORA</span></div></figcaption>
        </figure>
    </div>
</section>
<section id="tentang" class="home-section">
    <div class="home-container grid items-center gap-10 lg:grid-cols-2">
        <div class="home-app-preview" aria-label="Preview struktur fitur SIPORA, bukan profil pengguna">
            <div class="flex items-center justify-between border-b border-border pb-4"><strong>SIPORA <span class="text-text-muted">/ Ruang Pemuda</span></strong><x-ui.icon name="shield-check" class="size-5" /></div>
            <h3 class="mt-6 text-2xl font-bold">Satu tempat untuk perjalananmu</h3>
            <p class="mt-2 text-sm text-text-secondary">Preview fitur yang tersedia di SIPORA</p>
            <div class="mt-6 grid grid-cols-2 gap-3">
                @foreach([['user', 'Portfolio', 'Pengalaman & bukti karya'], ['activity', 'Activity', 'Partisipasi & kehadiran'], ['notebook', 'Passport', 'Riwayat perjalanan nyata'], ['badge-check', 'Certificate', 'Capaian dari Activity']] as [$icon, $title, $copy])
                <div class="rounded-xl bg-surface-soft p-4"><x-ui.icon :name="$icon" class="size-6 text-interactive" /><strong class="mt-3 block">{{ $title }}</strong><p class="mt-1 text-xs leading-5 text-text-secondary">{{ $copy }}</p></div>
                @endforeach
            </div>
            <p class="mt-5 text-xs text-text-muted">Ilustrasi antarmuka; bukan data atau testimoni pengguna.</p>
        </div>
        <div><x-public.section-heading eyebrow="Kenali platform" title="Apa itu SIPORA?" />
            <p class="mt-5 leading-7 text-text-secondary">SIPORA adalah platform ekosistem kepemudaan Kabupaten Pemalang yang menghubungkan pemuda, komunitas, aktivitas, peluang pengembangan diri, dan pemerintah dalam satu sistem digital terintegrasi.</p>
            <p class="mt-4 leading-7 text-text-secondary">Melalui SIPORA, pemuda dapat menemukan kegiatan, membangun portofolio, bergabung dengan komunitas, mengikuti peluang pengembangan diri, serta membangun rekam perjalanan melalui Passport dan Certificate.</p>
            <p class="mt-4 text-sm leading-7 text-text-secondary">Komunitas dan pemerintah dapat mengelola aktivitas, program dan partisipasi, serta memperoleh insight melalui Youth Intelligence dan Ecosystem Maps. Status publikasi dan kendali privasi tetap berlaku.</p>
            <div class="mt-6 flex flex-wrap gap-3"><a class="btn-accent" href="{{ $primaryCta }}">{{ auth()->check() ? 'Ruang Saya' : 'Daftar Sekarang' }}</a><a class="btn-outline" href="{{ route('search.index') }}">Jelajahi SIPORA</a></div>
        </div>
    </div>
</section>
<section id="angka" class="home-section !pt-0">
    <div class="home-container"><h2 class="text-2xl font-bold">Ekosistem SIPORA dalam Angka</h2><p class="mt-2 text-sm text-text-secondary">Berdasarkan pemuda yang terdaftar dan record publik di SIPORA; bukan seluruh populasi pemuda Kabupaten Pemalang.</p>
        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-public.stat-card icon="users" :value="$displayCount($youthStatistics['registered'])" label="Pemuda Terdaftar" tone="blue" />
            @foreach(array_slice($ecosystemStatistics, 0, 3) as $index => $metric)
                <x-public.stat-card :icon="['building', 'activity', 'bookmark'][$index]" :value="$displayCount($metric['value'] > 0 && $metric['value'] < 5 ? null : $metric['value'])" :label="$metric['label']" tone="blue" />
            @endforeach
        </div>
    </div>
</section>
<section id="peta" class="home-section !pt-0">
    <div class="home-container"><div class="flex flex-wrap items-end justify-between gap-4"><x-public.section-heading eyebrow="Cakupan kecamatan" title="Peta Ekosistem Pemuda" description="Jelajahi sebaran Community, Activity, dan Program dari data kecamatan yang tercatat." /><a class="btn-outline" href="{{ route('ecosystem-map.index') }}">Lihat Peta Ekosistem</a></div>
        <div class="eco-map-page home-map-preview mt-6"><x-public.ecosystem-map-panel :coverage="$coverage" /></div>
        <p class="mt-3 text-xs leading-6 text-text-secondary">Data agregat tanpa lokasi individu. Jumlah 1–4 ditampilkan sebagai &lt;5. Batas kecamatan bersumber dari data BIG edisi 2022.</p>
    </div>
</section>
<section id="alur" class="home-section bg-surface-soft">
    <div class="home-container"><x-public.section-heading eyebrow="Mulai perjalananmu" title="Alur Pendaftaran SIPORA" description="Lima langkah untuk menemukan ruang belajar, berkarya, dan berkontribusi." />
        <ol class="home-steps mt-8">
            @foreach([['user', 'Daftar Akun', 'Buat akun SIPORA.'], ['mail', 'Verifikasi Email', 'Konfirmasi email untuk mengaktifkan akun.'], ['shield-check', 'Lengkapi Profil', 'Isi data dasar, minat dan skill. Ajukan verifikasi identitas sesuai tahapan akun.'], ['notebook', 'Bangun Portofolio', 'Tambahkan pengalaman, prestasi dan evidence jika ada.'], ['search', 'Jelajahi Ekosistem', 'Temukan Activity, Community dan Opportunity.']] as $index => [$icon, $title, $copy])
            <li><span class="home-step-number">{{ $index + 1 }}</span><div><x-ui.icon :name="$icon" class="mb-3 size-5 text-interactive" /><h3 class="font-bold">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-text-secondary">{{ $copy }}</p></div></li>
            @endforeach
        </ol>
        <p class="mt-6 text-xs leading-6 text-text-secondary">Pendaftaran akun dan verifikasi identitas adalah proses berbeda. Kelengkapan profil dan persetujuan verifikasi mengikuti aturan akses SIPORA; pengalaman dan prestasi tambahan bersifat opsional.</p>
    </div>
</section>
<section class="home-section !pb-0"><div class="home-container"><x-public.section-heading eyebrow="Temukan kesempatan" title="Jelajahi Ekosistem SIPORA" />
    <form method="GET" action="{{ route('search.index') }}" class="mt-6 flex flex-col gap-3 sm:flex-row"><label class="flex-1"><span class="sr-only">Cari Activity, Community, Opportunity atau Program</span><input class="field w-full" type="search" name="q" maxlength="120" placeholder="Cari Activity, Community, Opportunity atau Program..."></label><button class="btn-accent" type="submit">Cari Sekarang</button></form>
    <div class="mt-4 flex flex-wrap gap-2">@foreach($interests as $interest)<a class="status-badge" href="{{ route('search.index', ['q' => $interest['name']]) }}">{{ $interest['name'] }}</a>@endforeach</div>
</div></section>
<section id="kegiatan" class="landing-section-scene px-section-white px-zig-right px-5 py-10">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end"><x-public.section-heading
                eyebrow="Terjadwal Berikutnya" title="Kegiatan yang Bisa Kamu Ikuti"
                description="Activity publik diurutkan berdasarkan jadwal terdekat." /><a
                href="{{ route('activities.index') }}" class="btn-outline">Lihat semua Activity <x-ui.icon
                    name="chevron-right" class="size-4" /></a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">@forelse ($activities as $activity)<x-discovery.activity-card
        :activity="$activity" />@empty<p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Activity publik yang akan datang.</p>@endforelse</div>
    </div>
</section>
<section id="komunitas" class="landing-section-scene px-section-paper px-zig-left px-5 py-10">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Tumbuh Bersama" title="Temukan Komunitas"
            description="Terhubung dengan orang-orang yang berbagi minat dan tujuan yang sama." centered />
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">@forelse ($communities as $community)<x-discovery.community-card
        :community="$community" />@empty<p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Community aktif yang dapat ditampilkan.</p>@endforelse</div>
        <div class="mt-8 text-center"><a href="{{ route('communities.index') }}" class="btn-outline">Lihat semua
                Community <x-ui.icon name="chevron-right" class="size-4" /></a></div>
    </div>
</section>
<section id="peluang" class="landing-section-scene px-section-white px-zig-right px-5 py-10">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end"><x-public.section-heading
                eyebrow="Buka Jalanmu" title="Opportunity untuk Pemuda"
                description="Akses beasiswa, kerelawanan, magang, dan program pengembangan yang dikurasi Admin SIPORA." /><a
                class="btn-outline" href="{{ route('opportunities.index') }}">Lihat semua Opportunity <x-ui.icon
                    name="chevron-right" class="size-4" /></a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($opportunities as $opportunity)<x-discovery.opportunity-card :opportunity="$opportunity" />@empty
                <div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
            Belum ada Opportunity yang dipublikasikan.</div>@endforelse</div>
    </div>
</section>
<section id="program" class="landing-section-scene px-section-paper px-zig-left px-5 py-10">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Program Pemuda"
            title="Program yang Sedang Berjalan dan Selesai"
            description="Program publik berasal dari Community aktif dan memiliki Proposal yang telah disetujui."
            centered />
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">@forelse ($programs as $program)<x-discovery.program-card
        :program="$program" />@empty<div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
                Belum ada Program yang memenuhi kriteria publik.</div>@endforelse</div>
        <div class="mt-8 text-center"><a class="btn-outline" href="{{ route('programs.index') }}">Lihat semua Program
                <x-ui.icon name="chevron-right" class="size-4" /></a></div>
    </div>
</section>
<section id="intelligence" class="home-section bg-surface-soft">
    <div class="home-container"><div class="flex flex-wrap items-end justify-between gap-4"><x-public.section-heading eyebrow="Youth Intelligence" title="Kenali Potensi Pemuda SIPORA" description="Berdasarkan pemuda yang terdaftar di SIPORA, bukan seluruh populasi Kabupaten Pemalang." /><a class="btn-outline" href="{{ route('statistics.index') }}">Lihat Statistik Pemuda</a></div>
        <div class="mt-6 grid gap-5 md:grid-cols-2">
            @foreach(['interests' => 'Minat Pemuda', 'skills' => 'Keahlian Pemuda'] as $key => $title)
                @php($rows = array_slice($analytics[$key], 0, 5))
                @php($maximum = max([1, ...array_map(fn ($row) => $row['count'] ?? 0, $rows)]))
                <section class="home-analytics-card"><h3 class="text-lg font-bold">{{ $title }}</h3><p class="mt-1 text-xs text-text-muted">Jumlah pemuda dengan pilihan yang tercatat</p>
                    <ul class="mt-5 space-y-4">@forelse($rows as $row)<li><div class="mb-2 flex justify-between gap-3 text-sm"><span>{{ $row['label'] }}</span><strong>{{ $displayCount($row['count']) }}</strong></div><div class="home-bar-track" aria-hidden="true"><span @if($row['count'] !== null) style="width:{{ round(100 * $row['count'] / $maximum) }}%" @else class="home-bar-suppressed" @endif></span></div></li>@empty<li class="text-sm text-text-secondary">Belum ada pilihan yang tercatat.</li>@endforelse</ul>
                </section>
            @endforeach
        </div>
        <p class="mt-4 text-xs text-text-secondary">Bucket berjumlah kurang dari 5 tidak ditampilkan sebagai angka pasti atau panjang bar. Satu pemuda dapat memiliki beberapa minat dan skill.</p>
    </div>
</section>
<section id="interaksi" class="home-section">
    <div class="home-container"><x-public.section-heading eyebrow="Percakapan ekosistem" title="Diskusi dan Kabar Pemuda" description="Diskusi Forum dan pembaruan publik dari Social Feed." />
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div><div class="mb-4 flex items-center justify-between gap-3"><h3 class="text-lg font-bold">Forum terbaru</h3><a class="btn-outline" href="{{ route('forum.index') }}">Buka Forum</a></div><div class="space-y-4">
                @forelse($threads as $thread)<article class="home-highlight card-interactive"><div class="flex flex-wrap items-center gap-2"><x-forum.category-badge :category="$thread->category" /><x-public.author-identity :author="$thread->author" /></div><h4 class="mt-3 font-bold"><a class="card-link" href="{{ route('forum.show', $thread) }}">{{ $thread->title }}</a></h4><p class="mt-2 line-clamp-2 text-sm leading-6 text-text-secondary">{{ $thread->body }}</p></article>@empty<p class="home-highlight text-text-secondary">Belum ada diskusi publik.</p>@endforelse
            </div></div>
            <div><div class="mb-4 flex items-center justify-between gap-3"><h3 class="text-lg font-bold">Social Feed terbaru</h3><a class="btn-outline" href="{{ route('social.index') }}">Buka Feed</a></div><div class="space-y-4">
                @forelse($posts as $post)<article class="home-highlight card-interactive">@if($post->organization)<a class="card-independent font-bold" href="{{ route('communities.show', $post->organization) }}">{{ $post->organization->name }}</a>@else<x-public.author-identity :author="$post->author" />@endif<p class="mt-3 line-clamp-2 text-sm leading-6 text-text-secondary">{{ $post->body }}</p><a class="card-link mt-3 inline-flex text-sm font-bold" href="{{ route('social.show', $post) }}">Baca pembaruan <span class="sr-only">{{ $loop->iteration }}</span></a></article>@empty<p class="home-highlight text-text-secondary">Belum ada pembaruan publik.</p>@endforelse
            </div></div>
        </div>
    </div>
</section>
<section id="kontak" class="home-section bg-surface-soft">
    <div class="home-container grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
        <div><x-public.section-heading eyebrow="Layanan dan informasi" title="Terhubung dengan Dindikpora" /><p class="mt-4 leading-7 text-text-secondary">Informasi layanan SIPORA dan lokasi yang telah dikonfirmasi oleh pengelola proyek.</p>
            @foreach(['address' => 'Alamat', 'email' => 'Email', 'phone' => 'Telepon', 'service_hours' => 'Jam layanan'] as $key => $label)
                @if(config('sipora.contact.'.$key))<p class="mt-4 text-sm"><strong class="block">{{ $label }}</strong>{{ config('sipora.contact.'.$key) }}</p>@endif
            @endforeach
            <div class="mt-6 flex flex-wrap gap-3"><a class="btn-outline" href="{{ route('contact') }}">Informasi Kontak</a>@if(config('sipora.contact.maps_url'))<a class="btn-accent" href="{{ config('sipora.contact.maps_url') }}" target="_blank" rel="noopener noreferrer">Lihat di Google Maps</a>@endif</div>
            <p class="mt-4 text-xs leading-6 text-text-muted">Lokasi pada embed: Dinas Pendidikan dan Kebudayaan Kabupaten Pemalang. Alamat jalan dan kontak ditampilkan ketika dikonfigurasi.</p>
        </div>
        @if(config('sipora.contact.maps_embed_url'))<iframe class="home-contact-map" src="{{ config('sipora.contact.maps_embed_url') }}" title="Lokasi layanan di Kabupaten Pemalang" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>@endif
    </div>
</section>
</div>
<script id="eco-map-data" type="application/json">@json(['districts' => $coverage, 'admin' => false, 'threshold' => 5], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
@endsection
@section('footer')<x-public.footer />@endsection
