<nav>
    <ul class="pagination">
        @if($paginator->onFirstPage())

        @else
            <li class="page-item">
                <a href="{{$paginator->previousPageUrl()}}" class="page-link" aria-label="Previous">
                    <span class="iconify" data-icon="la:angle-left"></span>
                </a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (count($element) < 2)

            @else
                @foreach ($element as $key=> $el)
                    <li class="page-item {{ $key == $paginator->currentPage() ? 'active' : '' }}"><a class="page-link" href="{{ $el }}">{{$key}}</a></li>
                @endforeach
            @endif
        @endforeach

        @if($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                    <span class="iconify" data-icon="la:angle-right"></span>
                </a>
            </li>
        @endif
    </ul>
</nav>
