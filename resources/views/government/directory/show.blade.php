@extends($workspace === 'admin' ? 'layouts.admin' : 'layouts.verifier')
@section('title', 'Detail '.$title.' · SIPORA')
@section($workspace === 'admin' ? 'content' : 'verifier-content')
<div class="space-y-6">
    <x-ui.page-header eyebrow="Data Ekosistem" :title="$record['name']" :description="$title.' · Ringkasan baca saja'"><x-slot:actions><a class="btn-outline" href="{{ route($workspace.'.directory.'.$kind.'.index') }}">Kembali ke direktori</a>@if($record['public_url'])<a class="btn-primary" href="{{ $record['public_url'] }}">Lihat profil publik</a>@endif</x-slot:actions></x-ui.page-header>
    <section class="sipora-card">
        @if($record['photo'])<img data-media-fallback src="{{ $record['photo'] }}" alt="" class="mb-5 size-20 rounded border border-border bg-white object-contain">@endif
        <dl class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">@foreach($columns as $index=>$column)<div class="min-w-0 rounded border border-border bg-surface-soft p-4"><dt class="text-xs font-semibold text-text-secondary">{{ $column }}</dt><dd class="mt-2 break-words font-semibold text-text-primary">{{ $record['cells'][$index] }}</dd></div>@endforeach</dl>
    </section>
    <p class="text-sm text-text-secondary">Direktori tidak mengubah status atau memberikan hak pengelolaan. Verifikasi tetap dilakukan melalui antrean dan kewenangan existing.</p>
</div>
@endsection
