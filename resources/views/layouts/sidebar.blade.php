{{-- =========================================================
    SIDEBAR AZZAHRA SALON & SPA MUSLIMAH
    PREMIUM COMPACT — ELITE EDITION
========================================================= --}}

@php
    /* =========================================================
       ACTIVE MENU
    ========================================================= */

    $penggunaAktif =
        request()->is('pelanggan*') ||
        request()->is('pegawai*') ||
        request()->is('users*');

    $perusahaanAktif =
        request()->is('cabang*') ||
        request()->is('perawatan*') ||
        request()->is('promo*');

    $reservasiAktif =
        request()->is('reservasi*') ||
        request()->is('riwayat-reservasi*');

    $keuanganAktif =
        request()->is('gaji*') ||
        request()->is('pajak*') ||
        request()->is('pembayaran*') ||
        request()->is('pemasukan*') ||
        request()->is('pengeluaran*');

    $laporanAktif =
        request()->is('laporan/harian*') ||
        request()->is('laporan/bulanan*') ||
        request()->is('laporan/master*');
@endphp


<ul class="navbar-nav sidebar sidebar-dark accordion"
    id="accordionSidebar">


    {{-- =====================================================
        LOGO
    ====================================================== --}}

    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="{{ url('/dashboard') }}">

        <div class="sidebar-brand-logo">

            <div class="logo-circle-decoration"></div>

            <img id="sidebarLogo"
                 src="{{ asset('template/img/logo.png') }}"
                 alt="Azzahra Salon & Spa Muslimah">

        </div>

    </a>


    <hr class="sidebar-divider my-0">


    {{-- =====================================================
        DASHBOARD
    ====================================================== --}}

    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">

        <a class="nav-link premium-menu"
           href="{{ url('/dashboard') }}">

            <span class="menu-icon">
                <i class="fas fa-tachometer-alt"></i>
            </span>

            <span class="menu-label">
                Dashboard
            </span>

        </a>

    </li>


    <hr class="sidebar-divider">


    {{-- =====================================================
        PENGGUNA
    ====================================================== --}}

    <div class="sidebar-heading">
        MANAGEMENT
    </div>


    <li class="nav-item {{ $penggunaAktif ? 'menu-parent-active' : '' }}">

        <a class="nav-link premium-menu {{ !$penggunaAktif ? 'collapsed' : '' }}"
           href="#collapsePengguna"
           data-toggle="collapse"
           role="button"
           aria-expanded="{{ $penggunaAktif ? 'true' : 'false' }}"
           aria-controls="collapsePengguna">

            <span class="menu-icon">
                <i class="fas fa-users"></i>
            </span>

            <span class="menu-label">
                Pengguna
            </span>

            <span class="menu-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

        </a>


        <div id="collapsePengguna"
             class="collapse {{ $penggunaAktif ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="submenu-container">


                {{-- PELANGGAN --}}

                <a href="{{ url('/pelanggan') }}"
                   class="submenu-link {{ request()->is('pelanggan*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-user"></i>
                    </span>

                    <span class="submenu-label">
                        Pelanggan
                    </span>

                </a>


                {{-- PEGAWAI --}}

                <a href="{{ url('/pegawai') }}"
                   class="submenu-link {{ request()->is('pegawai*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-user-tie"></i>
                    </span>

                    <span class="submenu-label">
                        Pegawai
                    </span>

                </a>


                {{-- AKUN USER / NASABAH --}}

                <a href="{{ route('users.index') }}"
                   class="submenu-link {{ request()->is('users*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-users-cog"></i>
                    </span>

                    <span class="submenu-label">
                        Akun Pengguna
                    </span>

                </a>


            </div>

        </div>

    </li>


    {{-- =====================================================
        PERUSAHAAN
    ====================================================== --}}

    <li class="nav-item {{ $perusahaanAktif ? 'menu-parent-active' : '' }}">

        <a class="nav-link premium-menu {{ !$perusahaanAktif ? 'collapsed' : '' }}"
           href="#collapsePerusahaan"
           data-toggle="collapse"
           role="button"
           aria-expanded="{{ $perusahaanAktif ? 'true' : 'false' }}"
           aria-controls="collapsePerusahaan">

            <span class="menu-icon">
                <i class="fas fa-building"></i>
            </span>

            <span class="menu-label">
                Perusahaan
            </span>

            <span class="menu-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

        </a>


        <div id="collapsePerusahaan"
             class="collapse {{ $perusahaanAktif ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="submenu-container">


                {{-- CABANG --}}

                <a href="{{ url('/cabang') }}"
                   class="submenu-link {{ request()->is('cabang*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-building"></i>
                    </span>

                    <span class="submenu-label">
                        Cabang
                    </span>

                </a>

                {{-- KATEGORI --}}

                <a href="{{ url('/kategori') }}"
                   class="submenu-link {{ request()->is('kategori*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-spa"></i>
                    </span>

                    <span class="submenu-label">
                        Kategori
                    </span>

                </a>


                {{-- PERAWATAN --}}

                <a href="{{ url('/perawatan') }}"
                   class="submenu-link {{ request()->is('perawatan*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-spa"></i>
                    </span>

                    <span class="submenu-label">
                        Perawatan
                    </span>

                </a>


                {{-- PROMO --}}

                <a href="{{ url('/promo') }}"
                   class="submenu-link {{ request()->is('promo*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-tags"></i>
                    </span>

                    <span class="submenu-label">
                        Promo
                    </span>

                </a>


            </div>

        </div>

    </li>


    {{-- =====================================================
        RESERVASI
    ====================================================== --}}

    <li class="nav-item {{ $reservasiAktif ? 'menu-parent-active' : '' }}">

        <a class="nav-link premium-menu {{ !$reservasiAktif ? 'collapsed' : '' }}"
           href="#collapseReservasi"
           data-toggle="collapse"
           role="button"
           aria-expanded="{{ $reservasiAktif ? 'true' : 'false' }}"
           aria-controls="collapseReservasi">

            <span class="menu-icon">
                <i class="fas fa-calendar-check"></i>
            </span>

            <span class="menu-label">
                Reservasi
            </span>

            <span class="menu-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

        </a>


        <div id="collapseReservasi"
             class="collapse {{ $reservasiAktif ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="submenu-container">


                {{-- DETAIL RESERVASI --}}

                <a href="{{ url('/reservasi') }}"
                   class="submenu-link {{ request()->is('reservasi*') && !request()->is('riwayat-reservasi*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </span>

                    <span class="submenu-label">
                        Detail Reservasi
                    </span>

                </a>


                {{-- RIWAYAT RESERVASI --}}

                <a href="{{ url('/riwayat-reservasi') }}"
                   class="submenu-link {{ request()->is('riwayat-reservasi*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-history"></i>
                    </span>

                    <span class="submenu-label">
                        Riwayat Reservasi
                    </span>

                </a>


            </div>

        </div>

    </li>


    {{-- =====================================================
        KEUANGAN
    ====================================================== --}}

    <div class="sidebar-heading">
        FINANCE
    </div>


    <li class="nav-item {{ $keuanganAktif ? 'menu-parent-active' : '' }}">

        <a class="nav-link premium-menu {{ !$keuanganAktif ? 'collapsed' : '' }}"
           href="#collapseKeuangan"
           data-toggle="collapse"
           role="button"
           aria-expanded="{{ $keuanganAktif ? 'true' : 'false' }}"
           aria-controls="collapseKeuangan">

            <span class="menu-icon">
                <i class="fas fa-wallet"></i>
            </span>

            <span class="menu-label">
                Keuangan
            </span>

            <span class="menu-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

        </a>


        <div id="collapseKeuangan"
             class="collapse {{ $keuanganAktif ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="submenu-container">


                {{-- GAJI --}}

                <a href="{{ url('/gaji') }}"
                   class="submenu-link {{ request()->is('gaji*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </span>

                    <span class="submenu-label">
                        Gaji
                    </span>

                </a>


                {{-- PAJAK --}}

                <a href="{{ url('/pajak') }}"
                   class="submenu-link {{ request()->is('pajak*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </span>

                    <span class="submenu-label">
                        Pajak
                    </span>

                </a>


                {{-- PEMBAYARAN --}}

                <a href="{{ url('/pembayaran') }}"
                   class="submenu-link {{ request()->is('pembayaran*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-credit-card"></i>
                    </span>

                    <span class="submenu-label">
                        Pembayaran
                    </span>

                </a>


                {{-- PEMASUKAN --}}

                <a href="{{ url('/pemasukan') }}"
                   class="submenu-link {{ request()->is('pemasukan*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-arrow-circle-down"></i>
                    </span>

                    <span class="submenu-label">
                        Pemasukan
                    </span>

                </a>


                {{-- PENGELUARAN --}}

                <a href="{{ url('/pengeluaran') }}"
                   class="submenu-link {{ request()->is('pengeluaran*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-arrow-circle-up"></i>
                    </span>

                    <span class="submenu-label">
                        Pengeluaran
                    </span>

                </a>


            </div>

        </div>

    </li>


    {{-- =====================================================
        LAPORAN
    ====================================================== --}}

    <div class="sidebar-heading">
        REPORT
    </div>


    <li class="nav-item {{ $laporanAktif ? 'menu-parent-active' : '' }}">

        <a class="nav-link premium-menu {{ !$laporanAktif ? 'collapsed' : '' }}"
           href="#collapseLaporan"
           data-toggle="collapse"
           role="button"
           aria-expanded="{{ $laporanAktif ? 'true' : 'false' }}"
           aria-controls="collapseLaporan">

            <span class="menu-icon">
                <i class="fas fa-chart-line"></i>
            </span>

            <span class="menu-label">
                Laporan
            </span>

            <span class="menu-arrow">
                <i class="fas fa-chevron-right"></i>
            </span>

        </a>


        <div id="collapseLaporan"
             class="collapse {{ $laporanAktif ? 'show' : '' }}"
             data-parent="#accordionSidebar">

            <div class="submenu-container">


                {{-- HARIAN --}}

                <a href="{{ url('/laporan/harian') }}"
                   class="submenu-link {{ request()->is('laporan/harian*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-calendar-day"></i>
                    </span>

                    <span class="submenu-label">
                        Laporan Harian
                    </span>

                </a>


                {{-- BULANAN --}}

                <a href="{{ url('/laporan/bulanan') }}"
                   class="submenu-link {{ request()->is('laporan/bulanan*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </span>

                    <span class="submenu-label">
                        Laporan Bulanan
                    </span>

                </a>


                {{-- MASTER --}}

                <a href="{{ url('/laporan/master') }}"
                   class="submenu-link {{ request()->is('laporan/master*') ? 'submenu-active' : '' }}">

                    <span class="submenu-icon">
                        <i class="fas fa-file-alt"></i>
                    </span>

                    <span class="submenu-label">
                        Laporan Master
                    </span>

                </a>


            </div>

        </div>

    </li>


    {{-- =====================================================
        DIVIDER BAWAH
    ====================================================== --}}

    <hr class="sidebar-divider d-none d-md-block">


    {{-- =====================================================
        TOGGLE
    ====================================================== --}}

    <div class="sidebar-toggle-wrapper d-none d-md-flex">

        <button type="button"
                id="sidebarTogglePremium"
                class="sidebar-toggle-premium"
                title="Perkecil Sidebar">

            <i class="fas fa-chevron-left"></i>

        </button>

    </div>


