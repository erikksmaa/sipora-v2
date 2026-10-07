@extends('layouts.public')

@section('title', 'SIPORA — Temukan Ruang untuk Berkembang')

@section('content')
@php($primaryCta = auth()->check() ? route(\App\Support\Auth\HomeRoute::for(auth()->user())) : route('register'))
<section id="beranda"
    class="relative overflow-hidden bg-primary-800 text-white">
    <div class="pointer-events-none absolute inset-0"
        style="opacity: 0.032; background-size: 64px 64px; background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px)">
    </div>
    <div
        class="mx-auto grid max-w-7xl items-center gap-12 px-5 pb-20 pt-12 lg:grid-cols-[1.05fr_.95fr] lg:pb-24 lg:pt-16">
        <div class="relative z-10">
            <span class="px-icon px-bird absolute -top-4 right-8 hidden text-accent-300/70 sm:block" aria-hidden="true"></span>
            <span
                class="font-display-accent inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.08em] text-white/90 shadow-xs"><span
                    class="size-2 rounded-full bg-accent"></span> Platform kepemudaan Kabupaten Pemalang</span>
            <h1
                class="font-heading mt-6 max-w-2xl text-[3rem] font-bold leading-[0.98] tracking-[-0.025em] text-white sm:text-[3.5rem] lg:text-[3.85rem]">
                Temukan ruang untuk <span class="relative text-accent-300">berkembang.<svg
                        class="absolute -bottom-2 left-0 w-full text-accent" viewBox="0 0 300 14" fill="none"
                        aria-hidden="true">
                        <path d="M3 10C81 1 190 2 297 8" stroke="currentColor" stroke-width="6"
                            stroke-linecap="round" />
                    </svg></span></h1>
            <p class="mt-6 max-w-xl text-base leading-7 text-white/85 sm:text-lg">Jelajahi kegiatan, bergabung dengan
                komunitas, temukan peluang, dan bangun portofolio terverifikasi bersama ekosistem pemuda Pemalang.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row"><a href="{{ route('activities.index') }}"
                    class="landing-btn-primary">Jelajahi Activity <x-ui.icon name="chevron-right"
                        class="size-4" /></a><a href="{{ route('communities.index') }}"
                    class="landing-btn-secondary !border-white/30 !bg-white/10 !text-white hover:!bg-white/20">Temukan
                    Community</a></div>
            <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-3 text-sm text-white/85"><span
                    class="font-bold text-white">Terbuka untuk pemuda Pemalang</span><span
                    class="inline-flex items-center gap-1.5"><x-ui.icon name="check" class="size-4 text-accent-300" />
                    Gratis untuk bergabung</span><span class="inline-flex items-center gap-1.5"><x-ui.icon
                        name="shield-check" class="size-4 text-accent-300" /> Activity melalui verifikasi</span></div>
        </div>
        <div class="landing-adventure-frame mx-auto w-full max-w-lg lg:mx-0">
            <div class="landing-adventure-scene" role="img"
                aria-label="Ilustrasi petualangan pixel: pemuda menjelajahi peta, komunitas, dan rekam pengalaman yang dapat diverifikasi">
                <span class="landing-adventure-label" aria-hidden="true">Peta Petualangan Pemuda</span>
                <span class="landing-adventure-sun" aria-hidden="true"></span>
                <span class="landing-adventure-cloud landing-adventure-cloud--one" aria-hidden="true"></span>
                <span class="landing-adventure-cloud landing-adventure-cloud--two" aria-hidden="true"></span>
                <span class="landing-adventure-hill landing-adventure-hill--back" aria-hidden="true"></span>
                <span class="landing-adventure-hill landing-adventure-hill--front" aria-hidden="true"></span>
                <span class="landing-adventure-route" aria-hidden="true"></span>
                <span class="px-sprite px-castle landing-adventure-castle" aria-hidden="true"></span>
                <span class="px-sprite px-pine landing-adventure-pine landing-adventure-pine--one" aria-hidden="true"></span>
                <span class="px-sprite px-pine landing-adventure-pine landing-adventure-pine--two" aria-hidden="true"></span>
                <span class="px-sprite px-knight landing-adventure-knight" aria-hidden="true"></span>
                <span class="px-sprite px-chest landing-adventure-chest" aria-hidden="true"></span>
                <span class="px-sprite px-map landing-adventure-map" aria-hidden="true"></span>
                <span class="landing-adventure-ground" aria-hidden="true"></span>
            </div>
            <div class="landing-adventure-proof">
                <span class="landing-adventure-proof-icon"><x-ui.icon name="badge-check" class="size-5" /></span>
                <div class="min-w-0"><strong class="block text-sm font-bold text-text-primary">Rekam yang dapat diverifikasi</strong><span
                        class="text-xs text-text-secondary">Sertifikat SIPORA memiliki kode verifikasi publik</span></div>
            </div>
        </div>
    </div>
    <div class="landing-adventure-edge" aria-hidden="true"></div>
