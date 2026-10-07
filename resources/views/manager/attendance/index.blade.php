@extends('layouts.manager')
@section('title', 'Presensi · '.$activity->title)
@section('manager-content')
@php
    $statusLabels = ['present' => 'Hadir', 'absent' => 'Tidak Hadir', 'excused' => 'Izin'];
    $initialStatuses = $participants->mapWithKeys(function ($participant) use ($statusLabels) {
        $status = old('attendance.'.$participant->uuid().'.status', $participant->attendances->first()?->attendance_status ?? 'present');
        return [$participant->uuid() => array_key_exists($status, $statusLabels) ? $status : 'present'];
    })->all();
    $hasSavedAttendance = $counts->sum() > 0;
@endphp

<x-workspace.page-header eyebrow="Presensi & kehadiran" title="Manajemen Kehadiran" :description="$activity->title.' · Sesi '.$session->session_number.' — '.$session->title">
    <a class="btn-secondary" href="{{ route('manager.activities.sessions.index', [$organization, $activity]) }}">Semua sesi</a>
</x-workspace.page-header>

<div class="mt-4 rounded-xl border border-border bg-surface-soft px-4 py-3 text-sm font-semibold text-text-primary">
    {{ $session->title }} · {{ $session->start_at->translatedFormat('d M Y, H:i') }}–{{ $session->end_at->format('H:i') }}
</div>

<div class="mt-5" x-data="bulkAttendance(@js($initialStatuses))">
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-live="polite">
        <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
            <p class="text-xs font-bold uppercase text-text-muted">Peserta diterima</p>
            <p class="mt-2 text-2xl font-extrabold text-text-primary">{{ $acceptedCount }}</p>
        </div>
        @foreach($statusLabels as $status => $label)
            <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
                <p class="text-xs font-bold uppercase text-text-muted">{{ $label }}</p>
                <p class="mt-2 text-2xl font-extrabold text-text-primary" x-text="count('{{ $status }}')">{{ $status === 'present' ? $acceptedCount - ($counts['absent'] ?? 0) - ($counts['excused'] ?? 0) : ($counts[$status] ?? 0) }}</p>
            </div>
        @endforeach
    </div>

    <form class="mt-5" method="POST" action="{{ route('manager.activities.attendance.bulk-update', [$organization, $activity, $session]) }}"
          @if($hasSavedAttendance) data-confirm="Presensi yang sudah tersimpan pada sesi ini akan diperbarui." data-confirm-title="Perbarui presensi?" data-confirm-button="Ya, simpan" @endif>
        @csrf
        @method('PUT')
        <div class="rounded-xl border border-border bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <label for="session-selector" class="mb-2 block text-xs font-bold uppercase text-text-muted">Pilih sesi</label>
                    <select id="session-selector" class="field !min-h-10 w-full md:w-auto" onchange="if (this.value) window.location = this.value">
                        @foreach($sessions as $option)
                            <option value="{{ route('manager.activities.attendance.index', [$organization, $activity, $option]) }}" @selected($option->is($session))>Sesi {{ $option->session_number }} — {{ $option->title }}</option>
                        @endforeach
                    </select>
                </div>
                @if($acceptedCount)
                    <button type="button" class="btn-secondary !min-h-10" x-on:click="markAllPresent()">Tandai Semua Hadir</button>
                @endif
            </div>
            <p class="mt-3 text-xs text-text-muted">{{ $unmarkedCount }} peserta belum memiliki presensi tersimpan. Pilihan Hadir baru disimpan setelah Anda menekan Simpan Presensi.</p>
        </div>

        <div class="mt-5 overflow-hidden rounded-xl border border-border bg-white shadow-sm">
            <div class="hidden gap-4 border-b border-border bg-surface-soft px-5 py-3 text-xs font-bold uppercase text-text-muted md:grid md:grid-cols-[minmax(0,1fr)_11rem_minmax(0,1.5fr)]">
                <span>Peserta</span><span>Status kehadiran</span><span>Catatan</span>
            </div>
            @forelse($participants as $participant)
                @php($attendance = $participant->attendances->first())
                <div class="grid gap-3 border-b border-border p-4 last:border-0 md:grid-cols-[minmax(0,1fr)_11rem_minmax(0,1.5fr)] md:items-center md:gap-4 md:px-5">
                    <div class="min-w-0">
                        <p class="font-bold text-text-primary">{{ $participant->user->profile?->full_name ?: $participant->user->name }}</p>
                        <p class="text-xs text-text-muted">Peserta diterima</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-text-muted md:sr-only" for="status-{{ $participant->uuid() }}">Status kehadiran {{ $participant->user->name }}</label>
                        <select id="status-{{ $participant->uuid() }}" class="field !min-h-10 w-full" name="attendance[{{ $participant->uuid() }}][status]" x-model="statuses['{{ $participant->uuid() }}']" required>
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected($initialStatuses[$participant->uuid()] === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold text-text-muted md:sr-only" for="notes-{{ $participant->uuid() }}">Catatan {{ $participant->user->name }}</label>
                        <input id="notes-{{ $participant->uuid() }}" class="field !min-h-10 w-full" name="attendance[{{ $participant->uuid() }}][notes]" maxlength="3000" value="{{ old('attendance.'.$participant->uuid().'.notes', $attendance?->notes) }}" placeholder="Catatan opsional">
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <p class="font-bold text-text-primary">Belum ada peserta diterima</p>
                    <p class="mt-2 text-sm text-text-muted">Peserta pending, ditolak, atau dibatalkan tidak ditampilkan sebagai target presensi.</p>
                </div>
            @endforelse
        </div>
        @if($acceptedCount)
            <div class="mt-5 flex justify-end"><button type="submit" class="btn-primary">Simpan Presensi</button></div>
        @endif
    </form>
</div>

<div class="mt-6 rounded-xl border border-border bg-surface-soft p-5 text-sm text-text-secondary">
    <p class="font-bold text-text-primary">Presensi adalah bukti partisipasi per sesi.</p>
    <p class="mt-1">Pencatatan di halaman ini tidak mengubah status penyelesaian peserta secara otomatis.</p>
</div>
@endsection