</ul>


{{-- =========================================================
    PREMIUM COMPACT CSS — ELITE EDITION
========================================================= --}}

<style>

/* =========================================================
   DESIGN TOKENS (selaras dengan dashboard)
========================================================= */

#accordionSidebar {

    --sb-red:       #e01c1d;
    --sb-red-dark:  #7c1013;
    --sb-gold:      #d9ac4f;
    --sb-gold-soft: rgba(217, 172, 79, .16);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;

}


/* =========================================================
   SIDEBAR
========================================================= */

#accordionSidebar {

    width: 240px !important;
    min-width: 240px !important;

    min-height: 100vh;

    position: relative;

    overflow-x: hidden;
    overflow-y: auto;

    background:
        radial-gradient(circle at 20% 0%, rgba(217, 172, 79, .10) 0%, transparent 45%),
        linear-gradient(
            170deg,
            rgba(58, 8, 14, .97) 0%,
            rgba(122, 18, 24, .95) 55%,
            rgba(92, 12, 22, .97) 100%
        ),
        url('{{ asset('template/img/bck.png') }}')
        center center / cover no-repeat !important;

    box-shadow:
        6px 0 24px rgba(0,0,0,.20);

    border-right: 1px solid rgba(255,255,255,.06);

    transition:
        width .3s ease,
        min-width .3s ease;

}

