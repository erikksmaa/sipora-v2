@extends('layouts.verifier')
@section('title','Pendaftaran Program')
@section('verifier-content')
<x-ui.page-header title="Pendaftaran Program" description="Tinjau pendaftar Program yang Proposal-nya telah disetujui." />
<div class="mt-6 space-y-3">@forelse($programs as $program)<article class="sipora-card flex flex-wrap items-center justify-between gap-4"><div><h2 class="font-bold">{{ $program->title }}</h2><p class="mt-1 text-sm text-text-secondary">{{ $program->organization->name }} · {{ $program->applications_count }} pendaftar · {{ $program->participants_count }} peserta diterima</p></div><a class="btn-outline" href="{{ route('verifier.programs.applications.index',$program) }}">Kelola Pendaftar</a></article>@empty<x-ui.empty-state title="Belum ada Program" description="Program dengan Proposal disetujui akan tampil di sini." />@endforelse</div><div class="mt-6">{{ $programs->links() }}</div>
@endsection
