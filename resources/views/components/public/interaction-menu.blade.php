@props(['mobile' => false])
@php
    $active = request()->routeIs('social.*', 'forum.*');
    $panelId = $mobile ? 'mobile-interaction-menu' : 'interaction-menu';
@endphp
<div x-data="{ expanded: false }" @click.outside="expanded = false"
    @keydown.escape.stop="expanded = false; $refs.trigger.focus()"
    @class(['relative', 'flex h-full items-center' => !$mobile])>
    <button x-ref="trigger" type="button" @click="expanded = !expanded"
        @keydown.arrow-down.prevent="expanded = true; $nextTick(() => $refs.panel.querySelector('a')?.focus())"
        :aria-expanded="expanded" aria-controls="{{ $panelId }}"
        @class([
            'gap-1',
            'public-nav-link h-full' => !$mobile,
            'public-nav-active' => !$mobile && $active,
            'flex min-h-11 w-full items-center justify-between rounded-lg px-3 py-2.5 hover:bg-primary-50 hover:text-primary' => $mobile,
            'bg-primary-50 text-primary' => $mobile && $active,
        ])>
        Interaksi <x-ui.icon name="chevron-down" class="size-4 transition" x-bind:class="expanded && 'rotate-180'" />
    </button>
    <div x-ref="panel" id="{{ $panelId }}" x-cloak x-show="expanded" x-transition
        @class([
            'rounded-2xl border border-border bg-white p-2',
            'absolute left-1/2 top-[calc(100%-4px)] w-64 -translate-x-1/2 shadow-lg' => !$mobile,
            'my-1 ml-3' => $mobile,
        ])>
        @foreach ([['Social Feed', 'Update terbaru ekosistem', 'users', 'social.index', 'social.*'], ['Forum', 'Diskusi dan berbagi pengalaman', 'message-circle', 'forum.index', 'forum.*']] as [$label, $description, $icon, $routeName, $pattern])
            <a href="{{ route($routeName) }}" @click="expanded = false"
                @if(request()->routeIs($pattern)) aria-current="page" @endif
                @class(['flex min-h-12 items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-primary-50', 'bg-primary-50' => request()->routeIs($pattern)])>
                <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-surface-soft text-primary shadow-xs"><x-ui.icon :name="$icon" class="size-4" /></span>
                <span><strong class="block text-sm text-text-primary">{{ $label }}</strong><small class="text-xs text-text-muted">{{ $description }}</small></span>
            </a>
        @endforeach
    </div>
</div>
