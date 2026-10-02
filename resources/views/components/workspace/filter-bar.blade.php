@props(['resetHref' => null])
<section {{ $attributes->class(['rounded-2xl border border-border bg-white p-4 shadow-sm']) }} aria-label="Filter hasil">
    <form method="GET" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
        {{ $slot }}
        <button class="btn-primary" type="submit"><x-ui.icon name="search" class="size-4" />Terapkan</button>
        @if ($resetHref)<a class="btn-ghost" href="{{ $resetHref }}">Reset</a>@endif
    </form>
</section>