#accordionSidebar::-webkit-scrollbar {

    width: 5px;

}

#accordionSidebar::-webkit-scrollbar-thumb {

    background: rgba(217, 172, 79, .35);

    border-radius: 10px;

}

#accordionSidebar::-webkit-scrollbar-track {

    background: transparent;

}


/* =========================================================
   MATIKAN ARROW DEFAULT SB ADMIN
========================================================= */

#accordionSidebar
.nav-link[data-toggle="collapse"]::after {

    display: none !important;

    content: none !important;

}


#accordionSidebar
.nav-link[data-toggle="collapse"]::before {

    display: none !important;

    content: none !important;

}


#accordionSidebar
.nav-item::before,

#accordionSidebar
.nav-item::after {

    display: none !important;

    content: none !important;

}


/* =========================================================
   LOGO
========================================================= */

#accordionSidebar .sidebar-brand {

    position: relative;

    width: 240px;

    height: 100px !important;

    min-height: 100px !important;

    padding: 12px 15px !important;

    overflow: hidden;

    border-bottom: 1px solid rgba(255,255,255,.06);

}


/* =========================================================
   ORNAMEN LOGO
========================================================= */

#accordionSidebar
.sidebar-brand::before {

    content: "";

    position: absolute;

    width: 285px;

    height: 285px;

    top: -205px;

    right: -115px;

    border-radius: 50%;

    background:
        radial-gradient(circle, rgba(217, 172, 79, .16) 0%, transparent 70%);

    pointer-events: none;

}


