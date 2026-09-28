@extends('layouts.youth')
@section('title', 'Buat Pengajuan Komunitas · SIPORA')
@section('content')
<div class="mx-auto max-w-4xl"><div class="mb-6"><a class="text-sm font-bold text-[#243378]" href="{{ route('youth.communities.index') }}">← Kembali ke pengajuan</a><h1 class="mt-3 text-3xl font-extrabold text-[#243378]">Buat draft komunitas</h1><p class="mt-2 text-slate-600">Simpan informasi secara bertahap. Kamu dapat meninjaunya sebelum dikirim.</p></div>@include('youth.communities._form')</div>
@endsection
