@extends('layouts.manager')
@section('title','Edit Activity')
@section('manager-content')<p class="text-sm font-bold text-orange-600">EDIT DRAFT</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">{{ $activity->title }}</h1>@include('manager.activities._form')@endsection