#accordionSidebar
.sidebar-brand::after {

    content: "";

    position: absolute;

    width: 190px;

    height: 190px;

    top: -135px;

    right: -80px;

    border-radius: 50%;

    border:
        1px solid rgba(217, 172, 79, .14);

    pointer-events: none;

}


/* =========================================================
   LOGO WRAPPER
========================================================= */

.sidebar-brand-logo {

    width: 100%;

    height: 100%;

    display: flex;

    align-items: center;

    justify-content: center;

    position: relative;

    z-index: 5;

}


/* =========================================================
   LINGKARAN LOGO
========================================================= */

.logo-circle-decoration {

    position: absolute;

    width: 150px;

    height: 150px;

    top: 50%;

    left: 50%;

    transform:
        translate(-50%, -50%);

    border-radius: 50%;

    background:
        rgba(255,255,255,.035);

    border:
        1px solid rgba(217, 172, 79, .22);

    box-shadow:
        0 0 30px rgba(217, 172, 79, .08);

    z-index: 1;

    pointer-events: none;

}


/* =========================================================
   LOGO
========================================================= */

#sidebarLogo {

    width: 180px;

    max-width: 100%;

    height: auto;

    display: block;

    position: relative;

    z-index: 3;

    pointer-events: none;

    filter: drop-shadow(0 4px 10px rgba(0,0,0,.30));

}


/* =========================================================
   DIVIDER
========================================================= */

#accordionSidebar .sidebar-divider {

    margin:
        6px 20px !important;

    border-top:
        1px solid rgba(255,255,255,.10) !important;

}


/* =========================================================
   HEADING
========================================================= */

#accordionSidebar .sidebar-heading {

    position: relative;

    padding:
        16px 25px 6px !important;

    margin:
        0 !important;

    color:
        var(--sb-gold) !important;

    font-size:
        9.5px !important;

    font-weight:
        700 !important;

    letter-spacing:
        2px;

    line-height:
        15px;

    text-transform:
        uppercase;

    opacity: .9;

}


/* =========================================================
   MENU UTAMA
========================================================= */

#accordionSidebar .premium-menu {

    position: relative;

    display: grid !important;

    grid-template-columns:
        35px minmax(0,1fr) 18px;

    align-items: center !important;

    width:
        calc(100% - 16px);

    height:
        50px !important;

    min-height:
        50px !important;

    margin:
        3px 8px !important;

    padding:
        0 12px !important;

    border-radius:
        12px;

    color:
        rgba(255,255,255,.87) !important;

    text-decoration:
        none !important;

    line-height:
        normal !important;

    transition:
        background .25s ease,
        color .2s ease,
        box-shadow .25s ease,
        transform .2s ease;

}


/* =========================================================
   HOVER MENU UTAMA
========================================================= */

#accordionSidebar
.premium-menu:hover {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .16), rgba(255,255,255,.06));

    color:
        #ffffff !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold);

    transform:
        translateX(2px);

}


/* =========================================================
   ICON MENU
========================================================= */

