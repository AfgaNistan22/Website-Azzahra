{{-- Navbar Azzahra Salon & Spa Muslimah --}}
@php
    use Illuminate\Support\Facades\Route;

    // Cadangan URL bila nama route belum ada
    $urlPerawatan     = Route::has('perawatan-layanan.index') ? route('perawatan-layanan.index') : url('/perawatan-layanan');
    $urlPromo         = Route::has('promo.publik')            ? route('promo.publik')            : url('/promo-terbaru');
    $urlTestimoni     = Route::has('testimoni.index')         ? route('testimoni.index')         : url('/testimoni');
    $urlSejarah       = Route::has('sejarah')        ? route('sejarah')        : url('/sejarah');
    $urlKontak        = Route::has('kontak')         ? route('kontak')         : url('/kontak-kami');
    $urlLokasi        = Route::has('lokasi.index')   ? route('lokasi.index')   : url('/lokasi');
    $urlGaleri        = Route::has('galeri.index')   ? route('galeri.index')   : url('/galeri');

    $urlBuatBooking   = Route::has('booking.create') ? route('booking.create') : url('/booking/buat');
    $urlLacakBooking  = Route::has('booking.lacak')  ? route('booking.lacak')  : url('/booking/lacak');
    $urlBookingSaya   = Route::has('booking.saya')   ? route('booking.saya')   : url('/booking-saya');
    $urlDaftarBooking = Route::has('booking.daftar') ? route('booking.daftar') : url('/daftar-booking');

    // Penanda menu aktif
    $layananAktif   = request()->routeIs('perawatan-layanan.*', 'layanan.*', 'promo.publik', 'testimoni.*');
    $tentangAktif   = request()->routeIs('sejarah', 'kontak', 'kontak.*', 'lokasi.*', 'galeri.*');
    $bookingAktif   = request()->routeIs('booking.*');
@endphp

