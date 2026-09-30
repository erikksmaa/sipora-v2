@extends('layouts.public')
@section('title', 'Akses tidak tersedia · SIPORA')
@section('meta_description', 'Akses ke halaman SIPORA ini tidak tersedia untuk akun Anda.')
@section('content')
<section class="grid min-h-[65vh] place-items-center bg-[#f5f6fc] px-5 py-16 text-center">
    <div class="max-w-xl"><p class="text-sm font-black uppercase tracking-[.2em] text-orange-600">403</p><h1 class="mt-3 text-4xl font-black text-[#18245c]">Akses tidak tersedia</h1><p class="mt-4 leading-7 text-slate-600">Akun Anda tidak memiliki izin untuk membuka halaman ini.</p><div class="mt-8 flex flex-wrap justify-center gap-3"><a class="landing-btn-primary" href="{{ route('home') }}">Kembali ke beranda</a>@auth<a class="landing-btn-secondary" href="{{ route(\App\Support\Auth\HomeRoute::for(auth()->user())) }}">Buka ruang saya</a>@else<a class="landing-btn-secondary" href="{{ route('login') }}">Masuk</a>@endauth</div></div>
</section>
@endsection
