@extends('layouts.public')
@section('title', 'Filter tidak valid · SIPORA')
@section('content')
<section class="mx-auto max-w-3xl px-5 py-16">
    <p class="text-sm font-semibold text-text-muted">422 · Filter tidak valid</p>
    <h1 class="mt-3 text-2xl font-bold text-primary">Periksa format pencarian</h1>
    <p class="mt-4 text-text-secondary">Format parameter pencarian atau filter tidak valid. Gunakan satu nilai untuk setiap filter.</p>
    <a href="{{ $resetUrl }}" class="btn-primary mt-6 inline-flex">Hapus filter dan coba lagi</a>
</section>
@endsection
