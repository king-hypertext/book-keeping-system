@if ($paginator->hasPages())
    <ul class="pagination pagination-sm">
        @if ($paginator->onFirstPage())
            <li class="page-item"><a class="page-link text-white disabled">prev</a></li>
            <li class="page-item active" aria-current="page">
                <a class="page-link  bg-white text-black border-white">{{ $paginator->currentPage() }}</a>
            </li>
        @else
            <li class="page-item active" aria-current="page">
                <a class="page-link text-white border-white bg-dark" href="{{ $paginator->previousPageUrl() }}">Prev</a>
            </li>
            <li class="page-item active" aria-current="page">
                <a class="page-link  bg-white text-black border-white">{{ $paginator->currentPage() }}</a>
            </li>
            <li class="page-item"><a class="page-link text-white disabled">Next</a></li>
        @endif
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link text-white" href="{{ $paginator->nextPageUrl() }}">next</a></li>
        @endif
    </ul>
@endif
<style>
    .page-link.disabled {
        pointer-events: none;
        border-color: #6c757d !important;
        background: #6c757d;
    }
</style>
