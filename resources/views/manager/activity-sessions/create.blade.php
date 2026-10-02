@extends('layouts.manager')
@section('title','Tambah Sesi')
@section('manager-content')
<x-workspace.page-header eyebrow="Jadwal Activity" title="Tambah sesi" :description="$activity->title" />
@include('manager.activity-sessions._form')
@endsection
