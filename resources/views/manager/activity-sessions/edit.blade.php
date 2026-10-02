@extends('layouts.manager')
@section('title','Edit Sesi')
@section('manager-content')
<x-workspace.page-header eyebrow="Jadwal Activity" :title="'Edit sesi '.$session->session_number" :description="$activity->title" />
@include('manager.activity-sessions._form')
@endsection
