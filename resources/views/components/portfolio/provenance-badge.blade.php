@props(['verified' => false])
<span {{ $attributes->class([
    'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold',
    'bg-emerald-50 text-emerald-700' => $verified,
    'bg-slate-100 text-slate-600' => ! $verified,
]) }}>
    <span aria-hidden="true">{{ $verified ? '✓' : '✦' }}</span>
    {{ $verified ? 'Terverifikasi SIPORA' : 'Data mandiri' }}
</span>