#accordionSidebar .menu-icon {

    width:
        35px;

    height:
        50px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        flex-start;

    margin:
        0;

    padding:
        0;

}


#accordionSidebar
.menu-icon i {

    width:
        18px;

    height:
        18px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    margin:
        0;

    padding:
        0;

    font-size:
        13px;

    line-height:
        18px;

    color:
        rgba(255,255,255,.60);

    text-align:
        center;

    transition:
        color .2s ease,
        transform .2s ease;

}


#accordionSidebar
.premium-menu:hover
.menu-icon i {

    color:
        var(--sb-gold) !important;

    transform:
        scale(1.1);

}


/* =========================================================
   LABEL
========================================================= */

#accordionSidebar .menu-label {

    height:
        50px;

    display:
        flex;

    align-items:
        center;

    margin:
        0;

    padding:
        0;

    font-size:
        12px;

    font-weight:
        500;

    line-height:
        1 !important;

    white-space:
        nowrap;

    color:
        rgba(255,255,255,.90);

    letter-spacing:
        .1px;

}


#accordionSidebar
.premium-menu:hover
.menu-label {

    color:
        #ffffff !important;

}


/* =========================================================
   ARROW
========================================================= */

#accordionSidebar .menu-arrow {

    width:
        18px;

    height:
        50px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    margin:
        0;

    padding:
        0;

    color:
        rgba(255,255,255,.45);

}


#accordionSidebar
.menu-arrow i {

    margin:
        0;

    padding:
        0;

    font-size:
        9px;

    line-height:
        1;

    transition:
        transform .25s ease;

}


#accordionSidebar
.premium-menu:hover
.menu-arrow {

    color:
        var(--sb-gold) !important;

}


/* =========================================================
   ARROW SAAT DROPDOWN TERBUKA
========================================================= */

#accordionSidebar
.premium-menu:not(.collapsed)
.menu-arrow i {

    transform:
        rotate(90deg);

}


/* =========================================================
   DASHBOARD ACTIVE
========================================================= */

#accordionSidebar
.nav-item.active >
.premium-menu {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .22), rgba(255,255,255,.05));

    color:
        #ffffff !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold),
        0 6px 16px rgba(0,0,0,.18);

}


#accordionSidebar
.nav-item.active
.menu-icon i {

    color:
        var(--sb-gold) !important;

}


/* =========================================================
   PARENT DROPDOWN AKTIF
   TIDAK MENYALA SEPERTI HOVER
========================================================= */

#accordionSidebar
.nav-item.menu-parent-active >
.premium-menu {

    background:
        transparent !important;

    color:
        rgba(255,255,255,.87) !important;

    box-shadow:
        none !important;

}


/* =========================================================
   PARENT DROPDOWN KETIKA MOUSE DI ATAS
========================================================= */

#accordionSidebar
.nav-item.menu-parent-active >
.premium-menu:hover {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .16), rgba(255,255,255,.06)) !important;

    color:
        #ffffff !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold) !important;

}


/* =========================================================
   SUBMENU
========================================================= */

#accordionSidebar .submenu-container {

    position:
        relative;

    margin:
        3px 10px 8px 25px;

    padding:
        6px 5px 6px 18px;

    border-left:
        2px solid var(--sb-gold-soft);

    background:
        rgba(0,0,0,.16);

    border-radius:
        0 12px 12px 0;

}


/* =========================================================
   SUBMENU LINK
========================================================= */

#accordionSidebar .submenu-link {

    position:
        relative;

    display:
        grid;

    grid-template-columns:
        26px minmax(0,1fr);

    align-items:
        center;

    width:
        100%;

    min-height:
        35px;

    margin:
        2px 0;

    padding:
        0 8px;

    border-radius:
        9px;

    color:
        rgba(255,255,255,.65) !important;

    text-decoration:
        none !important;

    font-size:
        10.5px;

    font-weight:
        400;

    line-height:
        normal !important;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease;

}


/* =========================================================
   SUBMENU HOVER
========================================================= */

#accordionSidebar
.submenu-link:hover {

    background:
        rgba(217, 172, 79, .12);

    color:
        #ffffff !important;

    transform:
        translateX(3px);

}


/* =========================================================
   SUBMENU ICON
========================================================= */

#accordionSidebar .submenu-icon {

    width:
        26px;

    height:
        35px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        flex-start;

    margin:
        0;

    padding:
        0;

}


