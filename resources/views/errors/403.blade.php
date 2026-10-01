@extends('layouts.public')
@section('title', 'Akses tidak tersedia · SIPORA')
@section('meta_description', 'Akses ke halaman SIPORA ini tidak tersedia untuk akun Anda.')
@section('content')
<section class="grid min-h-[65vh] place-items-center bg-surface-muted px-5 py-16 text-center"><div class="max-w-xl"><span class="mx-auto grid size-16 place-items-center rounded-2xl bg-orange-50 text-orange-700"><x-ui.icon name="shield-check" class="size-8" /></span><p class="mt-5 font-mono text-sm font-bold text-orange-600">403</p><h1 class="font-heading mt-2 text-4xl font-bold text-text-primary">Akses tidak tersedia</h1><p class="mt-4 leading-7 text-slate-600">Akun Anda tidak memiliki izin untuk membuka halaman ini.</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a class="landing-btn-primary" href="{{ route('home') }}">Kembali ke beranda</a>@auth<a class="btn-secondary" href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}">Buka ruang saya</a>@else<a class="btn-secondary" href="{{ route('login') }}">Masuk</a>@endauth</div></div></section>
@endsection
