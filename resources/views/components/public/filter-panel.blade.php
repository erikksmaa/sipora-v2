@props(['action', 'reset', 'label' => 'Filter pencarian'])
<section x-data="{ filtersOpen: false }" class="public-panel" aria-label="{{ $label }}">
    <button type="button" class="btn-secondary w-full justify-between md:hidden" @click="filtersOpen = !filtersOpen" :aria-expanded="filtersOpen"><span class="inline-flex items-center gap-2"><x-ui.icon name="filter" />Filter</span><x-ui.icon name="chevron-down" class="size-4 transition" x-bind:class="filtersOpen && 'rotate-180'" /></button>
    <form method="GET" action="{{ $action }}" class="mt-4 md:mt-0" x-bind:class="filtersOpen ? 'block' : 'hidden md:block'">
        {{ $slot }}
        <div class="mt-5 flex flex-wrap gap-3"><button class="landing-btn-primary" type="submit"><x-ui.icon name="filter" class="size-4" />Terapkan filter</button><a class="btn-ghost" href="{{ $reset }}">Reset filter</a></div>
    </form>
</section>
