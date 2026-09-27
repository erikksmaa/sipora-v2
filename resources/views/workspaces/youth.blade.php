@extends('layouts.youth')
@section('content')
<h1 class="mb-4 text-3xl font-bold">Your SIPORA home</h1>
<p>Welcome, {{ auth()->user()->name }}. Your account is ready.</p>
<p class="mt-3 text-slate-600">Youth profile and participation features will arrive in later phases.</p>
@endsection
