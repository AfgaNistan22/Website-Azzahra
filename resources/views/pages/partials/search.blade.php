@extends('pages.auth.guest')

@section('title', 'Cari Layanan - Azzahra Salon & Spa Muslimah')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman cari --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/search-modern.css') }}?v={{ file_exists(public_path('template/css/search-modern.css')) ? filemtime(public_path('template/css/search-modern.css')) : time() }}">
@endpush

@section('content')

@php
    // satu sumber untuk judul halaman, supaya tidak diulang-ulang
    $modeKategori = !empty($selectedCategory) && request('semua') != '1';
    $modeCari     = !$modeKategori && !empty($search) && request('semua') != '1';
    $judulBagian  = $modeKategori ? $selectedCategory->nama : ($modeCari ? 'Hasil Pencarian' : 'Semua Layanan');
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                {{-- BREADCRUMB --}}
                <nav class="sr-crumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house" aria-hidden="true"></i> Beranda</a>
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span>{{ $judulBagian }}</span>
                </nav>

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    AZZAHRA SALON &amp; SPA MUSLIMAH
                </span>

                <h1>
                    @if($modeKategori)
                        Layanan <em>{{ $selectedCategory->nama }}</em>
                    @elseif($modeCari)
                        Hasil <em>Pencarian.</em>
                    @else
                        Temukan <em>Perawatan Terbaik.</em>
                    @endif
                </h1>

                <p>
                    @if($modeKategori)
                        Temukan perawatan {{ $selectedCategory->nama }}
                        yang sesuai dengan kebutuhan dan kenyamanan Anda.
                    @elseif($modeCari)
                        Menampilkan hasil pencarian untuk
                        <strong>"{{ $search }}"</strong>.
                    @else
                        Luangkan waktu untuk diri sendiri. Jelajahi pilihan
                        perawatan yang dirancang untuk memberikan pengalaman
                        nyaman, privat, dan menenangkan bagi muslimah.
                    @endif
                </p>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fa-solid fa-magnifying-glass"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Cari • Pilih • Booking</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        <div class="sr-shell">

            {{-- SIDEBAR KATEGORI --}}
            <aside class="sr-side bkl-reveal">

                <div class="sr-side-head">
                    <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                    <span>Kategori Layanan</span>
                </div>

                <div class="sr-cats">

                    <a href="{{ route('home', ['semua' => 1]) }}"
                       class="sr-cat {{ empty($kategoriId) && (request('semua') == '1' || empty($search)) ? 'is-active' : '' }}">
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        <span>Semua Layanan</span>
                    </a>

                    @foreach($categories as $category)
                        <a href="{{ route('home', ['kategori' => $category->id]) }}"
                           class="sr-cat {{ (int)($kategoriId ?? 0) === (int)$category->id ? 'is-active' : '' }}">
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            <span>{{ $category->nama }}</span>
                        </a>
                    @endforeach

                </div>

                <div class="sr-side-note">
                    <i class="fa-solid fa-heart" aria-hidden="true"></i>
                    <span>
                        Setiap layanan dirancang untuk memberikan
                        kenyamanan dan perawatan terbaik bagi Muslimah.
                    </span>
                </div>

            </aside>

            {{-- DAFTAR LAYANAN --}}
            <div class="sr-results">

                <div class="sr-head bkl-reveal">
                    <div>
                        <span class="bkl-kicker">Layanan</span>
                        <h2 class="sr-title">{{ $judulBagian }}</h2>
                        <p class="sr-sub">Ditemukan <b id="srCount">{{ $services->count() }}</b> layanan</p>
                    </div>

                    <span class="sr-pill">
                        <i class="fa-solid fa-spa" aria-hidden="true"></i>
                        <span id="srPill">{{ $services->count() }}</span> Layanan
                    </span>
                </div>

                @if($services->isNotEmpty())

                    {{-- ALAT: cari cepat + urutkan (tanpa memuat ulang halaman) --}}
                    <div class="sr-tools bkl-reveal">
                        <label class="sr-quick">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            <input type="search" id="srQuick" placeholder="Saring layanan di halaman ini..." autocomplete="off" aria-label="Saring layanan">
                        </label>

                        <label class="sr-sort">
                            <span>Urutkan</span>
                            <select id="srSort" aria-label="Urutkan layanan">
                                <option value="default">Bawaan</option>
                                <option value="harga-asc">Harga terendah</option>
                                <option value="harga-desc">Harga tertinggi</option>
                                <option value="durasi-asc">Durasi tersingkat</option>
                                <option value="nama-asc">Nama A–Z</option>
                            </select>
                        </label>
                    </div>

                    {{-- SLIDER JIKA LEBIH DARI 2 --}}
                    @if($services->count() > 2)

                        <div class="sr-slider-wrap">

                            <div class="sr-slider-head">
                                <span><i class="fa-solid fa-hand-pointer" aria-hidden="true"></i> Geser untuk menjelajahi layanan</span>

                                <div class="sr-slider-ctl">
                                    <button type="button" class="sr-arrow" id="servicePrev" aria-label="Layanan sebelumnya">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="sr-arrow" id="serviceNext" aria-label="Layanan berikutnya">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="sr-slider" id="serviceSlider">
                                @foreach($services as $service)
                                    @include('pages.partials.service-card', ['service' => $service])
                                @endforeach
                            </div>

                            <div class="sr-progress" aria-hidden="true"><span id="srProgress"></span></div>
                        </div>

                    @else

                        {{-- GRID JIKA 2 ATAU KURANG --}}
                        <div class="sr-grid" id="serviceGrid">
                            @foreach($services as $service)
                                @include('pages.partials.service-card', ['service' => $service])
                            @endforeach
                        </div>

                    @endif

                    {{-- muncul bila saringan cepat tidak menemukan apa pun --}}
                    <div class="sr-empty" id="srNone" hidden>
                        <div class="sr-empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                        <h3>Tidak ada yang cocok</h3>
                        <p>Coba kata kunci lain atau hapus saringan.</p>
                    </div>

                @else

                    {{-- EMPTY STATE --}}
                    <div class="sr-empty bkl-reveal">
                        <div class="sr-empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>

                        <h3>Layanan Tidak Ditemukan</h3>

                        <p>
                            @if(!empty($selectedCategory))
                                Belum ada layanan pada kategori
                                <strong>{{ $selectedCategory->nama }}</strong>.
                            @elseif(!empty($search))
                                Maaf, belum ada layanan yang cocok dengan
                                pencarian <strong>"{{ $search }}"</strong>.
                            @else
                                Belum ada layanan yang tersedia saat ini.
                            @endif
                        </p>

                        <a href="{{ route('home', ['semua' => 1]) }}" class="bkl-btn bkl-btn-maroon">
                            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                            Lihat Semua Layanan
                        </a>
                    </div>

                @endif

            </div>
        </div>
    </div>