#accordionSidebar
.submenu-icon i {

    width:
        14px;

    height:
        14px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    margin:
        0;

    padding:
        0;

    font-size:
        8px;

    line-height:
        14px;

    color:
        rgba(255,255,255,.48);

}


#accordionSidebar
.submenu-link:hover
.submenu-icon i {

    color:
        var(--sb-gold);

}


/* =========================================================
   SUBMENU LABEL
========================================================= */

#accordionSidebar .submenu-label {

    height:
        35px;

    display:
        flex;

    align-items:
        center;

    margin:
        0;

    padding:
        0;

    line-height:
        1 !important;

    white-space:
        nowrap;

}


/* =========================================================
   SUBMENU ACTIVE
========================================================= */

#accordionSidebar
.submenu-link.submenu-active {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .22), rgba(255,255,255,.04)) !important;

    color:
        #ffffff !important;

    font-weight:
        600;

    box-shadow:
        0 4px 12px rgba(0,0,0,.14);

}


/* =========================================================
   TITIK SUBMENU AKTIF
========================================================= */

#accordionSidebar
.submenu-link.submenu-active::before {

    content: "";

    position:
        absolute;

    left:
        -23px;

    top:
        50%;

    width:
        6px;

    height:
        6px;

    border-radius:
        50%;

    background:
        var(--sb-gold);

    transform:
        translateY(-50%);

    box-shadow:
        0 0 8px rgba(217, 172, 79, .85);

}


/* =========================================================
   ICON SUBMENU AKTIF
========================================================= */

#accordionSidebar
.submenu-link.submenu-active
.submenu-icon i {

    color:
        var(--sb-gold) !important;

}


/* =========================================================
   TOGGLE
========================================================= */

#accordionSidebar .sidebar-toggle-wrapper {

    width:
        100%;

    min-height:
        64px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

}


#accordionSidebar
.sidebar-toggle-premium {

    width:
        38px;

    height:
        38px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    padding:
        0;

    border:
        1px solid var(--sb-gold-soft);

    border-radius:
        50%;

    background:
        rgba(255,255,255,.06);

    color:
        var(--sb-gold);

    cursor:
        pointer;

    outline:
        none !important;

    transition:
        background .2s ease,
        transform .2s ease,
        border .2s ease,
        box-shadow .2s ease;

}


#accordionSidebar
.sidebar-toggle-premium:hover {

    background:
        rgba(217, 172, 79, .16);

    border-color:
        var(--sb-gold);

    box-shadow:
        0 0 14px rgba(217, 172, 79, .35);

}


#accordionSidebar
.sidebar-toggle-premium i {

    font-size:
        9px;

    pointer-events:
        none;

}


/* =========================================================
   MINI SIDEBAR
========================================================= */

#accordionSidebar.toggled {

    width:
        75px !important;

    min-width:
        75px !important;

}


/* =========================================================
   MINI LOGO
========================================================= */

#accordionSidebar.toggled
.sidebar-brand {

    width:
        75px;

    height:
        95px !important;

    min-height:
        95px !important;

    padding:
        12px 8px !important;

}


#accordionSidebar.toggled
.logo-circle-decoration {

    width:
        62px;

    height:
        62px;

}


#accordionSidebar.toggled
#sidebarLogo {

    width:
        34px !important;

    max-width:
        34px !important;

}


/* =========================================================
   MINI HEADING
========================================================= */

#accordionSidebar.toggled
.sidebar-heading {

    display:
        none !important;

}


/* =========================================================
   MINI MENU
========================================================= */

#accordionSidebar.toggled
.premium-menu {

    display:
        flex !important;

    align-items:
        center !important;

    justify-content:
        center !important;

    width:
        75px !important;

    height:
        50px !important;

    min-height:
        50px !important;

    margin:
        2px 0 !important;

    padding:
        0 !important;

    border-radius:
        0;

}


/* =========================================================
   MINI ICON
========================================================= */

#accordionSidebar.toggled
.menu-icon {

    width:
        75px;

    height:
        50px;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

}


#accordionSidebar.toggled
.menu-icon i {

    width:
        18px;

    height:
        18px;

    font-size:
        14px;

    line-height:
        18px;

}


/* =========================================================
   MINI HIDE TEXT
========================================================= */

#accordionSidebar.toggled
.menu-label,

