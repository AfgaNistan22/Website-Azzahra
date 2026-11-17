@extends('pages.auth.guest')

@section('title', 'Galeri | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman galeri --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/galeri-modern.css') }}?v={{ file_exists(public_path('template/css/galeri-modern.css')) ? filemtime(public_path('template/css/galeri-modern.css')) : time() }}">

    {{-- gaya galeri versi gen z: kartu polaroid miring, garis tebal, stiker, animasi memantul --}}
    <style>
        :root {
            --gz-ink: #2a0a0b;
            --gz-red: #7c1013;
            --gz-wine: #7c1013;
            --gz-gold: #d9ac4f;
            --gz-pop: cubic-bezier(.34, 1.56, .64, 1);
        }

        /* ---------- pita teks berjalan ---------- */
        .gz-ticker {
            overflow: hidden;
            margin: 4px 0 22px;
            padding: 11px 0;
            background: var(--gz-red);
            color: #fff;
            border-block: 2px solid var(--gz-ink);
            transform: rotate(-1deg);
            font-family: 'DM Sans', system-ui, sans-serif;
            font-weight: 800;
            font-size: .95rem;
            letter-spacing: .02em;
            white-space: nowrap;
        }
        .gz-ticker-row {
            display: flex;
            width: max-content;
            animation: gz-marquee 30s linear infinite;
        }
        .gz-ticker:hover .gz-ticker-row { animation-play-state: paused; }
        .gz-ticker-group { display: flex; align-items: center; flex-shrink: 0; }
        .gz-ticker-group span { padding: 0 14px; }
        .gz-ticker-group i {
            font-style: normal;
            color: var(--gz-gold);
            display: inline-block;
            animation: gz-spin 6s linear infinite;
        }
        @keyframes gz-marquee { to { transform: translateX(-50%); } }
        @keyframes gz-spin    { to { transform: rotate(360deg); } }

        /* ---------- chip filter ---------- */
        .gl-filter .gl-chip {
            border: 2px solid var(--gz-ink);
            background: #fff;
            color: var(--gz-ink);
            border-radius: 999px;
            font-weight: 700;
            box-shadow: 3px 3px 0 var(--gz-ink);
            transition: transform .3s var(--gz-pop), box-shadow .2s ease, background .2s ease, color .2s ease;
        }
        .gl-filter .gl-chip:hover { transform: translate(-1px, -2px) rotate(-2deg); box-shadow: 5px 5px 0 var(--gz-ink); }
        .gl-filter .gl-chip:active { transform: translate(3px, 3px); box-shadow: 0 0 0 var(--gz-ink); }
        .gl-filter .gl-chip.is-active { background: var(--gz-red); color: #fff; transform: rotate(-1.5deg); }
        .gl-filter .gl-chip:focus-visible { outline: 3px solid var(--gz-gold); outline-offset: 3px; }

        /* ---------- slider ---------- */
        .gz-stage { position: relative; }

        .gl-track {
            position: relative;               /* offsetLeft kartu dihitung dari track */
            display: flex;
            gap: 26px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-padding-inline: 12px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 34px 12px 40px;          /* ruang untuk kemiringan dan bayangan keras */
        }
        .gl-track::-webkit-scrollbar { display: none; }

        /* kartu polaroid */
        .gl-slider .gl-track .gl-shot.gz-card {
            flex: 0 0 calc((100% - 52px) / 3);
            scroll-snap-align: start;
            position: relative;
            display: block;
            aspect-ratio: auto;
            height: auto;
            padding: 10px 10px 0;
            margin: 0;
            overflow: visible;
            background: #fff;
            border: 2px solid var(--gz-ink);
            border-radius: 16px;
            box-shadow: 6px 6px 0 var(--c, var(--gz-red));
            text-align: left;
            cursor: zoom-in;
            transform: rotate(var(--r, 0deg));
            transition: transform .45s var(--gz-pop), box-shadow .3s ease;
        }
        .gl-slider .gl-track .gl-shot.gz-card[hidden] { display: none !important; }

        .gl-slider .gl-track .gl-shot.gz-card:hover,
        .gl-slider .gl-track .gl-shot.gz-card:focus-visible {
            transform: rotate(0deg) translateY(-12px) scale(1.03);
            box-shadow: 10px 14px 0 var(--c, var(--gz-red));
            z-index: 2;
        }
        .gl-slider .gl-track .gl-shot.gz-card:active { transform: rotate(0deg) scale(.98); }
        .gl-slider .gl-track .gl-shot.gz-card:focus-visible { outline: 3px solid var(--gz-gold); outline-offset: 4px; }

        .gz-frame {
            position: relative;
            display: block;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            border-radius: 9px;
            background: #f3e9e4;
        }
        .gl-slider .gl-track .gl-shot.gz-card .gz-frame img {
            position: static;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .7s ease;
        }
        .gz-card:hover .gz-frame img,
        .gz-card:focus-visible .gz-frame img { transform: scale(1.1) rotate(.6deg); }

        .gz-sticker {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            max-width: calc(100% - 64px);
            padding: 4px 11px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: var(--gz-gold);
            color: var(--gz-ink);
            border: 2px solid var(--gz-ink);
            border-radius: 999px;
            box-shadow: 2px 2px 0 var(--gz-ink);
            font: 800 .72rem/1.4 'DM Sans', system-ui, sans-serif;
            transform: rotate(-5deg);
            animation: gz-float 3.4s ease-in-out infinite alternate;
        }
        @keyframes gz-float {
            from { transform: translateY(0) rotate(-5deg); }
            to   { transform: translateY(-4px) rotate(-2deg); }
        }

        .gz-zoom {
            position: absolute;
            right: 10px;
            bottom: 10px;
            z-index: 2;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            background: #fff;
            color: var(--gz-red);
            border: 2px solid var(--gz-ink);
            border-radius: 50%;
            transform: scale(0) rotate(-90deg);
            transition: transform .4s var(--gz-pop);
        }
        .gz-card:hover .gz-zoom,
        .gz-card:focus-visible .gz-zoom { transform: scale(1) rotate(0deg); }

        .gz-cap {
            display: block;
            padding: 12px 4px 15px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--gz-ink);
            font: italic 600 1.02rem/1.3 'Playfair Display', Georgia, serif;
        }

        /* muncul memantul bergantian saat slider terlihat */
        .gl-track.gz-ready:not(.is-in) .gz-card { opacity: 0; }
        .gl-track.gz-ready.is-in .gz-card {
            animation: gz-pop .85s var(--gz-pop) backwards;
            animation-delay: calc(var(--n, 0) * 90ms);
        }
        @keyframes gz-pop {
            from { opacity: 0; transform: translateY(70px) rotate(calc(var(--r, 0deg) * 4)) scale(.8); }
            to   { opacity: 1; transform: translateY(0) rotate(var(--r, 0deg)) scale(1); }
        }

        /* ---------- tombol panah ---------- */
        .gz-nav {
            position: absolute;
            top: calc(50% - 26px);
            z-index: 3;
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--gz-ink);
            border-radius: 50%;
            background: var(--gz-gold);
            color: var(--gz-ink);
            box-shadow: 3px 3px 0 var(--gz-ink);
            cursor: pointer;
            transition: transform .25s var(--gz-pop), box-shadow .2s ease, opacity .2s ease;
        }
        .gz-nav:hover:not(:disabled) { transform: translate(-2px, -2px) scale(1.06); box-shadow: 5px 5px 0 var(--gz-ink); }
        .gz-nav:active:not(:disabled) { transform: translate(3px, 3px); box-shadow: 0 0 0 var(--gz-ink); }
        .gz-nav:focus-visible { outline: 3px solid var(--gz-red); outline-offset: 3px; }
        .gz-nav:disabled { opacity: .4; cursor: default; }
        .gz-nav[hidden] { display: none; }
        .gz-nav-prev { left: -16px; }
        .gz-nav-next { right: -16px; }

        /* ---------- titik halaman + putar/jeda ---------- */
        .gz-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 2px;
        }
        .gl-dots { display: flex; align-items: center; gap: 8px; }
        .gl-dots:empty { display: none; }
        .gl-dot {
            width: 12px;
            height: 12px;
            padding: 0;
            background: #fff;
            border: 2px solid var(--gz-ink);
            border-radius: 999px;
            cursor: pointer;
            transition: width .35s var(--gz-pop), background .25s ease;
        }
        .gl-dot.is-active { width: 34px; background: var(--gz-red); }
        .gl-dot:focus-visible { outline: 3px solid var(--gz-gold); outline-offset: 3px; }

        .gz-play {
            width: 36px;
            height: 36px;
            display: inline-grid;
            place-items: center;
            padding: 0;
            font-size: .8rem;
            color: var(--gz-ink);
            background: #fff;
            border: 2px solid var(--gz-ink);
            border-radius: 50%;
            box-shadow: 2px 2px 0 var(--gz-ink);
            cursor: pointer;
            transition: transform .25s var(--gz-pop), box-shadow .2s ease;
        }
        .gz-play:hover { transform: rotate(-12deg) scale(1.08); }
        .gz-play:active { transform: translate(2px, 2px); box-shadow: 0 0 0 var(--gz-ink); }
        .gz-play:focus-visible { outline: 3px solid var(--gz-gold); outline-offset: 3px; }

        /* ---------- responsif ---------- */
        @media (max-width: 991.98px) {
            .gl-slider .gl-track .gl-shot.gz-card { flex-basis: calc((100% - 26px) / 2); }
        }
        @media (max-width: 575.98px) {
            .gl-track { gap: 20px; }
            .gl-slider .gl-track .gl-shot.gz-card { flex-basis: 78%; }
            .gz-nav { display: none; }                      /* di HP cukup geser jari */
        }
        @media (hover: none) {
            .gz-zoom { transform: scale(1) rotate(0deg); }  /* layar sentuh tidak punya hover */
        }

        /* ---------- hormati pengaturan "kurangi gerakan" ---------- */
        @media (prefers-reduced-motion: reduce) {
            .gl-track { scroll-behavior: auto; }
            .gz-ticker-row, .gz-ticker-group i, .gz-sticker { animation: none; }
            .gl-track.gz-ready:not(.is-in) .gz-card { opacity: 1; }
            .gl-track.gz-ready.is-in .gz-card { animation: none; }
            .gz-card, .gz-card .gz-frame img, .gz-zoom { transition: none; }
        }
    </style>
