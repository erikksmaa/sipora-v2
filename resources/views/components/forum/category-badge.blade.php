@props(['category'])
<span {{ $attributes->class(['status-badge', 'forum-category-badge']) }} data-category="{{ $category->slug }}">{{ $category->name }}</span>
