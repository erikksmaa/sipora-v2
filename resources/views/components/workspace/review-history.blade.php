@props(['title' => 'Riwayat review'])
<section {{ $attributes->class(['rounded-2xl border border-border bg-white p-5 shadow-sm sm:p-6']) }}><h2 class="text-xl font-bold text-text-primary">{{ $title }}</h2><div class="mt-4 border-l-2 border-primary-100 pl-5">{{ $slot }}</div></section>
