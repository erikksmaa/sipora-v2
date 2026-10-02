@extends('layouts.manager')
@section('title', 'Buat Program')
@section('manager-content')
<x-workspace.page-header eyebrow="Program baru" title="Buat Rencana Program" description="Simpan identitas dan tujuan Program sebagai rencana awal." />
@include('manager.programs._form')
@endsection
