@if($paginator->hasPages())
<nav class="sipora-pagination" aria-label="Navigasi halaman">
    @if($paginator->onFirstPage())<span aria-disabled="true">Sebelumnya</span>@else<a href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>@endif
    @if(isset($elements))
        @foreach($elements as $element)
            @if(is_string($element))<span aria-hidden="true">{{ $element }}</span>@else
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())<span aria-current="page" aria-label="Halaman {{ $page }}">{{ $page }}</span>@else<a href="{{ $url }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>@endif
                @endforeach
            @endif
        @endforeach
    @endif
    @if($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>@else<span aria-disabled="true">Berikutnya</span>@endif
</nav>
@endif
