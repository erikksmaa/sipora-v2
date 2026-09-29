@extends('layouts.manager')
@section('title', 'Edit Program')
@section('manager-content')
<p class="text-sm font-bold text-orange-600">EDIT RENCANA</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">{{ $program->title }}</h1><p class="mt-2 text-slate-600">Perbarui informasi inti sebelum alur Proposal dimulai.</p>
@include('manager.programs._form')
@endsection