#accordionSidebar.toggled
.menu-arrow {

    display:
        none !important;

}


/* =========================================================
   MINI HIDE DROPDOWN
========================================================= */

#accordionSidebar.toggled
.collapse,

#accordionSidebar.toggled
.submenu-container {

    display:
        none !important;

}


/* =========================================================
   MINI HOVER
========================================================= */

#accordionSidebar.toggled
.premium-menu:hover {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .16), rgba(255,255,255,.06)) !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold);

}


/* =========================================================
   MINI ACTIVE
========================================================= */

#accordionSidebar.toggled
.nav-item.active >
.premium-menu {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .22), rgba(255,255,255,.05)) !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold);

}


/* =========================================================
   MINI PARENT ACTIVE
========================================================= */

#accordionSidebar.toggled
.nav-item.menu-parent-active >
.premium-menu {

    background:
        transparent !important;

    box-shadow:
        none !important;

}


#accordionSidebar.toggled
.nav-item.menu-parent-active >
.premium-menu:hover {

    background:
        linear-gradient(90deg, rgba(217, 172, 79, .16), rgba(255,255,255,.06)) !important;

    box-shadow:
        inset 3px 0 0 var(--sb-gold) !important;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    #accordionSidebar {

        width:
            240px !important;

        min-width:
            240px !important;

    }

}

</style>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =================================================
           ELEMENT
        ================================================= */

        const sidebar =
            document.getElementById(
                'accordionSidebar'
            );


        const toggleButton =
            document.getElementById(
                'sidebarTogglePremium'
            );


        const sidebarLogo =
            document.getElementById(
                'sidebarLogo'
            );


        /* =================================================
           LOGO
        ================================================= */

        const logoNormal =
            "{{ asset('template/img/logo.png') }}";

        const logoMini =
            "{{ asset('template/img/logoicon_w.png') }}";


        /* =================================================
           UPDATE SIDEBAR
        ================================================= */

        function updateSidebar() {

            if (
                !sidebar ||
                !toggleButton
            ) {

                return;

            }


            const icon =
                toggleButton.querySelector('i');


            /* =============================================
               SIDEBAR KECIL
            ============================================== */

            if (
                sidebar.classList.contains('toggled')
            ) {

                if (sidebarLogo) {

                    sidebarLogo.src =
                        logoMini;

                }


                if (icon) {

                    icon.classList.remove(
                        'fa-chevron-left'
                    );

                    icon.classList.add(
                        'fa-chevron-right'
                    );

                }


                toggleButton.setAttribute(
                    'title',
                    'Perbesar Sidebar'
                );

            }


            /* =============================================
               SIDEBAR NORMAL
            ============================================== */

            else {

                if (sidebarLogo) {

                    sidebarLogo.src =
                        logoNormal;

                }


                if (icon) {

                    icon.classList.remove(
                        'fa-chevron-right'
                    );

                    icon.classList.add(
                        'fa-chevron-left'
                    );

                }


                toggleButton.setAttribute(
                    'title',
                    'Perkecil Sidebar'
                );

            }

        }


        /* =================================================
           TOGGLE SIDEBAR
        ================================================= */

        if (
            sidebar &&
            toggleButton
        ) {

            toggleButton.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();

                    event.stopPropagation();

                    sidebar.classList.toggle(
                        'toggled'
                    );

                    updateSidebar();

                }
            );

        }


        /* =================================================
           DROPDOWN ARROW
        ================================================= */

        const collapseMenus =
            document.querySelectorAll(
                '#accordionSidebar .premium-menu[data-toggle="collapse"]'
            );


        collapseMenus.forEach(
            function (menu) {

                const targetSelector =
                    menu.getAttribute('href');


                const target =
                    document.querySelector(
                        targetSelector
                    );


                if (!target) {

                    return;

                }


                /* =========================================
                   SAAT DROPDOWN DIBUKA
                ========================================== */

                $(target).on(
                    'shown.bs.collapse',
                    function () {

                        menu.classList.remove(
                            'collapsed'
                        );

                    }
                );


                /* =========================================
                   SAAT DROPDOWN DITUTUP
                ========================================== */

                $(target).on(
                    'hidden.bs.collapse',
                    function () {

                        menu.classList.add(
                            'collapsed'
                        );

                    }
                );

            }
        );


        /* =================================================
           INITIAL
        ================================================= */

        updateSidebar();

    }

);

</script>