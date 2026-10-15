@props(['status'])
<x-ui.badge :tone="match($status) { 'accepted'=>'success', 'rejected'=>'danger', 'waitlisted'=>'warning', 'under_review'=>'info', default=>'neutral' }">{{ \App\Models\ProgramApplication::LABELS[$status] ?? 'Belum mendaftar' }}</x-ui.badge>
