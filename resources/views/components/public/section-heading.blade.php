@props(['eyebrow', 'title', 'description' => null, 'centered' => false])

<div @class(['mx-auto max-w-3xl text-center' => $centered])>
    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#f59e0b]">{{ $eyebrow }}</p>
    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#18245c] sm:text-4xl">{{ $title }}</h2>
    @if ($description)<p class="mt-4 text-base leading-7 text-slate-600 sm:text-lg">{{ $description }}</p>@endif
</div>
