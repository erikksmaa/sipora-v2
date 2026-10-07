@props(['label', 'icon' => 'sparkles', 'tone' => 'primary'])
@php($tones = ['primary' => 'bg-primary-800', 'orange' => 'bg-accent-700', 'green' => 'bg-emerald-800', 'slate' => 'bg-primary-900'])
<div {{ $attributes->class(['media-fallback relative grid h-full w-full place-items-center overflow-hidden text-white', $tones[$tone] ?? $tones['primary']]) }}>
    <div class="relative flex flex-col items-center gap-3 px-6 text-center">
        <span class="grid size-12 place-items-center border border-white/40 bg-white/10"><x-ui.icon :name="$icon" class="size-6" /></span>
        <strong class="text-base font-bold text-white">{{ $label }}</strong>
    </div>
</div>
