@extends('layouts.manager')
@section('title','Pengaturan Pendaftaran Program')
@section('manager-content')
<x-ui.page-header title="Pengaturan Pendaftaran" :description="$program->title"><x-slot:actions><a class="btn-outline" href="{{ route('manager.programs.show',[$organization,$program]) }}">Kembali ke Program</a></x-slot:actions></x-ui.page-header>
<form method="POST" action="{{ route('manager.programs.intake.update',[$organization,$program]) }}" class="sipora-card mt-6 space-y-6">@csrf @method('PATCH')
    @include('manager.programs._intake-fields')
    @if($errors->any())<div role="alert" class="text-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <button type="submit" class="btn-accent">Simpan Pengaturan</button>
</form>
@endsection
