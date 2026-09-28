@extends('layouts.manager')
@section('title','Edit Sesi')
@section('manager-content')<p class="text-sm font-bold text-orange-600">{{ $activity->title }}</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">Edit sesi {{ $session->session_number }}</h1>@include('manager.activity-sessions._form')@endsection
