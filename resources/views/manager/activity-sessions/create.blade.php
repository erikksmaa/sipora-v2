@extends('layouts.manager')
@section('title','Tambah Sesi')
@section('manager-content')<p class="text-sm font-bold text-orange-600">{{ $activity->title }}</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">Tambah sesi</h1>@include('manager.activity-sessions._form')@endsection
