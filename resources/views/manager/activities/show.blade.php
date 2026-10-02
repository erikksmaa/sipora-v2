@extends('layouts.manager')
@section('title', $activity->title)
@section('manager-content')
<div class="space-y-6">
    <x-workspace.page-header :eyebrow="$activity->category?->name" :title="$activity->title" :description="$organization->name">
        @can('update', $activity)<a class="btn-secondary" href="{{ route('manager.activities.edit', [$organization, $activity]) }}">Edit draft</a>@endcan
        @can('submit', $activity)<form method="POST" action="{{ route('manager.activities.submit', [$organization, $activity]) }}">@csrf<button class="btn-primary">Ajukan verifikasi</button></form>@endcan
        @can('publish', $activity)<form method="POST" action="{{ route('manager.activities.publish', [$organization, $activity]) }}">@csrf<button class="btn-primary">Publikasikan</button></form>@endcan
    </x-workspace.page-header>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-label="Ringkasan Activity">
        @if($activity->poster_path)<img class="max-h-72 w-full object-cover" src="{{ route('manager.activities.poster', [$organization, $activity]) }}" alt="Poster {{ $activity->title }}">@endif
        <div class="p-5 sm:p-6"><div class="flex flex-wrap items-center gap-3"><x-activity.status-badge :status="$activity->execution_status" /><span class="text-xs text-slate-500">Review: {{ str($activity->review_status)->replace('_', ' ')->title() }}</span><span class="text-xs text-slate-500">Publikasi: {{ str($activity->publication_status)->replace('_', ' ')->title() }}</span></div>
            <p class="mt-5 whitespace-pre-line leading-7 text-slate-700">{{ $activity->description }}</p>
            <dl class="mt-6 grid gap-4 rounded-xl bg-slate-50 p-5 sm:grid-cols-2"><div><dt class="text-xs font-bold uppercase text-slate-500">Waktu</dt><dd class="mt-1 text-sm font-semibold text-primary-dark">{{ $activity->start_at->translatedFormat('d M Y H:i') }} – {{ $activity->end_at->translatedFormat('d M Y H:i') }}</dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Lokasi</dt><dd class="mt-1 text-sm font-semibold text-primary-dark">{{ ucfirst($activity->location_type) }} · {{ $activity->venue_name ?: 'Daring' }}</dd></div><div><dt class="text-xs font-bold uppercase text-slate-500">Program</dt><dd class="mt-1 text-sm">@if($activity->program)<a class="font-bold text-primary hover:underline" href="{{ route('manager.programs.show', [$organization, $activity->program]) }}">{{ $activity->program->title }}</a>@else Activity mandiri @endif</dd></div></dl>
            @if($activity->review_status === 'revision' && $activity->latestReview)<div class="mt-5 rounded-xl border border-orange-200 bg-orange-50 p-4"><p class="font-bold text-orange-900">Catatan revisi Verifier</p><p class="mt-1 whitespace-pre-line text-sm text-orange-800">{{ $activity->latestReview->review_notes }}</p></div>@endif
        </div>
    </section>
    <section class="sipora-card"><h2 class="text-lg font-bold text-primary-dark">Kelola pelaksanaan</h2><p class="mt-1 text-sm text-slate-500">Sesi, peserta, dan presensi Activity ini.</p><div class="mt-4 flex flex-wrap gap-2"><a class="btn-secondary" href="{{ route('manager.activities.sessions.index', [$organization, $activity]) }}">Sesi & presensi</a><a class="btn-secondary" href="{{ route('manager.activities.participants.index', [$organization, $activity]) }}">Peserta & sertifikat</a></div></section>
    <section class="sipora-card"><h2 class="text-lg font-bold text-primary-dark">Status & tindakan lanjutan</h2><div class="mt-4 flex flex-wrap gap-3">@can('completeExecution', $activity)@if(in_array($activity->execution_status, ['scheduled', 'ongoing'], true) && $activity->end_at->isPast())<form method="POST" action="{{ route('manager.activities.complete-execution', [$organization, $activity]) }}" onsubmit="return confirm('Selesaikan eksekusi Activity ini?')">@csrf<button class="btn-primary">Selesaikan Activity</button></form>@endif @endcan @can('archive', $activity)<form method="POST" action="{{ route('manager.activities.archive', [$organization, $activity]) }}">@csrf<button class="btn-secondary">Arsipkan</button></form>@endcan</div></section>
</div>
@endsection