</section>

<section class="relative z-20 -mt-9 px-5" aria-label="Pencarian publik">
    <form method="GET" action="{{ route('search.index') }}"
        class="mx-auto max-w-[1140px] rounded-lg border-2 border-primary-700/75 bg-white p-5 shadow-md sm:p-6">
        <div class="grid gap-3 lg:grid-cols-[1fr_auto]">
            <label class="relative"><span class="sr-only">Cari Activity, Community, Opportunity, atau
                    Program</span><x-ui.icon name="search"
                    class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-text-muted" /><input
                    type="search" name="q" maxlength="120" class="landing-search h-12 pl-11"
                    placeholder="Cari Activity, Community, Opportunity, atau Program..."></label>
            <button type="submit" class="landing-btn-primary h-12">Cari Sekarang</button>
        </div>
        <div class="mt-3.5 flex flex-wrap items-center gap-2 text-xs"><span
                class="font-semibold text-text-secondary">Pencarian
                populer:</span>@foreach (['Pelatihan', 'Volunteer', 'Beasiswa', 'Komunitas olahraga'] as $term)<a
                    href="{{ route('search.index', ['q' => $term]) }}"
                class="inline-flex h-8 items-center rounded-full border border-border bg-surface-soft px-3 font-semibold text-text-secondary transition hover:border-primary-300 hover:bg-primary-50 hover:text-primary">{{ $term }}</a>@endforeach
        </div>
    </form>
</section>

<section class="landing-section-scene px-section-paper px-zig-left px-5 pb-20 pt-24">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Jelajahi Minat" title="Eksplorasi Sesuai Minatmu"
            description="Mulai dari hal yang kamu sukai dan temukan ruang bertumbuh bersama pemuda lainnya." centered />
        @php($interestIcons = ['code', 'activity', 'briefcase', 'palette', 'graduation-cap', 'leaf', 'users', 'trophy'])
        <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-8">
            @foreach ($interests as $index => $interest)
                <article
                    class="group rounded-2xl border border-border bg-white p-5 text-center shadow-xs transition duration-200 hover:-translate-y-1 hover:border-primary-200 hover:shadow-sm">
                    <span
                        class="mx-auto grid size-12 place-items-center rounded-xl bg-primary-50 text-primary transition group-hover:scale-105"><x-ui.icon
                            :name="$interestIcons[$index % count($interestIcons)]" class="size-5" /></span>
                    <h3 class="mt-3.5 text-sm font-bold text-text-primary">{{ $interest['name'] }}</h3>
            </article>@endforeach
        </div>
    </div>
</section>

<section id="kegiatan" class="landing-section-scene px-section-white px-zig-right px-5 py-20">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end"><x-public.section-heading
                eyebrow="Terjadwal Berikutnya" title="Kegiatan yang Bisa Kamu Ikuti"
                description="Activity publik diurutkan berdasarkan jadwal terdekat." /><a
                href="{{ route('activities.index') }}" class="public-link">Lihat semua Activity <x-ui.icon
                    name="chevron-right" class="size-4" /></a></div>
        <div class="mt-10 grid gap-7 lg:grid-cols-3">@forelse ($activities as $activity)<x-discovery.activity-card
        :activity="$activity" />@empty<p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Activity publik yang akan datang.</p>@endforelse</div>
    </div>
</section>

<section id="komunitas" class="landing-section-scene px-section-paper px-zig-left px-5 py-20">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Tumbuh Bersama" title="Temukan Komunitas"
            description="Terhubung dengan orang-orang yang berbagi minat dan tujuan yang sama." centered />
        <div class="mt-10 grid gap-6 lg:grid-cols-3">@forelse ($communities as $community)<x-discovery.community-card
        :community="$community" />@empty<p
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-text-secondary">
                Belum ada Community aktif yang dapat ditampilkan.</p>@endforelse</div>
        <div class="mt-8 text-center"><a href="{{ route('communities.index') }}" class="public-link">Lihat semua
                Community <x-ui.icon name="chevron-right" class="size-4" /></a></div>
    </div>
</section>

<section id="peluang" class="landing-section-scene px-section-white px-zig-right px-5 py-20">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end"><x-public.section-heading
                eyebrow="Buka Jalanmu" title="Opportunity untuk Pemuda"
                description="Akses beasiswa, kerelawanan, magang, dan program pengembangan yang dikurasi Admin SIPORA." /><a
                class="public-link" href="{{ route('opportunities.index') }}">Lihat semua Opportunity <x-ui.icon
                    name="chevron-right" class="size-4" /></a></div>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            @forelse ($opportunities as $opportunity)<x-discovery.opportunity-card :opportunity="$opportunity" />@empty
                <div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
            Belum ada Opportunity yang dipublikasikan.</div>@endforelse</div>
    </div>
