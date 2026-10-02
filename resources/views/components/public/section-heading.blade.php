@props(['eyebrow', 'title', 'description' => null, 'centered' => false])

<div @class(['mx-auto max-w-3xl text-center' => $centered])>
    <p class="text-xs font-bold uppercase tracking-[0.2em] text-accent-600">{{ $eyebrow }}</p>
    <h2 class="font-heading mt-3 text-3xl font-bold tracking-tight text-text-primary sm:text-4xl">{{ $title }}</h2>
    @if ($description)
    <p class="mt-4 text-base leading-7 text-text-secondary sm:text-lg">{{ $description }}</p>@endif
</div>
