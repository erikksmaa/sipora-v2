@extends('layouts.manager')
@section('title', 'Activity · '.$organization->name)
@section('manager-content')
<div class="flex flex-wrap items-center justify-between gap-4"><div><p class="text-sm font-bold text-orange-600">WORKSPACE KOMUNITAS</p><h1 class="mt-1 text-3xl font-black text-[#14205c]">Activity</h1><p class="mt-2 text-slate-600">Kelola usulan dan publikasi kegiatan {{ $organization->name }}.</p></div><a class="btn-primary" href="{{ route('manager.activities.create', $organization) }}">+ Buat Activity</a></div>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
@forelse($activities as $activity)<a href="{{ route('manager.activities.show', [$organization,$activity]) }}" class="grid gap-3 border-b border-slate-100 p-5 transition hover:bg-slate-50 md:grid-cols-[1fr_auto]"><div><h2 class="font-extrabold text-[#14205c]">{{ $activity->title }}</h2><p class="mt-1 text-sm text-slate-500">{{ $activity->category?->name }} · {{ $activity->start_at->translatedFormat('d M Y, H:i') }}</p></div><div class="flex flex-wrap gap-2"><x-activity.status-badge :status="$activity->review_status"/><x-activity.status-badge :status="$activity->publication_status"/></div></a>@empty<div class="p-10 text-center"><p class="font-bold text-[#14205c]">Belum ada Activity</p><p class="mt-2 text-sm text-slate-500">Buat draft pertama komunitas Anda.</p></div>@endforelse
</div><div class="mt-5">{{ $activities->links() }}</div>
@endsection
