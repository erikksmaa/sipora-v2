@extends('layouts.manager')
@section('title','Buat Activity')
@section('manager-content')
<x-workspace.page-header eyebrow="Activity baru" title="Buat Activity" description="Simpan sebagai draft sebelum diajukan kepada Verifier." />
@include('manager.activities._form')
@endsection
