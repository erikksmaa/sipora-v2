@extends('layouts.admin')
@section('title', 'Log Aktivitas Akun Internal · SIPORA')
@section('content')
<div class="space-y-6"><x-ui.page-header eyebrow="Sistem" title="Log Aktivitas Akun Internal" description="Riwayat tindakan pengelolaan akun, tanpa password atau token pemulihan." />
<section class="table-shell"><div class="overflow-x-auto"><table class="w-full min-w-[760px]"><thead><tr><th scope="col">Waktu</th><th scope="col">Pelaku</th><th scope="col">Akun tujuan</th><th scope="col">Tindakan</th></tr></thead><tbody>@forelse($entries as $entry)<tr class="border-t border-border"><td>{{ $entry->created_at->timezone(config('sipora.display_timezone'))->format('d M Y H:i') }}</td><td>{{ $entry->causer?->name ?? 'Sistem' }}</td><td>{{ $entry->subject?->name ?? '—' }}</td><td class="break-words">{{ str($entry->event)->replace('_',' ')->title() }}</td></tr>@empty<tr><td colspan="4"><x-ui.empty-state icon="file-text" title="Belum ada aktivitas akun internal" /></td></tr>@endforelse</tbody></table></div></section>{{ $entries->links() }}</div>
@endsection
