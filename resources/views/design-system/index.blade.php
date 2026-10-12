@php
    extract(require resource_path('views/design-system/data.php'));
    $preview = in_array(request('preview'), ['navbar', 'components', 'dashboard', 'landing', 'statistics', 'map'], true) ? request('preview') : null;
    $theme = request('theme') === 'dark' ? 'dark' : 'light';
    $capture = request('capture') === '1';
    $onlySection = $capture && ctype_digit((string) request('section')) ? (int) request('section') : null;
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPORA · Visual Design System</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Pixelify+Sans:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/statistics.css', 'resources/css/ecosystem-map.css', 'resources/css/design-system.css', 'resources/js/design-system.js'])
</head>
<body class="{{ $preview ? 'ds-preview' : 'ds-board' }} {{ $capture ? 'ds-capture' : '' }}">
@if($preview)
    @include('design-system.preview')
@else
    <a href="#main" class="sr-only focus:not-sr-only">Lewati ke board</a>
    <div class="ds-toolbar" aria-label="Kontrol dokumentasi">
        <strong>SIPORA <span class="font-normal text-text-muted">/ Visual Design System</span></strong>
        <label class="text-xs font-semibold" for="ds-jump">Section</label>
        <select id="ds-jump" class="field !w-auto !min-h-9 !py-1" aria-label="Lompat ke section">@foreach($sections as $title)<option value="section-{{ $loop->iteration }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ $title }}</option>@endforeach</select>
        <button type="button" class="btn-secondary btn-sm" id="ds-theme" aria-pressed="{{ $theme === 'dark' ? 'true' : 'false' }}">{{ $theme === 'dark' ? 'Light mode' : 'Dark mode' }}</button>
        <button type="button" class="btn-ghost btn-sm" id="ds-pause" aria-pressed="false">Hentikan animasi</button>
        <a href="{{ route('design-system', ['capture' => 1, 'theme' => $theme]) }}" class="btn-accent btn-sm" id="ds-capture-all">Capture board</a>
    </div>
    <div class="ds-shell">
        <nav class="ds-index" aria-label="Daftar section">@foreach($sections as $title)<a href="#section-{{ $loop->iteration }}"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $title }}</a>@endforeach</nav>
        <main class="ds-main" id="main">
        @foreach($sections as $title)
            @php($number = $loop->iteration)
            @continue($onlySection && $onlySection !== $number)
            <section id="section-{{ $number }}" class="ds-section" aria-labelledby="heading-{{ $number }}">
                <header class="ds-section-head">
                    <span class="ds-number">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }} / 33</span>
                    <div><h2 id="heading-{{ $number }}">{{ $title }}</h2><p>{{ match($number) {
                        1 => 'Satu bahasa visual untuk ruang tumbuh pemuda Pemalang.',
                        2 => 'Token aktual dari app.css. Accent = Flame; primary = Slate. Nama peran pada brief tidak mengganti nama token proyek.',
                        3 => 'Hierarki yang diamati dari class aktual, bukan skala typography baru. Ukuran ditulis font-size / line-height dalam px pada basis 16px.',
                        4 => 'Ukuran diturunkan dari utilitas Tailwind dan layout yang digunakan saat ini.',
                        5 => 'Selective radius pada implementasi saat ini: beberapa token radius berbeda nama memiliki nilai yang sama.',
                        6 => 'SVG inline dari x-ui.icon. Nama di bawah adalah key yang tersedia, bukan daftar ikon baru.',
                        7 => 'Dekorasi pixel digunakan secara kontekstual: hero, pembatas, empty state, dan public experience.',
                        8 => 'Class aktual dengan ukuran, konten, dan state. Sampel hover / pressed / focus dibekukan hanya untuk dokumentasi.',
                        9 => 'Kontrol yang benar-benar ditemukan di form SIPORA. Semua isian di board ini adalah demo lokal.',
                        10 => 'Badge umum dan domain dipertahankan sebagai varian. Status selalu memiliki label teks.',
                        11 => 'Feedback ringan melalui toast; konfirmasi tindakan melalui SweetAlert2. Preview tidak melakukan perubahan data.',
                        12 => 'Komponen Blade aktual dengan fixture dalam memori. Semua angka dan identitas merupakan contoh dokumentasi.',
                        13 => 'Navbar aktual dalam viewport terpisah, ditambah komposisi sidebar dari class workspace saat ini.',
                        14 => 'Menu, collapsible group, dan details yang ditemukan. Tidak menambahkan pola menu baru ke aplikasi.',
                        15 => 'Pola filter aktual; submit demo ditahan di board dan tidak memanggil workflow aplikasi.',
                        16 => 'Class table-shell, status domain, action, empty state, dan horizontal overflow.',
                        17 => 'SweetAlert2 memakai styling konfirmasi SIPORA. Semua dialog yang dibuka hanya demo.',
                        18,19,20,21 => 'Mini komposisi berdasarkan struktur view saat ini. Bukan screenshot historis, bukan halaman aplikasi penuh.',
                        22 => 'Chart.js memakai renderer statistics.js aktual dengan dataset contoh. Tooltip muncul saat hover titik atau bar.',
                        23 => 'Kontrol peta aktual; latar geometri hanya ilustrasi dokumentasi, bukan batas wilayah resmi.',
                        24 => 'Komposisi forum dari view aktual: kategori, topik, balasan, helpful, report, dan status terkunci.',
                        25 => 'Komposisi post dan action dari Social Feed. Tombol tidak menulis post, like, komentar, atau follow.',
                        26 => 'Iframe memakai html[data-theme] aktual agar selector dark mode bekerja tanpa mengganti CSS aplikasi.',
                        27 => 'Viewport CSS nyata di iframe: desktop 1280px, tablet 768px, dan mobile 375px.',
                        28 => 'Perbandingan state visual dan semantik. Fokus keyboard tetap dapat dicoba pada komponen interaktif.',
                        29 => 'Transisi ringan yang ditemukan di CSS dan Alpine. Reduced motion telah tersedia; pause di board hanya untuk dokumentasi.',
                        30 => 'Dokumentasi implementasi yang ada dan gap yang belum tersedia. Ini bukan klaim audit aksesibilitas penuh.',
                        31 => 'Arah visual yang diminta: 80% modern UI, 20% pixel accents. Rasio ini pedoman, bukan hasil pengukuran.',
                        32 => 'Rekonstruksi wireframe dari struktur desain final. Tidak diklaim sebagai sketsa awal historis.',
                        default => 'Section dapat dibuka sendiri untuk capture dengan heading dan ruang kosong yang cukup.',
                    } }}</p></div>
                    <a class="ds-tag ds-capture-link" href="{{ route('design-system', ['capture' => 1, 'section' => $number, 'theme' => $theme]) }}">CAPTURE {{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</a>
                </header>
                @if($number <= 7) @include('design-system.foundations')
                @elseif($number <= 17) @include('design-system.components')
                @elseif($number <= 21) @include('design-system.patterns')
                @elseif($number <= 25) @include('design-system.domains')
                @else @include('design-system.documentation') @endif
            </section>
        @endforeach
        <p class="ds-caption">SIPORA · Visual Design System · Inventaris implementasi 11 Oktober 2026 · Local documentation · Data contoh</p>
        </main>
    </div>
@endif
</body>
</html>
