
    @extends('layouts.app')

    @section('title', 'Dashboard')

    @section('content')


    <div class="container-fluid px-4 py-3">

        {{-- =========================================================
            HEADER
        ========================================================== --}}
        <div class="dashboard-header mb-4">

            <div class="header-content">

                <div>
                    <div class="header-label">
                        <i class="fas fa-chart-line mr-2"></i>
                        DASHBOARD
                    </div>

                    <h1>
                        Azzahra Salon & Spa Muslimah
                    </h1>

                    <p>
                        Monitoring pelanggan, perawatan, pegawai, dan reservasi
                    </p>
                </div>

                <div class="header-icon">
                    <img id="sidebarLogo"
                        src="{{ asset('template/img/logoicon_w.png') }}"
                        alt="Azzahra Salon & Spa Muslimah">
                </div>

            </div>

        </div>


        {{-- =========================================================
            KPI CARDS
        ========================================================== --}}
        <div class="row mb-4">

            {{-- PELANGGAN --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="modern-stat-card">

                    <div class="stat-icon maroon">
                        <i class="fas fa-users"></i>
                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Total Pelanggan
                        </span>

                        <h3>
                            {{ $jumlahPelanggan }}
                        </h3>

                        <small>
                            Data pelanggan
                        </small>

                    </div>

                </div>

            </div>


            {{-- PERAWATAN --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="modern-stat-card">

                    <div class="stat-icon gold">
                        <i class="fas fa-spa"></i>
                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Total Perawatan
                        </span>

                        <h3>
                            {{ $jumlahPerawatan }}
                        </h3>

                        <small>
                            Layanan tersedia
                        </small>

                    </div>

                </div>

            </div>


            {{-- PEGAWAI --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="modern-stat-card">

                    <div class="stat-icon ink">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Total Pegawai
                        </span>

                        <h3>
                            {{ $jumlahPegawai }}
                        </h3>

                        <small>
                            Data pegawai
                        </small>

                    </div>

                </div>

            </div>


            {{-- RESERVASI --}}
            <div class="col-xl-3 col-md-6 mb-3">

                <div class="modern-stat-card">

                    <div class="stat-icon success">
                        <i class="fas fa-calendar-check"></i>
                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Total Reservasi
                        </span>

                        <h3>
                            {{ $jumlahReservasi }}
                        </h3>

                        <small>
                            Seluruh reservasi
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            RESERVASI TERBARU + STATUS
        ========================================================== --}}
        <div class="row mb-4">


            {{-- =====================================================
                RESERVASI TERBARU
            ====================================================== --}}
            <div class="col-xl-8 mb-4">

                <div class="modern-card h-100">

                    <div class="modern-card-header">

                        <div>

                            <div class="section-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>

                            <div class="section-title">

                                <h5>
                                    Reservasi Terbaru
                                </h5>

                                <small>
                                    Daftar reservasi terbaru pelanggan
                                </small>

                            </div>

                        </div>
                        <a href="{{ route('reservasi.index') }}"
                        class="btn btn-outline-elite-mini">

                            Lihat Semua
                            <i class="fas fa-arrow-right ml-1"></i>

                        </a>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table modern-table mb-0">

                                <thead>

                                    <tr>

                                        <th width="5%">
                                            No
                                        </th>

                                        <th>
                                            Pelanggan
                                        </th>

                                        <th>
                                            Perawatan
                                        </th>

                                        <th>
                                            Pegawai
                                        </th>

                                        <th>
                                            Jadwal
                                        </th>

                                        <th class="text-center">
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse($reservasiTerbaru as $item)

                                        <tr>

                                            {{-- NO --}}
                                            <td class="text-center">

                                                <span class="number-badge">
                                                    {{ $loop->iteration }}
                                                </span>

                                            </td>


                                            {{-- PELANGGAN --}}
                                            <td>

                                                <div class="customer-info">

                                                    <div class="avatar-small">

                                                        <i class="fas fa-user"></i>

                                                    </div>

                                                    <div>

                                                        <strong>
                                                            {{ optional($item->pelanggan)->nama ?? '-' }}
                                                        </strong>

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- PERAWATAN --}}
                                            <td>

                                                <div class="service-name">

                                                    <i class="fas fa-spa mr-1"></i>

                                                    {{ optional($item->perawatan)->nama ?? '-' }}

                                                </div>

                                            </td>


                                            {{-- PEGAWAI --}}
                                            <td>

                                                <div class="employee-name">

                                                    <i class="fas fa-user-tie mr-1"></i>

                                                    {{ optional($item->pegawai)->nama ?? '-' }}

                                                </div>

                                            </td>


                                            {{-- JADWAL --}}
                                            <td>

                                                <div class="schedule-date">

                                                    <i class="far fa-calendar-alt mr-1"></i>

                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}

                                                </div>

                                                <div class="schedule-time">

                                                    <i class="far fa-clock mr-1"></i>

                                                    {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}

                                                    -

                                                    {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}

                                                </div>

                                            </td>


                                            {{-- STATUS --}}
                                            <td class="text-center">

                                                @if($item->status === 'Menunggu')

                                                    <span class="status-badge waiting">

                                                        <i class="fas fa-clock mr-1"></i>

                                                        Menunggu

                                                    </span>

                                                @elseif($item->status === 'Dikonfirmasi')

                                                    <span class="status-badge confirmed">

                                                        <i class="fas fa-check mr-1"></i>

                                                        Dikonfirmasi

                                                    </span>

                                                @elseif($item->status === 'Selesai')

                                                    <span class="status-badge success">

                                                        <i class="fas fa-check-circle mr-1"></i>

                                                        Selesai

                                                    </span>

                                                @elseif($item->status === 'Dibatalkan')

                                                    <span class="status-badge cancelled">

                                                        <i class="fas fa-times-circle mr-1"></i>

                                                        Dibatalkan

                                                    </span>

                                                @else

                                                    <span class="status-badge">

                                                        {{ $item->status }}

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td colspan="6"
                                                class="empty-data">

                                                <div>

                                                    <i class="fas fa-calendar-times"></i>

                                                    <h6>
                                                        Belum ada reservasi
                                                    </h6>

                                                    <small>
                                                        Data reservasi terbaru akan tampil di sini.
                                                    </small>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                STATUS RESERVASI
            ====================================================== --}}
            <div class="col-xl-4 mb-4">

                <div class="modern-card h-100">

                    <div class="modern-card-header">

                        <div>

                            <div class="section-icon alt">
                                <i class="fas fa-chart-pie"></i>
                            </div>

                            <div class="section-title">

                                <h5>
                                    Status Reservasi
                                </h5>

                                <small>
                                    Ringkasan status reservasi
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        {{-- MENUNGGU --}}
                        <div class="status-row">

                            <div class="status-left">

                                <div class="status-circle waiting-bg">
                                    <i class="fas fa-clock"></i>
                                </div>

                                <div>

                                    <strong>
                                        Menunggu
                                    </strong>

                                    <small>
                                        Menunggu pembayaran
                                    </small>

                                </div>

                            </div>

                            <div class="status-number waiting-text">

                                {{ $reservasiMenunggu }}

                            </div>

                        </div>


                        {{-- DIKONFIRMASI --}}
                        <div class="status-row">

                            <div class="status-left">

                                <div class="status-circle confirmed-bg">
                                    <i class="fas fa-check"></i>
                                </div>

                                <div>

                                    <strong>
                                        Dikonfirmasi
                                    </strong>

                                    <small>
                                        Reservasi terkonfirmasi
                                    </small>

                                </div>

                            </div>

                            <div class="status-number confirmed-text">

                                {{ $reservasiDikonfirmasi }}

                            </div>

                        </div>


                        {{-- SELESAI --}}
                        <div class="status-row">

                            <div class="status-left">

                                <div class="status-circle success-bg">
                                    <i class="fas fa-check-double"></i>
                                </div>

                                <div>

                                    <strong>
                                        Selesai
                                    </strong>

                                    <small>
                                        Perawatan selesai
                                    </small>

                                </div>

                            </div>

                            <div class="status-number success-text">

                                {{ $reservasiSelesai }}

                            </div>

                        </div>


                        {{-- DIBATALKAN --}}
                        <div class="status-row last">

                            <div class="status-left">

                                <div class="status-circle cancelled-bg">
                                    <i class="fas fa-times"></i>
                                </div>

                                <div>

                                    <strong>
                                        Dibatalkan
                                    </strong>

                                    <small>
                                        Reservasi dibatalkan
                                    </small>

                                </div>

                            </div>

                            <div class="status-number cancelled-text">

                                {{ $reservasiDibatalkan }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            MENU SISTEM
        ========================================================== --}}
        <div class="mb-3">

            <div class="section-heading">

                <div>

                    <h5>
                        <i class="fas fa-th-large mr-2"></i>
                        Menu Sistem
                    </h5>

                    <small>
                        Informasi dan pengelolaan sistem Azzahra
                    </small>

                </div>

            </div>

        </div>


        <div class="row mb-5">


            {{-- PERAWATAN --}}
            <div class="col-xl-4 col-md-6 mb-4">

                <div class="feature-card">

                    <div class="feature-icon maroon-icon">

                        <i class="fas fa-spa"></i>

                    </div>

                    <div class="feature-content">

                        <h5>
                            Perawatan
                        </h5>

                        <p>
                            Kelola daftar layanan perawatan,
                            durasi, harga, dan informasi layanan
                            yang tersedia di Azzahra Salon.
                        </p>

                        <a href="{{ route('perawatan.index') }}"
                        class="feature-link">

                            Kelola Perawatan

                            <i class="fas fa-arrow-right ml-1"></i>

                        </a>

                    </div>

                </div>

            </div>


            {{-- PEGAWAI --}}
            <div class="col-xl-4 col-md-6 mb-4">

                <div class="feature-card">

                    <div class="feature-icon ink-icon">

                        <i class="fas fa-user-tie"></i>

                    </div>

                    <div class="feature-content">

                        <h5>
                            Pegawai
                        </h5>

                        <p>
                            Kelola data pegawai dan informasi
                            pegawai yang digunakan dalam proses
                            reservasi pelanggan.
                        </p>

                        <a href="{{ route('pegawai.index') }}"
                        class="feature-link">

                            Kelola Pegawai

                            <i class="fas fa-arrow-right ml-1"></i>

                        </a>

                    </div>

                </div>

            </div>


            {{-- RESERVASI --}}
            <div class="col-xl-4 col-md-6 mb-4">

                <div class="feature-card">

                    <div class="feature-icon gold-icon">

                        <i class="fas fa-calendar-check"></i>

                    </div>

                    <div class="feature-content">

                        <h5>
                            Sistem Reservasi
                        </h5>

                        <p>
                            Kelola reservasi pelanggan dengan
                            pengecekan jadwal untuk membantu
                            mencegah tumpang tindih waktu.
                        </p>

                        <a href="{{ route('reservasi.index') }}"
                        class="feature-link">

                            Kelola Reservasi

                            <i class="fas fa-arrow-right ml-1"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STYLE — PREMIUM / ELITE EDITION
    ========================================================= --}}
    @push('styles')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>

    /* =========================================================
    DESIGN TOKENS — disamakan dengan sidebar/navbar/profile
    ========================================================= */

    :root {

        --clr-primary:      #e01c1d;
        --clr-primary-dark: #7c1013;
        --clr-primary-soft: #fff1f1;

        --clr-gold:         #d9ac4f;
        --clr-gold-dark:    #a9781f;
        --clr-gold-soft:    #fdf3e2;

        --clr-ink:          #23252b;
        --clr-ink-soft:     #6b6f7a;
        --clr-muted:        #9a9fab;

        --bg-body:          #f5f5f9;
        --bg-card:          #ffffff;

        --border-soft:      #edeef2;
        --border-gold-soft: rgba(217, 172, 79, .18);

        --radius-xl: 22px;
        --radius-lg: 18px;
        --radius-md: 14px;
        --radius-sm: 10px;

        --shadow-1: 0 4px 16px rgba(24, 24, 40, .05);
        --shadow-2: 0 14px 34px rgba(24, 24, 40, .09);
        --shadow-glow-red: 0 10px 24px rgba(224, 28, 29, .22);
        --shadow-glow-gold: 0 10px 24px rgba(217, 172, 79, .28);

        --ease: cubic-bezier(.22, 1, .36, 1);

    }


    /* =========================================================
    GENERAL
    ========================================================= */

    body {

        background:
            radial-gradient(circle at top right, #fdf2ee 0%, transparent 45%),
            var(--bg-body);

        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;

    }

    .container-fluid {

        color: var(--clr-ink-soft);

    }

    .container-fluid h1,
    .container-fluid h3,
    .container-fluid h5,
    .container-fluid h6,
    .container-fluid strong {

        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        color: var(--clr-ink);

    }

    @keyframes elite-fade-up {

        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    .dashboard-header,
    .modern-stat-card,
    .modern-card,
    .feature-card {

        animation: elite-fade-up .55s var(--ease) both;

    }

    .row .col-xl-3:nth-child(2) .modern-stat-card { animation-delay: .05s; }
    .row .col-xl-3:nth-child(3) .modern-stat-card { animation-delay: .1s; }
    .row .col-xl-3:nth-child(4) .modern-stat-card { animation-delay: .15s; }


    /* =========================================================
    HEADER
    ========================================================= */

    .dashboard-header {

        position: relative;

        overflow: hidden;

        background:
            radial-gradient(circle at 12% 0%, rgba(217, 172, 79, .18) 0%, transparent 45%),
            linear-gradient(135deg, #e01c1d 0%, #b5171a 48%, #7c1013 100%);

        border-radius: var(--radius-xl);

        padding: 34px 38px;

        box-shadow:
            0 18px 40px rgba(124, 16, 19, .28),
            inset 0 1px 0 rgba(255,255,255,.12);

        border: 1px solid rgba(255,255,255,.08);

    }

    .dashboard-header::before {

        content: '';

        position: absolute;

        inset: 0;

        background-image:
            repeating-linear-gradient(
                135deg,
                rgba(255,255,255,.035) 0px,
                rgba(255,255,255,.035) 1px,
                transparent 1px,
                transparent 14px
            );

        pointer-events: none;

    }

    .dashboard-header::after {

        content: '';

        position: absolute;

        width: 260px;
        height: 260px;

        right: -90px;
        top: -110px;

        border-radius: 50%;

        background: radial-gradient(circle, rgba(217, 172, 79, .22) 0%, transparent 70%);

    }

    .header-content {

        position: relative;

        z-index: 2;

        display: flex;

        justify-content: space-between;

        align-items: center;

    }

    .header-label {

        display: inline-flex;
        align-items: center;

        color: #ffe9c2;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: 2.2px;

        margin-bottom: 10px;

        padding: 4px 12px;

        border-radius: 999px;

        background: rgba(255,255,255,.10);

        border: 1px solid rgba(255,255,255,.16);

    }

    .dashboard-header h1 {

        font-family: 'Playfair Display', 'Plus Jakarta Sans', serif;

        color: #ffffff;

        font-size: 29px;

        font-weight: 700;

        letter-spacing: .2px;

        margin: 0 0 6px 0;

        text-shadow: 0 2px 12px rgba(0,0,0,.15);

    }

    .dashboard-header p {

        color: rgba(255,255,255,.86);

        margin: 0;

        font-size: 13.5px;

    }

    .header-icon {

        width: 78px;

        height: 78px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 22px;

        background: rgba(255,255,255,.12);

        border: 1px solid rgba(255,255,255,.22);

        box-shadow:
            inset 0 1px 0 rgba(255,255,255,.25),
            0 12px 24px rgba(0,0,0,.18);

        color: #ffffff;

        font-size: 34px;

    }

    .header-icon img {

        max-width: 42px;

        filter: drop-shadow(0 4px 8px rgba(0,0,0,.25));

    }


    /* =========================================================
    STAT CARD
    ========================================================= */

    .modern-stat-card {

        position: relative;

        background: var(--bg-card);

        border-radius: var(--radius-lg);

        padding: 24px 22px;

        display: flex;

        align-items: center;

        min-height: 128px;

        border: 1px solid var(--border-gold-soft);

        box-shadow: var(--shadow-1);

        overflow: hidden;

        transition: transform .3s var(--ease), box-shadow .3s var(--ease);

    }

    .modern-stat-card::before {

        content: '';

        position: absolute;

        left: 0; top: 0;

        width: 100%;

        height: 3px;

        background: linear-gradient(90deg, var(--clr-primary), var(--clr-gold));

        opacity: 0;

        transition: opacity .3s ease;

    }

    .modern-stat-card:hover {

        transform: translateY(-6px);

        box-shadow: var(--shadow-2);

    }

    .modern-stat-card:hover::before {

        opacity: 1;

    }

    .stat-icon {

        width: 58px;

        height: 58px;

        min-width: 58px;

        border-radius: 16px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 22px;

        margin-right: 18px;

        box-shadow: inset 0 0 0 1px rgba(0,0,0,.02);

        transition: transform .3s var(--ease);

    }

    .modern-stat-card:hover .stat-icon {

        transform: scale(1.08) rotate(-4deg);

    }

    .stat-icon.maroon {

        color: #ffffff;

        background: linear-gradient(140deg, #ec3b3c, #7c1013);

        box-shadow: var(--shadow-glow-red);

    }

    .stat-icon.gold {

        color: #ffffff;

        background: linear-gradient(140deg, #e9c580, var(--clr-gold-dark));

        box-shadow: var(--shadow-glow-gold);

    }

    .stat-icon.ink {

        color: #ffffff;

        background: linear-gradient(140deg, #4a4d56, var(--clr-ink));

        box-shadow: 0 10px 24px rgba(35, 37, 43, .22);

    }

    .stat-icon.success {

        color: #ffffff;

        background: linear-gradient(140deg, #29c98c, #0e8a5c);

        box-shadow: 0 10px 24px rgba(23, 184, 128, .22);

    }

    .stat-content {

        min-width: 0;

    }

    .stat-title {

        display: block;

        color: var(--clr-muted);

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .8px;

    }

    .stat-content h3 {

        font-size: 29px;

        color: var(--clr-ink);

        font-weight: 800;

        letter-spacing: -.3px;

        margin: 4px 0 2px;

        font-variant-numeric: tabular-nums;

    }

    .stat-content small {

        color: var(--clr-muted);

        font-size: 11.5px;

    }


    /* =========================================================
    MODERN CARD
    ========================================================= */

    .modern-card {

        background: var(--bg-card);

        border-radius: var(--radius-lg);

        border: 1px solid var(--border-gold-soft);

        box-shadow: var(--shadow-1);

        overflow: hidden;

        transition: box-shadow .3s var(--ease);

    }

    .modern-card:hover {

        box-shadow: var(--shadow-2);

    }

    .modern-card-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 22px 24px;

        border-bottom: 1px solid var(--border-gold-soft);

        background: linear-gradient(180deg, #fffdf9, #ffffff);

    }

    .modern-card-header > div:first-child {

        display: flex;

        align-items: center;

    }

    .section-icon {

        width: 44px;

        height: 44px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: linear-gradient(140deg, #ec3b3c, #7c1013);

        color: #ffffff;

        border-radius: 13px;

        margin-right: 14px;

        box-shadow: var(--shadow-glow-red);

        font-size: 16px;

    }

    .section-icon.alt {

        background: linear-gradient(140deg, #e9c580, var(--clr-gold-dark));

        box-shadow: var(--shadow-glow-gold);

    }

    .section-title h5 {

        margin: 0;

        color: var(--clr-ink);

        font-weight: 700;

        font-size: 16px;

        letter-spacing: -.1px;

    }

    .section-title small {

        color: var(--clr-muted);

        font-size: 12px;

    }


    /* =========================================================
    TABLE
    ========================================================= */

    .modern-table thead {

        background: #fdf9f2;

    }

    .modern-table thead th {

        border-top: 0;

        border-bottom: 1px solid var(--border-gold-soft);

        color: #a9906f;

        font-size: 10.5px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .6px;

        padding: 14px 12px;

        white-space: nowrap;

    }

    .modern-table tbody td {

        border-top: 1px solid #f5ece0;

        padding: 15px 12px;

        font-size: 13px;

        color: var(--clr-ink-soft);

        vertical-align: middle;

    }

    .modern-table tbody tr {

        transition: background .2s ease;

    }

    .modern-table tbody tr:hover {

        background: #fffaf2;

    }

    .number-badge {

        width: 27px;

        height: 27px;

        border-radius: 8px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        background: #fdf3e2;

        color: var(--clr-gold-dark);

        font-size: 11.5px;

        font-weight: 700;

    }

    .customer-info {

        display: flex;

        align-items: center;

    }

    .avatar-small {

        width: 35px;

        height: 35px;

        min-width: 35px;

        display: flex;

        align-items: center;

        justify-content: center;

        background: linear-gradient(140deg, #ec3b3c, #7c1013);

        color: #ffffff;

        border-radius: 10px;

        margin-right: 10px;

        box-shadow: 0 6px 14px rgba(224, 28, 29, .22);

        font-size: 13px;

    }

    .customer-info strong {

        color: var(--clr-ink);

        font-size: 13px;

        font-weight: 600;

    }

    .service-name {

        color: var(--clr-primary);

        font-weight: 600;

    }

    .employee-name {

        color: var(--clr-ink-soft);

    }

    .schedule-date {

        font-weight: 700;

        color: #3d3f45;

    }

    .schedule-time {

        color: var(--clr-muted);

        font-size: 11px;

        margin-top: 3px;

    }


    /* =========================================================
    STATUS BADGE
    ========================================================= */

    .status-badge {

        display: inline-flex;

        align-items: center;

        padding: 6px 12px;

        border-radius: 999px;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: .2px;

        white-space: nowrap;

    }

    .status-badge.waiting {

        color: #8a5c00;

        background: #fff2cf;

    }

    .status-badge.confirmed {

        color: #a9781f;

        background: #fdf3e2;

    }

    .status-badge.success {

        color: #146c46;

        background: #dff5eb;

    }

    .status-badge.cancelled {

        color: #a11f16;

        background: #fde6e5;

    }


    /* =========================================================
    EMPTY DATA
    ========================================================= */

    .empty-data {

        padding: 52px !important;

        text-align: center;

        color: var(--clr-muted);

    }

    .empty-data i {

        font-size: 36px;

        margin-bottom: 12px;

        color: #ecdcc0;

    }

    .empty-data h6 {

        color: #7a7d86;

        font-weight: 700;

        margin-bottom: 4px;

    }

    .empty-data small {

        color: var(--clr-muted);

    }


    /* =========================================================
    RESERVATION STATUS
    ========================================================= */

    .status-row {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 16px 6px;

        margin: 0 -6px;

        border-radius: var(--radius-sm);

        border-bottom: 1px solid var(--border-gold-soft);

        transition: background .2s ease;

    }

    .status-row:hover {

        background: #fffaf2;

    }

    .status-row.last {

        border-bottom: 0;

    }

    .status-left {

        display: flex;

        align-items: center;

    }

    .status-circle {

        width: 42px;

        height: 42px;

        min-width: 42px;

        border-radius: 13px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 13px;

        font-size: 15px;

    }

    .status-left strong {

        display: block;

        color: #383a40;

        font-size: 13px;

        font-weight: 700;

    }

    .status-left small {

        display: block;

        color: var(--clr-muted);

        font-size: 10.5px;

        margin-top: 2px;

    }

    .status-number {

        font-size: 23px;

        font-weight: 800;

        font-variant-numeric: tabular-nums;

    }

    .waiting-bg {

        background: #fff2cf;

        color: #c98a00;

    }

    .confirmed-bg {

        background: #fdf3e2;

        color: var(--clr-gold-dark);

    }

    .success-bg {

        background: #dff5eb;

        color: #17b880;

    }

    .cancelled-bg {

        background: #fde6e5;

        color: var(--clr-primary);

    }

    .waiting-text {

        color: #c98a00;

    }

    .confirmed-text {

        color: var(--clr-gold-dark);

    }

    .success-text {

        color: #17b880;

    }

    .cancelled-text {

        color: var(--clr-primary);

    }


    /* =========================================================
    SECTION HEADING
    ========================================================= */

    .section-heading {

        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 16px;

        padding-left: 12px;

        border-left: 3px solid var(--clr-primary);

    }

    .section-heading h5 {

        font-weight: 700;

        color: var(--clr-ink);

        margin: 0;

        font-size: 16.5px;

    }

    .section-heading h5 i {

        color: var(--clr-primary);

    }

    .section-heading small {

        color: var(--clr-muted);

    }


    /* =========================================================
    FEATURE CARD
    ========================================================= */

    .feature-card {

        position: relative;

        height: 100%;

        background: var(--bg-card);

        border: 1px solid var(--border-gold-soft);

        border-radius: var(--radius-lg);

        padding: 24px;

        display: flex;

        overflow: hidden;

        box-shadow: var(--shadow-1);

        transition: transform .3s var(--ease), box-shadow .3s var(--ease);

    }

    .feature-card::after {

        content: '';

        position: absolute;

        left: 0; right: 0; bottom: 0;

        height: 3px;

        background: linear-gradient(90deg, var(--clr-primary), var(--clr-gold));

        transform: scaleX(0);

        transform-origin: left;

        transition: transform .35s var(--ease);

    }

    .feature-card:hover {

        transform: translateY(-6px);

        box-shadow: var(--shadow-2);

    }

    .feature-card:hover::after {

        transform: scaleX(1);

    }

    .feature-icon {

        width: 50px;

        height: 50px;

        min-width: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 14px;

        font-size: 20px;

        margin-right: 16px;

        color: #ffffff;

        transition: transform .3s var(--ease);

    }

    .feature-card:hover .feature-icon {

        transform: scale(1.08) rotate(-4deg);

    }

    .maroon-icon {

        background: linear-gradient(140deg, #ec3b3c, #7c1013);

        box-shadow: var(--shadow-glow-red);

    }

    .ink-icon {

        background: linear-gradient(140deg, #4a4d56, var(--clr-ink));

        box-shadow: 0 10px 22px rgba(35, 37, 43, .22);

    }

    .gold-icon {

        background: linear-gradient(140deg, #e9c580, var(--clr-gold-dark));

        box-shadow: var(--shadow-glow-gold);

    }

    .feature-content h5 {

        font-size: 15.5px;

        font-weight: 700;

        color: var(--clr-ink);

        margin-bottom: 8px;

    }

    .feature-content p {

        color: #8b8e97;

        font-size: 12px;

        line-height: 1.75;

        margin-bottom: 12px;

    }

    .feature-link {

        display: inline-flex;

        align-items: center;

        color: var(--clr-primary);

        font-size: 11.5px;

        font-weight: 700;

        text-decoration: none;

        letter-spacing: .2px;

        transition: gap .25s ease, color .2s ease;

    }

    .feature-link i {

        transition: transform .25s ease;

    }

    .feature-link:hover {

        color: var(--clr-primary-dark);

        text-decoration: none;

    }

    .feature-link:hover i {

        transform: translateX(4px);

    }


    /* =========================================================
    BUTTON — Lihat Semua (mini outline)
    ========================================================= */

    .btn-outline-elite-mini {

        color: var(--clr-primary-dark);

        border: 1.5px solid var(--clr-gold);

        background: transparent;

        border-radius: 999px;

        font-size: 11px;

        font-weight: 700;

        padding: 7px 16px;

        transition: all .25s ease;

        display: inline-flex;

        align-items: center;

    }

    .btn-outline-elite-mini:hover {

        background: linear-gradient(140deg, #ec3b3c, #7c1013);

        border-color: transparent;

        color: #ffffff;

        box-shadow: var(--shadow-glow-red);

        transform: translateY(-1px);

    }


    /* =========================================================
    RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .container-fluid {

            padding-left: 15px !important;

            padding-right: 15px !important;

        }

        .dashboard-header {

            padding: 24px;

            border-radius: var(--radius-lg);

        }

        .dashboard-header h1 {

            font-size: 21px;

        }

        .header-icon {

            display: none;

        }

        .modern-card-header {

            align-items: flex-start;

            flex-direction: column;

            gap: 12px;

        }

        .modern-table {

            min-width: 850px;

        }

        .feature-card {

            padding: 18px;

        }

    }

    </style>

    @endpush

    @endsection
