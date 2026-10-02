@extends('layouts.manager')
@section('title', 'Dashboard '.$organization->name.' · SIPORA')
@section('manager-content')
<div class="space-y-6">
    <x-workspace.page-header eyebrow="Operasional Community" title="Dashboard {{ $organization->name }}" description="Pantau anggota, Activity, Program, dan pekerjaan yang perlu ditindaklanjuti.">
        <a class="btn-secondary" href="{{ route('manager.programs.create', $organization) }}">Buat Program</a>
        <a class="btn-primary" href="{{ route('manager.activities.create', $organization) }}">Buat Activity</a>
    </x-workspace.page-header>

    <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan Community">
        @foreach([
            ['label' => 'Anggota aktif', 'value' => $counts['members'], 'icon' => 'users', 'tone' => 'bg-indigo-50 text-primary', 'url' => route('manager.members.index', $organization)],
            ['label' => 'Permintaan bergabung', 'value' => $counts['pending'], 'icon' => 'user', 'tone' => 'bg-orange-50 text-orange-700', 'url' => route('manager.members.index', $organization).'#permintaan'],
            ['label' => 'Activity terbit', 'value' => $counts['published_activities'], 'icon' => 'calendar', 'tone' => 'bg-emerald-50 text-emerald-700', 'url' => route('manager.activities.index', $organization)],
            ['label' => 'Peserta menunggu', 'value' => $counts['pending_participants'], 'icon' => 'clipboard-check', 'tone' => 'bg-amber-50 text-amber-700', 'url' => route('manager.activities.index', $organization)],
        ] as $metric)
            <a class="sipora-card group !p-4 transition hover:border-indigo-200 hover:shadow-md" href="{{ $metric['url'] }}">
                <span class="grid size-10 place-items-center rounded-xl {{ $metric['tone'] }}"><x-ui.icon :name="$metric['icon']" /></span>
                <strong class="mt-3 block text-3xl font-black text-primary-dark">{{ $metric['value'] }}</strong>
                <span class="mt-1 block text-sm font-semibold text-slate-600 group-hover:text-primary">{{ $metric['label'] }}</span>
            </a>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]" aria-labelledby="manager-actions-heading">
        <div class="sipora-card">
            <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-wider text-orange-700">Perlu tindakan</p><h2 id="manager-actions-heading" class="mt-1 text-xl font-bold text-primary-dark">Antrean operasional</h2></div><span class="text-sm text-slate-500">Data aktual Community</span></div>
            <div class="mt-5 divide-y divide-slate-100">
                @if($counts['pending'] > 0)<a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="{{ route('manager.members.index', $organization) }}#permintaan"><span><strong class="block text-primary-dark">{{ $counts['pending'] }} permintaan bergabung</strong><span class="text-slate-500">Tinjau calon anggota Community</span></span><x-ui.icon name="chevron-right" /></a>@endif
                @foreach($activitiesNeedingParticipants as $activity)<a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="{{ route('manager.activities.participants.index', [$organization, $activity]) }}"><span><strong class="block text-primary-dark">{{ $activity->pending_count }} peserta menunggu · {{ $activity->title }}</strong><span class="text-slate-500">Tinjau pendaftaran Activity</span></span><x-ui.icon name="chevron-right" /></a>@endforeach
                @foreach($programsNeedingRevision as $program)<a class="flex items-center justify-between gap-3 py-4 text-sm hover:text-primary" href="{{ route('manager.programs.show', [$organization, $program]) }}"><span><strong class="block text-primary-dark">{{ $program->title }}</strong><span class="text-slate-500">Ada Proposal, Logbook, atau E-LPJ yang perlu revisi</span></span><x-ui.icon name="chevron-right" /></a>@endforeach
                @if($counts['pending'] === 0 && $activitiesNeedingParticipants->isEmpty() && $programsNeedingRevision->isEmpty())<p class="py-6 text-sm text-slate-500">Tidak ada antrean tindakan saat ini.</p>@endif
            </div>
        </div>
        <aside class="sipora-card"><h2 class="text-xl font-bold text-primary-dark">Community yang dikelola</h2><div class="mt-4 flex items-center gap-3"><span class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-xl bg-primary text-lg font-bold text-white">@if($organization->logo_path)<img class="size-full object-cover" src="{{ route('communities.logo', $organization) }}" alt="">@else{{ mb_substr($organization->name, 0, 1) }}@endif</span><div class="min-w-0"><p class="truncate font-bold text-primary-dark">{{ $organization->name }}</p><p class="text-xs text-slate-500">{{ $organization->category->name }}</p></div></div><dl class="mt-5 space-y-3 border-t border-slate-100 pt-4 text-sm"><div class="flex justify-between gap-3"><dt class="text-slate-500">Verifikasi</dt><dd><x-community.status-badge :status="$organization->review_status" /></dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Operasional</dt><dd class="font-bold capitalize text-primary-dark">{{ $organization->operational_status }}</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Profil</dt><dd class="font-bold text-primary-dark">{{ $completion['percentage'] }}%</dd></div><div class="flex justify-between gap-3"><dt class="text-slate-500">Program berjalan</dt><dd class="font-bold text-primary-dark">{{ $counts['running_programs'] }}</dd></div></dl><div class="mt-5 flex flex-wrap gap-2"><a class="btn-secondary" href="{{ route('manager.profile.edit', $organization) }}">Kelola profil</a><a class="btn-ghost" href="{{ route('communities.show', $organization) }}">Halaman publik</a></div></aside>
    </section>

    <div class="grid gap-6 xl:grid-cols-2">
        <section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary-dark">Activity mendatang</h2><a class="text-sm font-bold text-primary hover:underline" href="{{ route('manager.activities.index', $organization) }}">Semua Activity</a></div><div class="mt-4 divide-y divide-slate-100">@forelse($upcomingActivities as $activity)<a class="flex items-center gap-4 py-3 hover:text-primary" href="{{ route('manager.activities.show', [$organization, $activity]) }}"><span class="grid size-12 shrink-0 place-items-center rounded-xl bg-indigo-50 text-xs font-bold text-primary">{{ $activity->start_at->translatedFormat('d M') }}</span><span class="min-w-0 flex-1"><strong class="block truncate text-sm text-primary-dark">{{ $activity->title }}</strong><span class="text-xs text-slate-500">{{ $activity->start_at->translatedFormat('d M Y, H:i') }}</span></span><x-activity.status-badge :status="$activity->execution_status" /></a>@empty<p class="py-6 text-sm text-slate-500">Belum ada Activity mendatang.</p>@endforelse</div></section>
        <section class="sipora-card"><div class="flex items-center justify-between gap-3"><h2 class="text-xl font-bold text-primary-dark">Program berjalan</h2><a class="text-sm font-bold text-primary hover:underline" href="{{ route('manager.programs.index', $organization) }}">Semua Program</a></div><div class="mt-4 divide-y divide-slate-100">@forelse($runningPrograms as $program)<a class="flex items-center justify-between gap-3 py-4" href="{{ route('manager.programs.show', [$organization, $program]) }}"><span class="min-w-0"><strong class="block truncate text-sm text-primary-dark">{{ $program->title }}</strong><span class="text-xs text-slate-500">Proposal {{ $program->latestProposal?->status ?? 'belum ada' }} · E-LPJ {{ $program->latestFinancialReport?->status ?? 'belum ada' }}</span></span><x-ui.icon name="chevron-right" /></a>@empty<p class="py-6 text-sm text-slate-500">Belum ada Program berjalan.</p>@endforelse</div></section>
    </div>
</div>
@endsection
