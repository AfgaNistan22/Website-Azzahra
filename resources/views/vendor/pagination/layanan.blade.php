{{-- resources/views/vendor/pagination/layanan.blade.php --}}
@if ($paginator->hasPages())
    <nav class="ly-pg" role="navigation" aria-label="Navigasi halaman">

        <p class="ly-pg-info">
            Menampilkan <b>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</b>
            dari <b>{{ $paginator->total() }}</b> layanan
        </p>

        <ul class="ly-pg-list">

            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="ly-pg-item is-disabled" aria-disabled="true">
                    <span class="ly-pg-link ly-pg-edge"><i class="fas fa-arrow-left" aria-hidden="true"></i><span class="ly-pg-text">Sebelumnya</span></span>
                </li>
            @else
                <li class="ly-pg-item">
                    <a class="ly-pg-link ly-pg-edge" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"><i class="fas fa-arrow-left" aria-hidden="true"></i><span class="ly-pg-text">Sebelumnya</span></a>
                </li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="ly-pg-item is-dots" aria-disabled="true"><span class="ly-pg-link">…</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="ly-pg-item is-active" aria-current="page"><span class="ly-pg-link">{{ $page }}</span></li>
                        @else
                            <li class="ly-pg-item"><a class="ly-pg-link" href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li class="ly-pg-item">
                    <a class="ly-pg-link ly-pg-edge" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"><span class="ly-pg-text">Berikutnya</span><i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </li>
            @else
                <li class="ly-pg-item is-disabled" aria-disabled="true">
                    <span class="ly-pg-link ly-pg-edge"><span class="ly-pg-text">Berikutnya</span><i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </li>
            @endif

        </ul>

        {{-- progres halaman --}}
        <div class="ly-pg-bar" aria-hidden="true"><span style="width: {{ round($paginator->currentPage() / $paginator->lastPage() * 100) }}%"></span></div>

    </nav>
@endif