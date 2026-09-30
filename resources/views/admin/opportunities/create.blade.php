@extends('layouts.admin')
@section('title', 'Tambah Opportunity · Admin SIPORA')
@section('content')
<div class="mx-auto max-w-5xl"><p class="text-xs font-black uppercase tracking-wider text-orange-600">Opportunity Hub</p><h1 class="mt-1 text-3xl font-black text-[#18245c]">Tambah Opportunity</h1><form class="mt-7 rounded-3xl bg-white p-6 shadow-sm sm:p-8" method="POST" action="{{ route('admin.opportunities.store') }}">@csrf @include('admin.opportunities._form')</form></div>
@endsection
