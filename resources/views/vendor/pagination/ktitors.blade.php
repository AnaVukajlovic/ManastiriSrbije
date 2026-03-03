@if ($paginator->hasPages())
<nav class="kt-pnav" role="navigation" aria-label="Pagination Navigation">
  <div class="kt-pnav__inner">

    {{-- Prev --}}
    @if ($paginator->onFirstPage())
      <span class="kt-pnav__btn is-disabled">← Prethodna</span>
    @else
      <a class="kt-pnav__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Prethodna</a>
    @endif

    {{-- Info --}}
    <span class="kt-pnav__info">
      Strana <b>{{ $paginator->currentPage() }}</b> / <b>{{ $paginator->lastPage() }}</b>
      <span class="muted"> (ukupno: {{ $paginator->total() }})</span>
    </span>

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a class="kt-pnav__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Sledeća →</a>
    @else
      <span class="kt-pnav__btn is-disabled">Sledeća →</span>
    @endif

  </div>
</nav>
@endif