@extends('pages.auth.guest')

@section('title', 'Perawatan & Layanan')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman layanan --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/layanan-modern.css') }}?v={{ file_exists(public_path('template/css/layanan-modern.css')) ? filemtime(public_path('template/css/layanan-modern.css')) : time() }}">
@endpush

@section('content')

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    AZZAHRA SALON &amp; SPA MUSLIMAH
                </span>

                <h1>
                    Perawatan
                    <em>&amp; Layanan.</em>
                </h1>

                <p>
                    Temukan perawatan kecantikan yang sesuai dengan kebutuhan Anda.
                    Nikmati pengalaman salon dan spa muslimah dengan suasana nyaman,
                    privat, dan pelayanan profesional.
                </p>

                {{-- PENCARIAN --}}
                <form class="ly-search" action="{{ route('perawatan-layanan.index') }}" method="GET" role="search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input type="text" name="cari" value="{{ $pencarian }}"
                           placeholder="Cari facial, creambath, atau body spa..."
                           aria-label="Cari perawatan">
                    @if ($kategoriTerpilih)
                        <input type="hidden" name="kategori" value="{{ $kategoriTerpilih }}">
                    @endif
                    <button type="submit" class="bkl-btn bkl-btn-maroon">Cari</button>
                </form>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-spa"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Salon • Spa • Muslimah</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        {{-- FILTER KATEGORI --}}
        <section class="ly-section bkl-reveal">
            <div class="ly-head">
                <span class="bkl-kicker">Kategori</span>
                <h2 class="ly-title">Jelajahi Perawatan Kami</h2>
                <p class="ly-sub">
                    Pilih kategori untuk menemukan layanan yang sesuai
                    dengan kebutuhan kecantikan Anda.
                </p>
            </div>

            <div class="ly-cats">
                <a
                    href="{{ route('perawatan-layanan.index', [
                        'cari' => $pencarian ?: null
                    ]) }}"
                    class="ly-cat {{ !$kategoriTerpilih ? 'is-active' : '' }}"
                >
                    <i class="fas fa-th-large" aria-hidden="true"></i>
                    Semua Perawatan
                </a>

                @foreach ($kategori as $itemKategori)
                    <a
                        href="{{ route('perawatan-layanan.index', [
                            'kategori' => $itemKategori->id,
                            'cari' => $pencarian ?: null
                        ]) }}"
                        class="ly-cat {{ (string) $kategoriTerpilih === (string) $itemKategori->id ? 'is-active' : '' }}"
                    >
                        <i class="fas fa-spa" aria-hidden="true"></i>
                        {{ $itemKategori->nama }}
                    </a>
                @endforeach
            </div>
        </section>

        {{-- DAFTAR LAYANAN --}}
        <section class="ly-section">

            <div class="ly-head bkl-reveal">
                <span class="bkl-kicker">Layanan</span>
                <h2 class="ly-title">
                    @if ($kategoriTerpilih)
                        {{ optional($kategori->firstWhere('id', $kategoriTerpilih))->nama
                            ?? 'Pilihan Perawatan' }}
                    @else
                        Pilihan Perawatan Kami
                    @endif
                </h2>

                <p class="ly-sub">
                    Temukan perawatan favorit Anda dari
                    {{ $perawatan->total() }} layanan tersedia.
                </p>
            </div>

            <div class="ly-grid">
                @forelse ($perawatan as $item)
                    <article class="ly-card bkl-reveal">

                        {{-- FOTO PERAWATAN --}}
                        @php
                            $foto = $item->foto
                                ? asset('storage/' . $item->foto)
                                : asset('template/img/service-default.png');
                        @endphp

                        <div class="ly-img">
                            <img
                                src="{{ $foto }}"
                                alt="{{ $item->nama }}"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('template/img/service-default.png') }}';"
                            >

                            {{-- KATEGORI --}}
                            @if ($item->kategori)
                                <span class="ly-chip">
                                    <i class="fas fa-spa" aria-hidden="true"></i>
                                    {{ $item->kategori->nama }}
                                </span>
                            @endif
                        </div>

                        <div class="ly-body">

                            {{-- NAMA PERAWATAN --}}
                            <h3>{{ $item->nama }}</h3>

                            {{-- DESKRIPSI --}}
                            <p class="ly-desc">
                                {{ $item->deskripsi
                                    ?: 'Nikmati pengalaman perawatan dengan suasana nyaman dan pelayanan profesional dari Azzahra.' }}
                            </p>

                            {{-- DURASI DAN HARGA --}}
                            <div class="ly-meta">
                                <div class="ly-duration">
                                    <i class="far fa-clock" aria-hidden="true"></i>
                                    <span>
                                        {{ $item->durasi ?: '-' }}
                                        @if ($item->durasi)
                                            menit
                                        @endif
                                    </span>
                                </div>

                                <div class="ly-price-box">
                                    <small>Harga perawatan</small>
                                    <strong>Rp{{ number_format($item->harga, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            {{-- TOMBOL --}}
                            <a
                                href="{{ route('layanan.detail', $item->id) }}"
                                class="bkl-btn bkl-btn-maroon ly-book service-book-btn"
                                aria-label="Lihat layanan {{ $item->nama }}"
                            >
                                <i class="far fa-calendar-check" aria-hidden="true"></i>
                                Lihat Layanan
                                <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                            </a>

                        </div>
                    </article>
                @empty
                    <div class="ly-empty bkl-reveal">
                        <i class="fas fa-spa" aria-hidden="true"></i>

                        <h3>Perawatan Belum Ditemukan</h3>

                        <p>
                            Maaf, layanan yang Anda cari belum tersedia.
                            Coba kata kunci atau kategori lainnya.
                        </p>

                        <a href="{{ route('perawatan-layanan.index') }}" class="bkl-btn bkl-btn-maroon">
                            <i class="fas fa-th-large" aria-hidden="true"></i>
                            Lihat Semua Perawatan
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if ($perawatan->hasPages())
                <div class="ly-pagination bkl-reveal">
                    {{ $perawatan->withQueryString()->links('vendor.pagination.layanan') }}
                </div>
            @endif

        </section>
    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection