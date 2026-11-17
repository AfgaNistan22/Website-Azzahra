@extends('pages.auth.guest')

@section('title', $layanan->nama . ' - Azzahra Salon & Spa Muslimah')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman detail --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/detail-modern.css') }}?v={{ file_exists(public_path('template/css/detail-modern.css')) ? filemtime(public_path('template/css/detail-modern.css')) : time() }}">
@endpush

@section('content')

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero dt-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                {{-- BREADCRUMB --}}
                <nav class="dt-crumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house" aria-hidden="true"></i> Beranda</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <a href="{{ route('home', ['semua' => 1]) }}">Layanan</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span>{{ $layanan->nama }}</span>
                </nav>

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    AZZAHRA SALON &amp; SPA
                </span>

                <h1 class="dt-h1">{{ $layanan->nama }}</h1>

                @if ($layanan->kategori)
                    <span class="dt-cat">
                        <i class="fa-solid fa-leaf" aria-hidden="true"></i>
                        {{ $layanan->kategori->nama }}
                    </span>
                @endif

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fa-solid fa-spa"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Beauty • Relaxation • Self Care</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        <div class="dt-card bkl-reveal">

            {{-- GAMBAR LAYANAN --}}
            <div class="dt-image">

                <img
                    src="{{ asset('storage/' . $layanan->foto) }}"
                    alt="{{ $layanan->nama }}"
                    loading="lazy"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >

                <div class="dt-ph" style="display: none;">
                    <i class="fa-solid fa-spa"></i>
                </div>

                <span class="dt-image-label">
                    <i class="fa-solid fa-spa" aria-hidden="true"></i>
                    Perawatan Muslimah
                </span>

                <div class="dt-image-caption">Beauty • Relaxation • Self Care</div>
            </div>

            {{-- INFORMASI LAYANAN --}}
            <div class="dt-info">

                <span class="bkl-kicker">Detail Perawatan</span>

                <p class="dt-intro">
                    Waktunya memberikan perhatian lebih untuk diri sendiri.
                    Nikmati pengalaman perawatan yang nyaman dan menenangkan
                    bersama Azzahra Salon &amp; Spa Muslimah.
                </p>

                {{-- INFORMASI DURASI --}}
                <div class="dt-meta">

                    <div class="dt-meta-item">
                        <span class="dt-meta-icon"><i class="fa-regular fa-clock" aria-hidden="true"></i></span>
                        <div>
                            <small>Durasi perawatan</small>
                            <strong>{{ $layanan->durasi }} menit</strong>
                        </div>
                    </div>

                    <div class="dt-meta-item">
                        <span class="dt-meta-icon"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
                        <div>
                            <small>Pelayanan</small>
                            <strong>Khusus Muslimah</strong>
                        </div>
                    </div>

                </div>

                {{-- DESKRIPSI --}}
                <div class="dt-desc">
                    <h3>
                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                        Tentang Perawatan
                    </h3>

                    <p>
                        {{ $layanan->deskripsi ?: 'Informasi lengkap mengenai layanan ini akan segera tersedia. Hubungi kami untuk mengetahui detail perawatan.' }}
                    </p>
                </div>

                {{-- HARGA --}}
                <div class="dt-price">
                    <div>
                        <span class="dt-price-label">
                            <i class="fa-solid fa-tag" aria-hidden="true"></i>
                            Harga perawatan
                        </span>

                        <strong>
                            <small>Rp</small>{{ number_format($layanan->harga, 0, ',', '.') }}
                        </strong>

                        <span class="dt-price-note">
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                            Harga per satu kali perawatan
                        </span>
                    </div>

                    <i class="fa-solid fa-spa dt-price-deco" aria-hidden="true"></i>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="dt-actions">

                    <a href="{{ route('perawatan-layanan.index') }}" class="bkl-btn dt-back">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        <span>Kembali</span>
                    </a>

                    <a href="{{ route('booking.create', ['perawatan' => $layanan->id]) }}" class="bkl-btn bkl-btn-maroon">
                        <span>Pesan Perawatan</span>
                        <i class="fa-solid fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                    </a>

                </div>

                {{-- CATATAN --}}
                <div class="dt-note">
                    <i class="fa-solid fa-shield-heart" aria-hidden="true"></i>
                    <span>
                        Ruang perawatan yang nyaman, privat, dan dirancang
                        untuk memberikan pengalaman terbaik bagi muslimah.
                    </span>
                </div>

            </div>
        </div>

        {{-- FOOTER KECIL --}}
        <p class="bkl-footer-note bkl-reveal">
            <i class="fa-solid fa-leaf" aria-hidden="true"></i>
            Saatnya memanjakan diri &nbsp;•&nbsp; Azzahra Salon &amp; Spa Muslimah
        </p>

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection