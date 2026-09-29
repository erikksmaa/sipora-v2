@extends('layouts.manager')
@section('title', 'Buat Program')
@section('manager-content')
<p class="text-sm font-bold text-orange-600">PROGRAM BARU</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">Buat Rencana Program</h1><p class="mt-2 max-w-2xl text-slate-600">Simpan identitas dan tujuan Program. Pengajuan Proposal belum tersedia pada fase ini.</p>
@include('manager.programs._form')
@endsection
