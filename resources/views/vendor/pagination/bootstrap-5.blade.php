@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    {!! __('Menampilkan') !!}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('hingga') !!}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('dari') !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('hasil') !!}
                </p>
            </div>

            <div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                        </li>
                    @endif

                    @php
                        $current = $paginator->currentPage();
                        $last = $paginator->lastPage();
                    @endphp

                    {{-- Halaman pertama --}}
                    <li class="page-item {{ ($current == 1) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>

                    {{-- Titik ... --}}
                    @if ($current > 3)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif

                    {{-- Halaman sekitar --}}
                    @for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++)
                        <li class="page-item {{ ($i == $current) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Titik ... --}}
                    @if ($current < $last - 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif

                    {{-- Halaman terakhir --}}
                    @if ($last > 1)
                        <li class="page-item {{ ($current == $last) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