</section>

<section id="program" class="landing-section-scene px-section-paper px-zig-left px-5 py-20">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Program Pemuda"
            title="Program yang Sedang Berjalan dan Selesai"
            description="Program publik berasal dari Community aktif dan memiliki Proposal yang telah disetujui."
            centered />
        <div class="mt-10 grid gap-6 lg:grid-cols-3">@forelse ($programs as $program)<x-discovery.program-card
        :program="$program" />@empty<div
                    class="col-span-full rounded-2xl border-2 border-dashed border-border p-10 text-center text-sm text-text-secondary">
                Belum ada Program yang memenuhi kriteria publik.</div>@endforelse</div>
        <div class="mt-8 text-center"><a class="public-link" href="{{ route('programs.index') }}">Lihat semua Program
                <x-ui.icon name="chevron-right" class="size-4" /></a></div>
    </div>
</section>

<section class="px-section-white px-zig-right px-5 py-20">
    <div class="mx-auto max-w-7xl"><x-public.section-heading eyebrow="Cara Kerja SIPORA"
            title="Dari menemukan ruang hingga membangun rekam jejak"
            description="SIPORA menghubungkan pencarian kegiatan, partisipasi, verifikasi, dan Portfolio dalam satu alur yang jelas."
            centered />
        <div class="mt-10 grid gap-5 md:grid-cols-3">
            @foreach([['search', 'Temukan', 'Jelajahi Activity, Community, Opportunity, dan Program yang tersedia.'], ['users', 'Berpartisipasi', 'Daftar dan berkontribusi melalui ekosistem Community yang terverifikasi.'], ['badge-check', 'Bangun rekam jejak', 'Aktivitas selesai dan sertifikat resmi memperkaya Youth Portfolio.']] as $index => [$icon, $title, $description])
                <article
                    class="relative rounded-2xl border border-border bg-white p-6 shadow-xs transition hover:shadow-sm">
                    <span
                        class="absolute right-5 top-5 font-mono text-sm font-bold text-text-muted">0{{ $index + 1 }}</span><span
                        class="grid size-12 place-items-center rounded-xl {{ $index === 1 ? 'bg-accent-50 text-accent-700' : ($index === 2 ? 'bg-success-soft text-success' : 'bg-primary-50 text-primary') }}"><x-ui.icon
                            :name="$icon" class="size-5" /></span>
                    <h3 class="mt-5 text-lg font-bold text-text-primary">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-text-secondary">{{ $description }}</p>
            </article>@endforeach
        </div>
    </div>
</section>

<div class="px-divider-loot bg-primary-900" aria-hidden="true"></div>
<section class="landing-dark-panel bg-primary-900 px-5 py-16 text-white">
    <div class="mx-auto grid max-w-7xl items-center gap-8 lg:grid-cols-[1fr_auto]">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-accent-300">Ekosistem terverifikasi</p>
            <h2 class="font-heading mt-2 text-3xl font-bold sm:text-4xl">Peran yang jelas menjaga kepercayaan.</h2>
            <p class="mt-4 max-w-3xl leading-7 text-white/85">Admin memverifikasi identitas pemuda, sedangkan Verifier
                meninjau Community, Activity, dan alur Program. Setiap rekam jejak publik tetap mengikuti status dan
                privasi yang berlaku.</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            @foreach([['shield-check', 'Identitas'], ['building', 'Community'], ['activity', 'Activity'], ['notebook', 'Program']] as [$icon, $label])
                <div
                    class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-bold">
            <x-ui.icon :name="$icon" class="size-4 text-accent-300" />{{ $label }}</div>@endforeach
        </div>
    </div>
</section>

