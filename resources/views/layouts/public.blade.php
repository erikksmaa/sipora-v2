<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="@yield('meta_description', 'SIPORA menghubungkan pemuda Pemalang dengan Activity, Community, Opportunity, Program, dan Youth Portfolio.')">
    @hasSection('canonical')
    <link rel="canonical" href="@yield('canonical')">@endif
    <title>@yield('title', 'SIPORA — Ruang Tumbuh Pemuda Pemalang')</title>
    <link rel="icon" type="image/png" href="{{ asset('sipora-growth-mark-v1.png') }}">
    <x-theme-init />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body data-public-motion class="mobile-scale min-h-screen bg-bg text-text-primary antialiased">
    <a href="#main"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:p-3">Lewati
        ke konten</a>
    <x-public.navbar />
    <main id="main" data-page-motion tabindex="-1">@yield('content')</main>
    @hasSection('footer')@yield('footer')@else<x-public.footer />@endif
    <x-back-to-top />
    <x-mobile-bottom-nav />
    <x-alerts />

    <!-- Accessibility Widget -->
    <script>
        window.ACCESSIBILITY_WIDGET_CONFIG = {
            lang: {
                accessibilityMenu: 'Menu Aksesibilitas',
                closeAccessibilityMenu: 'Tutup Menu Aksesibilitas',
                accessibilityTools: 'Alat Aksesibilitas',
                resetAllSettings: 'Atur Ulang Semua Pengaturan',
                screenReader: 'Pembaca Layar',
                textSpacing: 'Jarak Teks',
                pauseAnimations: 'Hentikan Animasi',
                hideImages: 'Sembunyikan Gambar',
                dyslexiaFriendly: 'Ramah Disleksia',
                biggerCursor: 'Perbesar Kursor',
                lineHeight: 'Tinggi Baris',
                textAlign: 'Perataan Teks',
                textSize: 'Ukuran Teks',
                highContrast: 'Kontras Tinggi',
                selectLanguage: 'Pilih Bahasa',
                reset: 'Atur Ulang',
                close: 'Tutup'
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/accessibility-widgets@latest/widget.js"></script>
</body>

</html>
