@extends('layouts.manager')
@section('title','Edit Activity')
@section('manager-content')
<x-workspace.page-header eyebrow="Edit draft Activity" :title="$activity->title" description="Perbarui informasi sebelum Activity diajukan kembali." />
@include('manager.activities._form')
@endsection
