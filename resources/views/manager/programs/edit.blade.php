@extends('layouts.manager')
@section('title', 'Edit Program')
@section('manager-content')
<x-workspace.page-header eyebrow="Edit rencana Program" :title="$program->title" description="Perbarui informasi inti sesuai status Program saat ini." />
@include('manager.programs._form')
@endsection