<section id="tentang" class="overflow-hidden px-5 py-20">
    <div class="mx-auto grid max-w-7xl items-center gap-14 lg:grid-cols-2">
        <div class="relative mx-auto w-full max-w-lg">
            <div class="absolute -inset-4 rotate-2 rounded-[2rem] bg-primary-50"></div>
            <div class="relative rounded-2xl border border-border bg-white p-7 shadow-md">
                <p class="text-xs font-bold uppercase tracking-wider text-accent-600">Ilustrasi struktur Portfolio</p>
                <div class="mt-4 flex items-center gap-4 border-b border-border pb-5"><span
                        class="grid size-14 place-items-center rounded-2xl bg-primary text-base font-black text-white shadow-xs">YP</span>
                    <div>
                        <h3 class="text-lg font-bold text-text-primary">Youth Portfolio</h3>
                        <p class="text-xs text-text-secondary">Dikendalikan oleh pemilik profil</p>
                    </div>
                </div>
                <div class="mt-5 space-y-2.5">
                    @foreach (['Activity yang diselesaikan', 'Keanggotaan Community aktif', 'Sertifikat SIPORA terverifikasi'] as $item)
                        <div class="flex items-center gap-3 rounded-xl bg-surface-soft p-3"><span
                                class="grid size-8 place-items-center rounded-lg bg-white text-success shadow-xs"><x-ui.icon
                                    name="check" class="size-4" /></span><span
                    class="text-sm font-semibold text-text-primary">{{ $item }}</span></div>@endforeach
                </div>
                <p class="mt-4 text-xs leading-5 text-text-muted">Tampilan ini menjelaskan struktur fitur dan bukan
                    Portfolio atau testimoni pengguna tertentu.</p>
            </div>
        </div>
        <div><x-public.section-heading eyebrow="Youth Portfolio" title="Bangun Portofolio Terverifikasi Sejak Dini"
                description="Catat perjalananmu, kumpulkan bukti pengalaman, dan tampilkan kontribusi nyata dalam satu profil yang terpercaya." />
            <div class="mt-7 space-y-4">
                @foreach ([['Riwayat aktivitas yang tercatat', 'Setiap partisipasi terhubung dengan penyelenggara dan proses verifikasi.'], ['Sertifikat digital terintegrasi', 'Simpan capaianmu dalam portofolio yang mudah diakses.'], ['Kendali privasi tetap milikmu', 'Tentukan informasi yang ingin kamu tampilkan kepada publik.']] as [$title, $description])
                    <div class="flex gap-3.5"><span
                            class="grid size-9 shrink-0 place-items-center rounded-full bg-accent-50 text-accent-600"><x-ui.icon
                                name="check" class="size-4" /></span>
                        <div>
                            <h3 class="font-bold text-text-primary">{{ $title }}</h3>
                            <p class="mt-0.5 text-sm leading-6 text-text-secondary">{{ $description }}</p>
                        </div>
                </div>@endforeach
            </div><a href="{{ $primaryCta }}"
                class="landing-btn-primary mt-8">{{ auth()->check() ? 'Buka Youth Home' : 'Mulai Bangun Portofolio' }}</a>
        </div>
    </div>
</section>

<section class="bg-surface-soft px-5 py-16" aria-label="Statistik publik SIPORA">
    <div class="mx-auto max-w-7xl">
        <p class="text-center text-xs font-bold uppercase tracking-[0.18em] text-accent-600">Statistik publik SIPORA</p>
        <h2 class="font-heading mt-2 text-center text-3xl font-bold text-text-primary">Ekosistem SIPORA dalam Angka</h2>
        <p class="mt-3 text-center text-sm text-text-secondary">Berdasarkan pemuda yang terdaftar di SIPORA, bukan seluruh populasi pemuda Kabupaten Pemalang.</p>
        <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach([['registered', 'Pemuda Terdaftar', 'users'], ['identity_verified', 'Identitas Terverifikasi', 'shield-check'], ['public_portfolio', 'Portfolio Publik', 'user'], ['activity_participated', 'Pernah Mengikuti Activity', 'activity']] as [$key, $label, $icon])
                <x-public.stat-card :icon="$icon" :value="$youthStatistics[$key] === null ? '<5' : number_format($youthStatistics[$key])" :label="$label" tone="blue" />
            @endforeach
        </div>
        <div class="mt-8 text-center"><a href="{{ route('statistics.index') }}" class="btn-secondary">Lihat Statistik Pemuda</a></div>
    </div>
</section>

<section class="landing-dark-panel relative overflow-hidden bg-primary-800 px-5 py-20 text-center text-white">
    <div class="relative mx-auto max-w-3xl">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-accent-300">Giliranmu untuk bertumbuh</p>
        <h2 class="font-heading mt-3 text-4xl font-bold tracking-tight sm:text-5xl">Ambil langkah pertamamu bersama SIPORA.</h2>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-white/85 sm:text-lg">Bergabung dengan ekosistem pemuda
            Pemalang dan temukan lebih banyak cara untuk belajar, berkarya, dan berdampak.</p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row"><a href="{{ $primaryCta }}"
                class="landing-btn-primary">{{ auth()->check() ? 'Buka Youth Home' : 'Daftar Sebagai Pemuda' }}</a>@guest<a
                    href="{{ route('register') }}"
                    class="landing-btn-secondary !border-white/30 !bg-white/10 !text-white hover:!bg-white/20">Daftarkan
                Komunitas</a>@endguest</div>
    </div>
</section>
@endsection

@section('footer')<x-public.footer />@endsection