{{--
    CSS dropdown sengaja ditaruh langsung di sini (bukan lewat @push) supaya pasti terpakai,
    apa pun posisi @stack('styles') di layout. Dipasang sebelum <nav> agar tidak berkedip.
--}}
<style>
    /* ===== Dropdown (dipakai Layanan, Tentang kami, Booking) ===== */
    .navigation ul li.nav-dropdown { position: relative; list-style: none; }

    /* Tombol pemicu: tampil seperti menu lain, ditambah panah (tanpa garis bawah) */
    .navigation ul li.nav-dropdown > a.nav-dropdown-toggle {
        position: relative;
        display: inline-flex !important;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border-bottom: 0 !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }

    .navigation .nav-dropdown-caret { font-size: 10px; opacity: .85; transition: transform .3s ease; }
    .navigation .nav-dropdown.is-open .nav-dropdown-caret { transform: rotate(180deg); }

    /* Panel */
    .navigation .nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 14px);
        left: -14px;
        z-index: 1000;
        display: block;
        width: 300px;
        padding: 8px;
        border: 1px solid #EADFE1;
        border-radius: 12px;
        background: #FFFFFF;
        box-shadow: 0 18px 40px -12px rgba(42, 7, 17, .35), 0 2px 6px rgba(42, 7, 17, .08);
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: opacity .22s ease, transform .22s ease, visibility .22s;
        text-align: left;
    }
    /* jembatan hover agar panel tidak menutup saat kursor melintas */
    .navigation .nav-dropdown-menu::before { content: ""; position: absolute; left: 0; right: 0; top: -16px; height: 16px; }
    /* segitiga kecil penunjuk */
    .navigation .nav-dropdown-menu::after {
        content: ""; position: absolute; top: -6px; left: 30px; width: 12px; height: 12px;
        background: #fff; border-top: 1px solid #EADFE1; border-left: 1px solid #EADFE1;
        transform: rotate(45deg);
    }

    .navigation .nav-dropdown.is-open .nav-dropdown-menu,
    .navigation .nav-dropdown:focus-within .nav-dropdown-menu { opacity: 1; visibility: visible; transform: none; }
    @media (hover: hover) and (pointer: fine) {
        .navigation .nav-dropdown:hover .nav-dropdown-menu { opacity: 1; visibility: visible; transform: none; }
        .navigation .nav-dropdown:hover .nav-dropdown-caret { transform: rotate(180deg); }
    }

    /* Item. !important diperlukan karena aturan global .navigation ul li a memaksa teks putih */
    .navigation .nav-dropdown-menu a.nav-dropdown-item {
        position: relative;
        display: flex !important;
        align-items: center;
        gap: 14px;
        margin: 0;
        padding: 11px 12px !important;
        border-radius: 8px;
        background: transparent !important;
        color: #2A0711 !important;
        text-decoration: none !important;
        transition: background .2s ease;
    }
    .navigation .nav-dropdown-menu a.nav-dropdown-item:hover,
    .navigation .nav-dropdown-menu a.nav-dropdown-item:focus-visible { background: #FAF4F4 !important; color: #2A0711 !important; outline: none; }
    .navigation .nav-dropdown-menu a.nav-dropdown-item.active { background: #F6EBEC !important; color: #2A0711 !important; }
    .navigation .nav-dropdown-menu a.nav-dropdown-item.active::before {
        content: ""; position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px; border-radius: 3px; background: #64172D;
    }

    .navigation .nav-dropdown-icon {
        display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
        width: 38px; height: 38px; border-radius: 9px;
        background: #F6EBEC; color: #64172D !important; font-size: 15px;
        transition: background .2s ease, color .2s ease;
    }
    .navigation .nav-dropdown-icon i { color: inherit !important; }
    .navigation a.nav-dropdown-item:hover .nav-dropdown-icon,
    .navigation a.nav-dropdown-item.active .nav-dropdown-icon { background: #64172D; color: #FFFFFF !important; }

    .navigation .nav-dropdown-text { display: flex; flex-direction: column; min-width: 0; line-height: 1.35; }
    .navigation .nav-dropdown-text strong { font-size: 14px; font-weight: 600; color: #2A0711 !important; }
    .navigation .nav-dropdown-text small { margin-top: 1px; font-size: 12px; font-weight: 400; color: #7A6A6F !important; }

    .navigation .nav-dropdown-sep { height: 1px; margin: 6px 12px; background: #EFE5E6; }

    /* ===== Layar kecil: panel mengembang di dalam alur menu ===== */
    @media (max-width: 768px) {
        .navigation ul li.nav-dropdown { width: 100%; }
        .navigation ul li.nav-dropdown > a.nav-dropdown-toggle { width: 100%; justify-content: space-between; }

        .navigation .nav-dropdown-menu,
        .navigation .nav-dropdown:focus-within .nav-dropdown-menu {
            position: static;
            width: 100%;
            max-height: 0;
            margin-top: 0;
            padding: 0 8px;
            overflow: hidden;
            border-width: 0;
            border-radius: 10px;
            opacity: 1;
            visibility: visible;
            transform: none;
            box-shadow: none;
            transition: max-height .4s ease, padding .25s ease, margin .25s ease;
        }
        .navigation .nav-dropdown.is-open .nav-dropdown-menu {
            max-height: 520px; margin-top: 10px; padding: 8px; border-width: 1px;
        }
        .navigation .nav-dropdown-menu::before,
        .navigation .nav-dropdown-menu::after { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .navigation .nav-dropdown-menu,
        .navigation .nav-dropdown-caret { transition: none; }
    }
</style>

<nav class="navigation">
    <div class="container">
        <ul>

            {{-- Beranda --}}
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
            </li>

            {{-- Layanan (dropdown) --}}
            <li class="nav-dropdown {{ $layananAktif ? 'is-current' : '' }}">

                <a href="#"
                   class="nav-dropdown-toggle {{ $layananAktif ? 'active' : '' }}"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false"
                   aria-controls="menuLayanan">
                    Layanan
                    <i class="fas fa-chevron-down nav-dropdown-caret" aria-hidden="true"></i>
                </a>

                <div class="nav-dropdown-menu" id="menuLayanan" role="menu">

                    <a href="{{ $urlPerawatan }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('perawatan-layanan.*', 'layanan.*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-spa"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Perawatan</strong>
                            <small>Daftar perawatan dan layanan kami</small>
                        </span>
                    </a>

                    <a href="{{ $urlPromo }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('promo.publik') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-tags"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Promo</strong>
                            <small>Penawaran dan diskon terbaru</small>
                        </span>
                    </a>

                    <a href="{{ $urlTestimoni }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('testimoni.*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-comment-dots"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Testimoni</strong>
                            <small>Cerita dan ulasan pelanggan kami</small>
                        </span>
                    </a>

                </div>

            </li>

            {{-- Jadwal Terapis --}}
            <li>
                <a href="{{ route('jadwal-terapis.index') }}"
                   class="{{ request()->routeIs('jadwal-terapis.*') ? 'active' : '' }}">
                    Jadwal Terapis
                </a>
            </li>

            {{-- Tentang kami (dropdown) --}}
            <li class="nav-dropdown {{ $tentangAktif ? 'is-current' : '' }}">

                <a href="#"
                   class="nav-dropdown-toggle {{ $tentangAktif ? 'active' : '' }}"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false"
                   aria-controls="menuTentang">
                    Tentang kami
                    <i class="fas fa-chevron-down nav-dropdown-caret" aria-hidden="true"></i>
                </a>

                <div class="nav-dropdown-menu" id="menuTentang" role="menu">

                    <a href="{{ $urlSejarah }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('sejarah') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-landmark"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Sejarah</strong>
                            <small>Perjalanan Azzahra Salon & Spa</small>
                        </span>
                    </a>

                    <a href="{{ $urlKontak }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('kontak', 'kontak.*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-phone-alt"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Kontak kami</strong>
                            <small>Hubungi kami lewat telepon atau WhatsApp</small>
                        </span>
                    </a>

                    <a href="{{ $urlLokasi }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Lokasi</strong>
                            <small>Alamat dan petunjuk arah</small>
                        </span>
                    </a>

                    <a href="{{ $urlGaleri }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('galeri.*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-images"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Galeri</strong>
                            <small>Foto suasana dan hasil perawatan</small>
                        </span>
                    </a>

                </div>

            </li>

            {{-- Booking (dropdown) --}}
            <li class="nav-dropdown {{ $bookingAktif ? 'is-current' : '' }}">

                <a href="#"
                   class="nav-dropdown-toggle {{ $bookingAktif ? 'active' : '' }}"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false"
                   aria-controls="menuBooking">
                    Booking
                    <i class="fas fa-chevron-down nav-dropdown-caret" aria-hidden="true"></i>
                </a>

                <div class="nav-dropdown-menu" id="menuBooking" role="menu">

                    {{-- Terbuka untuk tamu maupun pelanggan login --}}
                    <a href="{{ $urlBuatBooking }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('booking.create') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-calendar-plus"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Buat Booking</strong>
                            <small>Pesan perawatan baru</small>
                        </span>
                    </a>

                    <div class="nav-dropdown-sep" role="separator"></div>

                    @auth
                        {{-- Hanya untuk yang sudah login (route-nya memakai middleware auth) --}}
                        <a href="{{ $urlBookingSaya }}" role="menuitem"
                           class="nav-dropdown-item {{ request()->routeIs('booking.saya*', 'booking.detail', 'booking.pembayaran*') ? 'active' : '' }}">
                            <span class="nav-dropdown-icon"><i class="fas fa-calendar-check"></i></span>
                            <span class="nav-dropdown-text">
                                <strong>Bookingan Saya</strong>
                                <small>Booking terdekat Anda</small>
                            </span>
                        </a>

                        <a href="{{ $urlDaftarBooking }}" role="menuitem"
                           class="nav-dropdown-item {{ request()->routeIs('booking.daftar*') ? 'active' : '' }}">
                            <span class="nav-dropdown-icon"><i class="fas fa-list-ul"></i></span>
                            <span class="nav-dropdown-text">
                                <strong>Daftar Booking</strong>
                                <small>Seluruh riwayat booking</small>
                            </span>
                        </a>
                    @endauth

                    <a href="{{ $urlLacakBooking }}" role="menuitem"
                       class="nav-dropdown-item {{ request()->routeIs('booking.lacak*') ? 'active' : '' }}">
                        <span class="nav-dropdown-icon"><i class="fas fa-search"></i></span>
                        <span class="nav-dropdown-text">
                            <strong>Lacak Booking</strong>
                            <small>Pakai kode booking dan WhatsApp</small>
                        </span>
                    </a>

                </div>

            </li>

        </ul>
    </div>
</nav>

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/navbar2.css') }}">
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ---------- Dropdown (Layanan, Tentang kami, Booking) ---------- */
        var dropdowns = document.querySelectorAll('.navigation .nav-dropdown');

        function tutupSemua(kecuali) {
            dropdowns.forEach(function (dd) {
                if (dd === kecuali) return;
                dd.classList.remove('is-open');
                var t = dd.querySelector('.nav-dropdown-toggle');
                if (t) t.setAttribute('aria-expanded', 'false');
            });
        }

        dropdowns.forEach(function (dd) {
            var toggle = dd.querySelector('.nav-dropdown-toggle');
            if (!toggle) return;

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                tutupSemua(dd);
                var buka = dd.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', buka ? 'true' : 'false');
            });
        });

        // Klik di luar menu menutupnya
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.navigation .nav-dropdown')) tutupSemua(null);
        });

        // Escape menutup dan mengembalikan fokus ke tombol
        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') return;
            dropdowns.forEach(function (dd) {
                if (dd.classList.contains('is-open')) {
                    var t = dd.querySelector('.nav-dropdown-toggle');
                    dd.classList.remove('is-open');
                    if (t) { t.setAttribute('aria-expanded', 'false'); t.focus(); }
                }
            });
        });


        /* ---------- Kategori (dipertahankan dari versi lama) ---------- */
        document.querySelectorAll('.navigation .navbar-category').forEach(function (item) {
            var toggle = item.querySelector('.navbar-category-toggle');
            if (!toggle) return;

            toggle.addEventListener('click', function (event) {
                if (window.innerWidth > 768) return;

                event.preventDefault();
                item.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');
            });
        });
    });
</script>