<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'SIPORA menghubungkan pemuda Pemalang dengan Activity, Community, Opportunity, Program, dan Youth Portfolio.')">
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
    <title>@yield('title', 'SIPORA — Ruang Tumbuh Pemuda Pemalang')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <x-theme-init />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600&family=Pixelify+Sans:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <style>
        .auth-shell { min-height: 100vh; min-height: 100dvh; display: flex; flex-direction: column; }
        .auth-main { width: 100%; flex: 1; display: flex; align-items: center; justify-content: center; padding: 32px 20px 48px; }
        .auth-main > .auth-card { width: 100%; margin: 0 auto; }
    </style>
</head>
<body class="auth-shell bg-bg text-text-primary antialiased">
    <a href="#main" class="sr-only focus:not-sr-only">Lewati ke konten</a>
    <x-public.navbar />
    <main id="main" tabindex="-1" class="auth-main">@yield('content')</main>
    @stack('scripts')
    <x-alerts />
</body>
</html>
