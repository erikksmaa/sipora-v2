@extends('layouts.public')
@section('title', 'Pencarian · SIPORA')
@section('meta_description', 'Cari Activity, Community, Opportunity, Program, Forum, dan Portfolio Pemuda publik dalam ekosistem SIPORA Kabupaten Pemalang.')
@section('canonical', route('search.index'))
@section('content')
<section class="public-page-intro public-page-intro--search"><div class="relative mx-auto max-w-5xl text-center"><p class="text-xs font-bold uppercase tracking-[.18em] text-accent-300">Pencarian SIPORA</p><h1 class="font-heading mt-2 text-4xl font-bold tracking-tight sm:text-5xl">Temukan ruang tumbuhmu</h1><p class="mx-auto mt-3 max-w-2xl text-white/85">Cari lintas Activity, Community, Opportunity, Program, dan Portfolio Pemuda publik.</p><form class="mx-auto mt-7 flex max-w-3xl flex-col gap-3 rounded-2xl border border-border bg-white p-3 shadow-md sm:flex-row" method="GET" action="{{ route('search.index') }}"><label class="relative flex-1"><span class="sr-only">Kata pencarian</span><x-ui.icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-text-muted" /><input class="landing-search h-12 pl-11" type="search" name="q" value="{{ $query }}" maxlength="120" placeholder="Teknologi, olahraga, beasiswa, pemuda..."></label><button class="landing-btn-primary" type="submit">Cari sekarang</button></form></div></section>
<div class="bg-surface-soft px-5 py-12"><div class="mx-auto max-w-6xl">
@if($query === '')
    <x-ui.empty-state icon="search" title="Mulai pencarianmu" description="Masukkan kata kunci untuk menemukan berbagai ruang pengembangan pemuda di SIPORA." />
@else
    <div class="mb-9"><p class="text-xs font-bold uppercase tracking-wider text-accent-600">Hasil pencarian</p><h2 class="public-section-title mt-1">Hasil untuk “{{ $query }}”</h2></div>
    <div class="space-y-12">
    @foreach([['Activity',$activities,'activities.index','activity'],['Community',$communities,'communities.index','building'],['Opportunity',$opportunities,'opportunities.index','briefcase'],['Program',$programs,'programs.index','notebook'],['Pemuda',$youth,'youth-directory.index','users']] as [$label,$items,$routeName,$icon])
        <section><div class="flex items-center justify-between gap-3 border-b border-border pb-4"><div class="flex items-center gap-3"><span class="grid size-10 place-items-center rounded-xl bg-primary-50 text-primary shadow-xs"><x-ui.icon :name="$icon" class="size-5" /></span><h2 class="text-xl font-bold text-text-primary">{{ $label }}</h2></div><a class="public-link" href="{{ route($routeName,['q'=>$query]) }}">Lihat semua <x-ui.icon name="chevron-right" class="size-4" /></a></div>
        <div class="mt-5 grid gap-5 md:grid-cols-2">@forelse($items as $item) @if($label==='Activity')<x-discovery.activity-card :activity="$item" compact />@elseif($label==='Community')<x-discovery.community-card :community="$item" compact />@elseif($label==='Opportunity')<x-discovery.opportunity-card :opportunity="$item" compact />@elseif($label==='Program')<x-discovery.program-card :program="$item" compact />@else<x-discovery.youth-card :profile="$item" />@endif @empty<x-ui.empty-state class="col-span-full" :title="'Tidak ada '.$label.' publik yang cocok.'" />@endforelse</div></section>
    @endforeach
    <section><div class="flex items-center justify-between gap-3 border-b border-border pb-4"><h2 class="text-xl font-bold text-text-primary">Forum</h2><a class="public-link" href="{{ route('forum.index') }}">Lihat diskusi <x-ui.icon name="chevron-right" class="size-4" /></a></div><div class="mt-5 grid gap-4 md:grid-cols-2">@forelse($forumThreads as $thread)<article class="sipora-card p-5"><p class="text-xs font-bold uppercase text-interactive">{{ $thread->category->name }}</p><h3 class="mt-2 text-lg font-bold"><a href="{{ route('forum.show', $thread) }}">{{ $thread->title }}</a></h3><p class="mt-2 line-clamp-2 text-sm text-text-secondary">{{ $thread->body }}</p></article>@empty<p class="text-sm text-text-muted">Tidak ada diskusi publik yang cocok.</p>@endforelse</div></section>
    </div>
@endif
</div></div>
@endsection
