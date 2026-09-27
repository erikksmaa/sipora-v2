@extends('layouts.youth')
@section('title', 'Edit Profil · SIPORA')
@section('content')
<div class="mx-auto max-w-4xl"><div class="mb-6"><a class="text-sm font-bold text-[#243378]" href="{{ route('youth.profile.show') }}">← Kembali ke profil</a><h1 class="mt-3 text-3xl font-extrabold">Edit profil</h1><p class="mt-2 text-slate-600">Informasi kontak dan data lahir tetap privat.</p></div><section class="sipora-card"><x-youth.profile-form :user="$user" :action="route('youth.profile.update')" /></section></div>
@endsection
