@if ($paginator->hasPages())
    <nav class="vt-pagination" role="navigation" aria-label="Pagination Navigation">

        <div class="vt-pagination-side">
            @if ($paginator->onFirstPage())
                <span class="vt-page-btn disabled">
                    ‹ Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="vt-page-btn">
                    ‹ Previous
                </a>
            @endif
        </div>

        <div class="vt-page-info">
            @if(method_exists($paginator, 'total'))
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
            @else
                Showing results
            @endif
        </div>

        <div class="vt-pagination-side right">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="vt-page-btn">
                    Next ›
                </a>
            @else
                <span class="vt-page-btn disabled">
                    Next ›
                </span>
            @endif
        </div>

    </nav>
@endif