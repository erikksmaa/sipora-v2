@props(['eligible', 'title' => null, 'reasons' => []])
<aside @class(['rounded-2xl border p-5', 'border-emerald-200 bg-success-soft' => $eligible, 'border-amber-200 bg-warning-soft' => ! $eligible])>
    <div class="flex items-start gap-3"><span @class(['grid size-10 shrink-0 place-items-center rounded-xl bg-white', 'text-success' => $eligible, 'text-warning' => ! $eligible])><x-ui.icon :name="$eligible ? 'badge-check' : 'alert-circle'" /></span><div><h2 class="font-bold text-text-primary">{{ $title ?? ($eligible ? 'Program siap dievaluasi' : 'Program belum siap dievaluasi') }}</h2>@if(count($reasons))<ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-text-secondary">@foreach($reasons as $reason)<li>{{ $reason }}</li>@endforeach</ul>@endif</div></div>
</aside>
