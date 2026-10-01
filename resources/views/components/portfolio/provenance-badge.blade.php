@props(['verified' => false])
<span {{ $attributes->class([
    'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold',
    'border-emerald-200 bg-emerald-50 text-emerald-800' => $verified,
    'border-slate-200 bg-slate-100 text-slate-700' => ! $verified,
]) }}>
    <x-ui.icon :name="$verified ? 'badge-check' : 'user'" class="size-3.5" />
    {{ $verified ? 'Terverifikasi SIPORA' : 'Data mandiri' }}
</span>