@endpush

@section('content')

@php
    $daftarGaleri = isset($galeri) ? collect($galeri) : collect();

    $kategoriGaleri = $daftarGaleri
        ->map(function ($g) { return $g->kategori ?? null; })
        ->filter()->unique()->sort()->values();

    // Hanya foto yang punya berkas yang ditampilkan
    $fotoGaleri = $daftarGaleri->map(function ($g) {
        $foto = $g->foto ?? null;

        $g->src_tampil = $foto
            ? asset('storage/' . (\Illuminate\Support\Str::contains($foto, '/') ? $foto : 'foto_galeri/' . $foto))
            : null;

        return $g;
    })->filter(fn ($g) => $g->src_tampil)->values();

    $jumlahPerKategori = $fotoGaleri->groupBy(fn ($g) => \Illuminate\Support\Str::slug($g->kategori ?? 'lainnya'))->map->count();

    // kemiringan dan warna bayangan tiap kartu (bergilir)
    $miring = [-2.2, 1.6, -1.2, 2.4, -1.8, 1.1];
    $warna  = ['#e01c1d', '#d9ac4f', '#7c1013'];

    // teks untuk pita berjalan
    $kataPita = ['glow up', 'self care', 'spa day', 'me time', 'fresh look', 'treat yourself'];
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    TENTANG KAMI
                </span>

                <h1>
                    Galeri
                    <em>Azzahra.</em>
                </h1>

                <p>
                    Intip suasana salon, ruang perawatan, dan hasil glow up
                    pelanggan Azzahra Salon &amp; Spa.
                </p>

                <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    Booking Sekarang
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-images"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Momen • Suasana • Hasil</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        @if ($fotoGaleri->count() > 0)

            {{-- ===================== JUDUL ===================== --}}
            <div class="gl-head bkl-reveal">
                <div>
                    <span class="bkl-kicker">OUR GALLERY</span>
                    <h2 class="gl-title">Glow up check ✦</h2>
                    <p class="gl-sub">Geser terus, tap foto buat lihat lebih jelas.</p>
                </div>

                <span class="gl-total"><i class="fas fa-layer-group"></i> <span id="glJumlah">{{ $fotoGaleri->count() }}</span> Foto</span>
            </div>


            {{-- ===================== FILTER ===================== --}}
            @if ($kategoriGaleri->count() > 0)
                <div class="gl-filter bkl-reveal" id="galeriFilter" role="group" aria-label="Filter kategori galeri">
                    <button type="button" class="gl-chip is-active" data-filter="semua" aria-pressed="true">
                        Semua <span>{{ $fotoGaleri->count() }}</span>
                    </button>

                    @foreach ($kategoriGaleri as $kat)
                        @php $slug = \Illuminate\Support\Str::slug($kat); @endphp
                        <button type="button" class="gl-chip" data-filter="{{ $slug }}" aria-pressed="false">
                            {{ $kat }} <span>{{ $jumlahPerKategori[$slug] ?? 0 }}</span>
                        </button>
                    @endforeach
                </div>
            @endif


            {{-- ===================== PITA BERJALAN (hiasan) ===================== --}}
            <div class="gz-ticker" aria-hidden="true">
                <div class="gz-ticker-row">
                    @for ($ulang = 0; $ulang < 2; $ulang++)
                        <div class="gz-ticker-group">
                            @foreach ($kataPita as $kata)
                                <span>{{ $kata }}</span><i>✦</i>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>


            {{-- ===================== SLIDER ===================== --}}
            <div class="gl-slider" role="region" aria-roledescription="carousel" aria-label="Galeri foto Azzahra">

                <div class="gz-stage">

                    <button type="button" class="gz-nav gz-nav-prev" id="glPrev" aria-label="Foto sebelumnya">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>

                    <div class="gl-track" id="galeriGrid">

                        @foreach ($fotoGaleri as $g)

                            <button type="button"
                                    class="gl-shot gz-card"
                                    style="--r: {{ $miring[$loop->index % 6] }}deg; --c: {{ $warna[$loop->index % 3] }};"
                                    data-category="{{ \Illuminate\Support\Str::slug($g->kategori ?? 'lainnya') }}"
                                    data-kategori-label="{{ $g->kategori ?? '' }}"
                                    data-src="{{ $g->src_tampil }}"
                                    data-caption="{{ $g->judul ?? '' }}"
                                    aria-label="Perbesar foto{{ !empty($g->judul) ? ': ' . $g->judul : '' }}">

                                <span class="gz-frame">
                                    <img src="{{ $g->src_tampil }}" alt="{{ $g->judul ?? 'Galeri Azzahra' }}" loading="lazy">

                                    @if (!empty($g->kategori))
                                        <span class="gz-sticker">{{ $g->kategori }}</span>
                                    @endif

                                    <span class="gz-zoom" aria-hidden="true"><i class="fas fa-expand-alt"></i></span>
                                </span>

                                <span class="gz-cap">{{ !empty($g->judul) ? $g->judul : (!empty($g->kategori) ? $g->kategori : 'Azzahra ✦') }}</span>
                            </button>

                        @endforeach

                    </div>

                    <button type="button" class="gz-nav gz-nav-next" id="glNext" aria-label="Foto berikutnya">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>

                </div>

                <div class="gz-controls">
                    <div class="gl-dots" id="glDots" role="group" aria-label="Halaman galeri"></div>

                    <button type="button" class="gz-play" id="glPlay" aria-pressed="false" aria-label="Jeda slideshow otomatis">
                        <i class="fas fa-pause" aria-hidden="true"></i>
                    </button>
                </div>

            </div>


            {{-- ===================== PENUTUP ===================== --}}
            <section class="gl-band bkl-reveal">
                <div>
                    <h2>Udah kepo? Saatnya glow up.</h2>
                    <p>Pilih perawatan favoritmu, lalu booking jadwalnya bareng Azzahra.</p>
                </div>

                <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    Booking Sekarang
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>
            </section>

        @else

            {{-- ===================== KOSONG ===================== --}}
            <div class="bkl-empty bkl-reveal">

                <div class="bkl-empty-art">
                    <span class="bkl-empty-ring"></span>
                    <i class="far fa-images"></i>
                    <span class="bkl-empty-spark">✦</span>
                </div>

                <span class="bkl-kicker">SEGERA HADIR</span>

                <h3>Galeri belum tersedia</h3>

                <p>Foto-foto terbaru akan segera kami tampilkan di sini.</p>

            </div>

        @endif

    </div>

