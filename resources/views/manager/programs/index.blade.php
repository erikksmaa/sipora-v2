@extends('layouts.manager')
@section('title', 'Program · '.$organization->name)
@section('manager-content')
<div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-bold text-orange-600">PROGRAM & LAPORAN</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">Program Saya</h1><p class="mt-2 max-w-2xl text-slate-600">Susun payung administratif untuk rangkaian Activity {{ $organization->name }}.</p></div><a class="btn-primary" href="{{ route('manager.programs.create', $organization) }}">+ Buat Program</a></div>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
@forelse($programs as $program)
    <a href="{{ route('manager.programs.show', [$organization, $program]) }}" class="grid gap-4 border-b border-slate-100 p-5 transition hover:bg-slate-50 md:grid-cols-[1fr_auto] md:items-center">
        <div><p class="text-xs font-bold uppercase tracking-wide text-orange-600">{{ $program->category?->name }}</p><h2 class="mt-1 text-lg font-extrabold text-[#14205c]">{{ $program->title }}</h2><p class="mt-1 text-sm text-slate-500">{{ $program->start_date?->translatedFormat('d M Y') ?? 'Periode belum ditentukan' }}@if($program->end_date) – {{ $program->end_date->translatedFormat('d M Y') }}@endif · {{ $program->activities_count }} Activity</p></div>
        <x-program.status-badge :status="$program->execution_status" />
    </a>
@empty
    <div class="p-10 text-center"><p class="font-bold text-[#14205c]">Belum ada Program</p><p class="mt-2 text-sm text-slate-500">Program dapat disimpan sebagai rencana meski belum memiliki Activity.</p><a class="btn-primary mt-5 inline-flex" href="{{ route('manager.programs.create', $organization) }}">Buat Program pertama</a></div>
@endforelse
</div><div class="mt-5">{{ $programs->links() }}</div>
@endsection
