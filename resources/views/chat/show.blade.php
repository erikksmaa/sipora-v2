@extends('layouts.'.$workspace)
@section('title', ($room->type === 'global' ? 'Chat Global' : 'Chat Community').' · SIPORA')
@section($workspace === 'manager' ? 'manager-content' : ($workspace === 'verifier' ? 'verifier-content' : 'content'))
<div class="mx-auto max-w-5xl space-y-4" x-data="siporaChat(@js($config))">
    <x-ui.page-header eyebrow="Percakapan SIPORA" :title="$room->type === 'global' ? 'Chat Global' : 'Chat Community'" :description="$room->type === 'global' ? 'Obrolan singkat ekosistem untuk pengguna SIPORA yang telah masuk.' : ($room->community?->name ?? 'Community diarsipkan').' · Percakapan khusus anggota aktif.'" />
    <p class="text-sm text-text-secondary">Hindari membagikan NIK, nomor telepon, dan informasi pribadi. Gunakan Forum untuk diskusi terstruktur.</p>
    @include('chat._conversation', ['compact' => false])
</div>
@endsection
