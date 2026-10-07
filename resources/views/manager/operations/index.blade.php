@extends('layouts.manager')
@section('title', $title.' · '.$organization->name)
@section('manager-content')
<div class="space-y-6">
    <x-workspace.page-header eyebrow="Operasional Community" :title="$title" :description="$description" />
    <form method="GET" class="sipora-card grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_minmax(0,220px)_minmax(0,140px)_auto]" aria-label="Filter {{ $title }}">
        <div><label class="label" for="operation-search">Cari {{ in_array($title, ['Sesi', 'Peserta', 'Presensi']) ? 'Activity' : 'Program' }}</label><input id="operation-search" class="field" type="search" name="q" value="{{ $filters['q'] }}" maxlength="80" placeholder="Cari nama {{ in_array($title, ['Sesi', 'Peserta', 'Presensi']) ? 'Activity' : 'Program' }}"></div>
        <div><label class="label" for="operation-status">{{ $statusLabel }}</label><select id="operation-status" class="field" name="status"><option value="">Semua status</option>@foreach($statusOptions as $value => $label)<option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>@endforeach</select></div>
        <div><label class="label" for="operation-per-page">Per halaman</label><select id="operation-per-page" class="field" name="per_page">@foreach([5, 10, 20, 50] as $count)<option value="{{ $count }}" @selected($filters['per_page'] === $count)>{{ $count }}</option>@endforeach</select></div>
        <div class="flex items-end gap-2"><button class="btn-primary" type="submit">Terapkan</button><a class="btn-secondary" href="{{ url()->current() }}">Reset</a></div>
    </form>
    <section class="sipora-card">
        <p class="mb-4 text-sm text-text-secondary">{{ $rows->total() }} hasil ditemukan</p>
        <div class="space-y-3">
            @forelse($rows as $row)
                <article class="flex flex-col justify-between gap-4 rounded-sm border border-border bg-surface-soft p-4 sm:flex-row sm:items-center">
                    <div class="min-w-0"><h2 class="font-bold text-text-primary">{{ $row['title'] }}</h2><p class="mt-1 text-sm text-text-secondary">{{ $row['detail'] }}</p><div class="mt-2">@if($row['badgeType'] === 'activity')<x-activity.status-badge :status="$row['badgeStatus']" />@elseif($row['badgeType'] === 'proposal' && $row['badgeStatus'])<x-program.proposal-status-badge :status="$row['badgeStatus']" />@elseif($row['badgeType'] === 'financial' && $row['badgeStatus'])<x-program.financial-status-badge :status="$row['badgeStatus']" />@elseif($row['badgeType'] === 'logbooks')<span class="inline-flex rounded-full px-3 py-1 text-xs font-extrabold {{ $row['badgeStatus'] > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">{{ $row['badgeStatus'] > 0 ? $row['badgeStatus'].' catatan' : 'Belum ada catatan' }}</span>@else<span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-700">Belum ada</span>@endif</div></div>
                    <a class="btn-secondary shrink-0 self-start" href="{{ $row['url'] }}">{{ $row['action'] }}</a>
                </article>
            @empty
                <x-ui.empty-state title="Tidak ada hasil pada filter ini" description="Coba ubah atau reset filter." icon="clipboard-check" />
            @endforelse
        </div>
        <div class="mt-5">{{ $rows->links() }}</div>
    </section>
</div>
@endsection
