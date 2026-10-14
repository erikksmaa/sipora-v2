@props(['verified' => false])
<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold',
    'border-success/30 bg-success-soft text-success' => $verified,
    'border-border bg-surface-soft text-text-secondary' => ! $verified,
]) }}>
    <x-ui.icon :name="$verified ? 'badge-check' : 'user'" class="size-3.5" />
    {{ $verified ? 'Terverifikasi SIPORA' : 'Data mandiri' }}
</span>