</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var slider   = document.getElementById('serviceSlider');
    var container = slider || document.getElementById('serviceGrid');
    var prevBtn  = document.getElementById('servicePrev');
    var nextBtn  = document.getElementById('serviceNext');
    var bar      = document.getElementById('srProgress');

    /* ---------- SLIDER ---------- */
    function updateButtons() {
        if (!slider) return;
        var maxScroll = slider.scrollWidth - slider.clientWidth;

        if (prevBtn) prevBtn.disabled = slider.scrollLeft <= 2;
        if (nextBtn) nextBtn.disabled = slider.scrollLeft >= maxScroll - 2;
        if (bar) {
            var visible = maxScroll > 0 ? slider.clientWidth / slider.scrollWidth : 1;
            var pos     = maxScroll > 0 ? slider.scrollLeft / maxScroll : 0;
            bar.style.width = (visible * 100) + '%';
            bar.style.transform = 'translateX(' + (pos * (1 / visible - 1) * 100) + '%)';
        }
    }

    function getScrollAmount() {
        var card = slider.querySelector('.sr-card:not([hidden])');
        if (!card) return slider.clientWidth;
        var gap = parseFloat(getComputedStyle(slider).gap) || 0;
        return (card.getBoundingClientRect().width + gap) * 2;
    }

    if (slider) {
        if (prevBtn) prevBtn.addEventListener('click', function () { slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' }); });
        if (nextBtn) nextBtn.addEventListener('click', function () { slider.scrollBy({ left: getScrollAmount(),  behavior: 'smooth' }); });
        slider.addEventListener('scroll', updateButtons, { passive: true });
        window.addEventListener('resize', updateButtons);
        updateButtons();
    }

    /* ---------- SARING CEPAT + URUTKAN ---------- */
    var quick = document.getElementById('srQuick');
    var sort  = document.getElementById('srSort');
    var none  = document.getElementById('srNone');
    var count = document.getElementById('srCount');
    var pill  = document.getElementById('srPill');

    if (container && (quick || sort)) {
        var cards = Array.prototype.slice.call(container.querySelectorAll('.sr-card'));
        cards.forEach(function (c, i) { c.dataset.urutan = i; });

        var apply = function () {
            var q = quick ? quick.value.trim().toLowerCase() : '';
            var shown = 0;

            cards.forEach(function (c) {
                var cocok = !q || (c.dataset.cari || '').indexOf(q) !== -1;
                c.hidden = !cocok;
                if (cocok) shown++;
            });

            var mode = sort ? sort.value : 'default';
            var num  = function (c, k) { return parseFloat(c.dataset[k]) || 0; };
            var sorted = cards.slice().sort(function (a, b) {
                if (mode === 'harga-asc')   return num(a, 'harga') - num(b, 'harga');
                if (mode === 'harga-desc')  return num(b, 'harga') - num(a, 'harga');
                if (mode === 'durasi-asc')  return num(a, 'durasi') - num(b, 'durasi');
                if (mode === 'nama-asc')    return (a.dataset.nama || '').localeCompare(b.dataset.nama || '', 'id');
                return num(a, 'urutan') - num(b, 'urutan');
            });
            sorted.forEach(function (c) { container.appendChild(c); });

            if (count) count.textContent = shown;
            if (pill)  pill.textContent  = shown;
            if (none)  none.hidden = shown !== 0;
            if (slider) { slider.scrollTo({ left: 0 }); updateButtons(); }
        };

        if (quick) quick.addEventListener('input', apply);
        if (sort)  sort.addEventListener('change', apply);
    }
});
</script>
@endpush