</main>

{{-- LIGHTBOX --}}
<div class="gl-lb" id="pgLightbox" role="dialog" aria-modal="true" aria-label="Pratinjau foto" aria-hidden="true">
    <button type="button" class="gl-lb-btn gl-lb-close" id="pgLbClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <button type="button" class="gl-lb-btn gl-lb-prev" id="pgLbPrev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
    <button type="button" class="gl-lb-btn gl-lb-next" id="pgLbNext" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
    <figure>
        <img src="" alt="" id="pgLbImg">
        <figcaption>
            <div class="gl-lb-title" id="pgLbCap"></div>
            <span class="gl-lb-count" id="pgLbCount"></span>
        </figcaption>
    </figure>
</div>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

{{-- slider, filter kategori, dan lightbox --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    var filterBox = document.getElementById('galeriFilter');
    var track     = document.getElementById('galeriGrid');
    if (!track) return;

    var shots  = Array.prototype.slice.call(track.querySelectorAll('.gl-shot'));
    var jumlah = document.getElementById('glJumlah');

    var btnGeserKiri  = document.getElementById('glPrev');
    var btnGeserKanan = document.getElementById('glNext');
    var kotakDot      = document.getElementById('glDots');
    var btnPutar      = document.getElementById('glPlay');
    var slider        = track.closest('.gl-slider');

    var kurangiGerak = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // kartu disembunyikan dulu hanya bila JS aktif, lalu dimunculkan satu per satu
    track.classList.add('gz-ready');

    /* ---------- Slider ---------- */
    function kartu() {
        return shots.filter(function (s) { return !s.hidden; });
    }

    function langkah() {
        var k = kartu()[0];
        if (!k) return track.clientWidth;
        var cs  = window.getComputedStyle(track);
        var gap = parseFloat(cs.columnGap || cs.gap) || 0;
        return k.offsetWidth + gap;
    }

    function perLayar() {
        return Math.max(1, Math.round(track.clientWidth / langkah()));
    }

    function jumlahHalaman() {
        return Math.max(1, Math.ceil(kartu().length / perLayar()));
    }

    function diUjung() {
        return track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
    }

    function halamanAktif() {
        var maks = jumlahHalaman() - 1;
        if (diUjung()) return maks;
        return Math.min(maks, Math.floor(Math.round(track.scrollLeft / langkah()) / perLayar()));
    }

    function ke(halaman) {
        track.scrollTo({ left: halaman * perLayar() * langkah(), behavior: 'smooth' });
    }

    function bangunDot() {
        kotakDot.innerHTML = '';
        var n = jumlahHalaman();
        if (n <= 1) return;

        for (var i = 0; i < n; i++) {
            (function (idx) {
                var d = document.createElement('button');
                d.type = 'button';
                d.className = 'gl-dot';
                d.setAttribute('aria-label', 'Ke halaman ' + (idx + 1));
                d.addEventListener('click', function () { sentuh(); ke(idx); });
                kotakDot.appendChild(d);
            })(i);
        }
    }

    function segarkan() {
        var banyak = kartu().length > perLayar();

        btnGeserKiri.hidden  = !banyak;
        btnGeserKanan.hidden = !banyak;
        btnPutar.hidden      = !banyak;

        btnGeserKiri.disabled  = track.scrollLeft <= 2;
        btnGeserKanan.disabled = diUjung();

        var aktif = halamanAktif();
        Array.prototype.forEach.call(kotakDot.children, function (d, i) {
            var on = i === aktif;
            d.classList.toggle('is-active', on);
            if (on) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
        });
    }

    // urutan kemunculan (jeda bertahap antar kartu)
    function urutkan() {
        kartu().forEach(function (s, i) {
            s.style.setProperty('--n', String(Math.min(i, 8)));
        });
    }

    function mulaiAnimasi() {
        track.classList.remove('is-in');
        void track.offsetWidth;               // paksa browser mengulang animasi
        urutkan();
        track.classList.add('is-in');
    }

    function atur() {
        bangunDot();
        segarkan();
    }

    btnGeserKiri.addEventListener('click', function () {
        sentuh();
        track.scrollBy({ left: -langkah() * perLayar(), behavior: 'smooth' });
    });
    btnGeserKanan.addEventListener('click', function () {
        sentuh();
        track.scrollBy({ left: langkah() * perLayar(), behavior: 'smooth' });
    });

    var menunggu = false;
    track.addEventListener('scroll', function () {
        if (menunggu) return;
        menunggu = true;
        window.requestAnimationFrame(function () { menunggu = false; segarkan(); });
    }, { passive: true });

    window.addEventListener('resize', atur);
    window.addEventListener('load', atur);

    /* ---------- Putar otomatis ---------- */
    var jedaManual   = kurangiGerak;   // pengguna yang minta kurangi gerakan: tidak diputar otomatis
    var kursorDiatas = false;
    var fokusDidalam = false;
    var terlihatDiLayar = false;
    var sentuhTerakhir = 0;
    var pewaktu = null;

    function sentuh() { sentuhTerakhir = Date.now(); }

    function perbaruiTombolPutar() {
        var ikon = btnPutar.querySelector('i');
        ikon.className = jedaManual ? 'fas fa-play' : 'fas fa-pause';
        btnPutar.setAttribute('aria-pressed', jedaManual ? 'true' : 'false');
        btnPutar.setAttribute('aria-label', jedaManual ? 'Putar slideshow otomatis' : 'Jeda slideshow otomatis');
    }

    function geserOtomatis() {
        if (jedaManual || kursorDiatas || fokusDidalam || !terlihatDiLayar || document.hidden) return;
        if (lb.classList.contains('is-open')) return;
        if (Date.now() - sentuhTerakhir < 3000) return;
        if (kartu().length <= perLayar()) return;

        if (diUjung()) {
            track.scrollTo({ left: 0, behavior: 'smooth' });      // kembali ke awal
        } else {
            track.scrollBy({ left: langkah() * perLayar(), behavior: 'smooth' });
        }
    }

    btnPutar.addEventListener('click', function () {
        jedaManual = !jedaManual;
        perbaruiTombolPutar();
    });

    slider.addEventListener('mouseenter', function () { kursorDiatas = true; });
    slider.addEventListener('mouseleave', function () { kursorDiatas = false; });
    slider.addEventListener('focusin',    function () { fokusDidalam = true; });
    slider.addEventListener('focusout',   function () { fokusDidalam = false; });
    track.addEventListener('pointerdown', sentuh, { passive: true });
    track.addEventListener('wheel',       sentuh, { passive: true });

    perbaruiTombolPutar();
    pewaktu = window.setInterval(geserOtomatis, 3800);

    /* ---------- Animasi muncul saat slider terlihat ---------- */
    if ('IntersectionObserver' in window) {
        var pengamat = new IntersectionObserver(function (entri) {
            entri.forEach(function (e) {
                terlihatDiLayar = e.isIntersecting;

                if (e.isIntersecting && !track.classList.contains('is-in')) {
                    mulaiAnimasi();
                }
            });
        }, { threshold: 0.2 });

        pengamat.observe(slider);
    } else {
        terlihatDiLayar = true;
        mulaiAnimasi();
    }

    /* ---------- Filter kategori ---------- */
    function terapkan(kategori) {
        var tampil = 0;

        shots.forEach(function (s) {
            var cocok = kategori === 'semua' || s.getAttribute('data-category') === kategori;
            s.hidden = !cocok;
            if (cocok) tampil++;
        });

        if (jumlah) jumlah.textContent = tampil;

        track.scrollTo({ left: 0, behavior: 'auto' });
        atur();
        mulaiAnimasi();
    }

    if (filterBox) {
        filterBox.addEventListener('click', function (e) {
            var chip = e.target.closest ? e.target.closest('.gl-chip') : null;
            if (!chip) return;

            filterBox.querySelectorAll('.gl-chip').forEach(function (c) {
                var aktif = c === chip;
                c.classList.toggle('is-active', aktif);
                c.setAttribute('aria-pressed', aktif ? 'true' : 'false');
            });

            terapkan(chip.getAttribute('data-filter'));
        });
    }

    urutkan();
    atur();

    /* ---------- Lightbox ---------- */
    var lb     = document.getElementById('pgLightbox');
    var lbImg  = document.getElementById('pgLbImg');
    var lbCap  = document.getElementById('pgLbCap');
    var lbCnt  = document.getElementById('pgLbCount');
    var btnTut = document.getElementById('pgLbClose');
    var btnPrv = document.getElementById('pgLbPrev');
    var btnNxt = document.getElementById('pgLbNext');

    var daftar = [];      // foto yang sedang terlihat
    var posisi = 0;
    var pemicu = null;    // elemen yang dikembalikan fokusnya

    function pratinjau(idx) {
        var s = daftar[idx];
        if (!s) return;
        new Image().src = s.getAttribute('data-src');
    }

    function tampilkan(idx) {
        if (!daftar.length) return;
        posisi = (idx + daftar.length) % daftar.length;

        var s = daftar[posisi];
        var cap = s.getAttribute('data-caption') || '';
        var kat = s.getAttribute('data-kategori-label') || '';

        lbImg.classList.add('is-swap');

        setTimeout(function () {
            lbImg.src = s.getAttribute('data-src');
            lbImg.alt = cap || 'Galeri Azzahra';
            lbImg.classList.remove('is-swap');
        }, 120);

        lbCap.textContent = cap || kat;
        lbCnt.textContent = (posisi + 1) + ' / ' + daftar.length;

        var banyak = daftar.length > 1;
        btnPrv.hidden = !banyak;
        btnNxt.hidden = !banyak;

        pratinjau((posisi + 1) % daftar.length);
        pratinjau((posisi - 1 + daftar.length) % daftar.length);
    }

    function buka(shot) {
        daftar = kartu();
        pemicu = shot;

        lb.classList.add('is-open');
        lb.setAttribute('aria-hidden', 'false');
        document.body.classList.add('gl-lock');

        tampilkan(daftar.indexOf(shot));
        btnTut.focus();
    }

    function tutup() {
        lb.classList.remove('is-open');
        lb.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('gl-lock');
        sentuh();

        // slider ikut berhenti di foto terakhir yang dilihat di lightbox
        var terakhir = daftar[posisi];
        if (terakhir) {
            track.scrollTo({ left: Math.max(0, terakhir.offsetLeft - 12), behavior: 'auto' });
            terakhir.focus({ preventScroll: true });
        } else if (pemicu) {
            pemicu.focus();
        }
    }

    track.addEventListener('click', function (e) {
        var shot = e.target.closest ? e.target.closest('.gl-shot') : null;
        if (shot) buka(shot);
    });

    btnTut.addEventListener('click', tutup);
    btnPrv.addEventListener('click', function () { tampilkan(posisi - 1); });
    btnNxt.addEventListener('click', function () { tampilkan(posisi + 1); });

    lb.addEventListener('click', function (e) {
        if (e.target === lb) tutup();
    });

    document.addEventListener('keydown', function (e) {
        if (!lb.classList.contains('is-open')) return;

        if (e.key === 'Escape')     { tutup(); }
        if (e.key === 'ArrowLeft')  { tampilkan(posisi - 1); }
        if (e.key === 'ArrowRight') { tampilkan(posisi + 1); }

        // jaga fokus tetap di dalam lightbox
        if (e.key === 'Tab') {
            var fokus = [btnTut, btnPrv, btnNxt].filter(function (b) { return !b.hidden; });
            var pertama = fokus[0], terakhir = fokus[fokus.length - 1];

            if (e.shiftKey && document.activeElement === pertama) { e.preventDefault(); terakhir.focus(); }
            else if (!e.shiftKey && document.activeElement === terakhir) { e.preventDefault(); pertama.focus(); }
        }
    });

    // geser jari pada layar sentuh (lightbox)
    var x0 = null;
    lb.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
        if (x0 === null) return;
        var dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 50) tampilkan(posisi + (dx < 0 ? 1 : -1));
        x0 = null;
    });
});
</script>

@endsection