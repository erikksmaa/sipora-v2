@props(['hover' => false])
<article {{ $attributes->class(['surface-card p-5 sm:p-6', 'surface-card-hover' => $hover]) }}>{{ $slot }}</article>
