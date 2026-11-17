<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan Data Reservasi</title>

    <style>

        @page {
            margin: 25px 30px 35px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #2c3e50;
            margin-top: 90px;
        }

        /* =====================================================
           WATERMARK
        ====================================================== */

        .watermark {
            position: fixed;
            top: 25%;
            left: 15%;
            width: 70%;
            opacity: 0.05;
            z-index: -1;
        }

        /* =====================================================
           HEADER
        ====================================================== */

        .header {
            position: fixed;
            top: -10px;
            left: 0;
            right: 0;

            border-bottom: 3px solid #dc3545;

            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .salon-name {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 3px;
        }

        .salon-info {
            font-size: 10px;
            color: #555;
        }

        .header-right {
            text-align: right;
            font-size: 9px;
            color: #777;
        }

        /* =====================================================
           TITLE
        ====================================================== */

        .title {
            text-align: center;
            margin-bottom: 18px;
        }

        .title h3 {
            margin: 0;
            font-size: 16px;
            color: #2c3e50;
        }

        .title p {
            margin: 4px 0 0;
            font-size: 9px;
            color: #777;
        }

        /* =====================================================
           CARD
        ====================================================== */

        .card {
            border: 1px solid #f1b0b7;
            border-left: 4px solid #dc3545;
            padding: 10px;
            margin-top: 12px;
            margin-bottom: 18px;
            background: #fff;
        }

        .card-title {
            color: #dc3545;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 7px;
        }

        /* =====================================================
           INFO LAPORAN
        ====================================================== */

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 3px 5px;
            font-size: 9px;
        }

        .info-label {
            width: 20%;
            font-weight: bold;
            color: #555;
        }

        /* =====================================================
           KPI
        ====================================================== */

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary-grid td {
            width: 20%;
            padding: 4px;
            border: none;
            vertical-align: top;
        }

        .kpi-box {
            border: 1px solid #f1b0b7;
            border-left: 4px solid #dc3545;
            background: #fff8f8;
            padding: 9px;
            text-align: center;
        }

        .kpi-title {
            color: #dc3545;
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        /* =====================================================
           TABLE
        ====================================================== */

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 18px;
        }

        table.data th {
            background: #dc3545;
            color: white;
            border: 1px solid #c82333;
            padding: 6px;
            text-align: center;
            font-size: 8.5px;
        }

        table.data td {
            border: 1px solid #eee;
            padding: 6px;
            font-size: 8.5px;
            vertical-align: middle;
        }

        table.data tr:nth-child(even) td {
            background: #fff5f5;
        }

        /* =====================================================
           STATUS
        ====================================================== */

        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .status-table th {
            background: #dc3545;
            color: white;
            padding: 6px;
            border: 1px solid #c82333;
            font-size: 9px;
        }

        .status-table td {
            padding: 6px;
            border: 1px solid #eee;
            font-size: 9px;
        }

        .status-table tr:nth-child(even) td {
            background: #fff5f5;
        }

        /* =====================================================
           BADGE STATUS
        ====================================================== */

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .badge-dikonfirmasi {
            background: #cfe2ff;
            color: #084298;
        }

        .badge-selesai {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-dibatalkan {
            background: #f8d7da;
            color: #842029;
        }

        /* =====================================================
           TEXT
        ====================================================== */

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-muted {
            color: #777;
        }

        /* =====================================================
           KESIMPULAN
        ====================================================== */

        .conclusion {
            border: 1px solid #f1b0b7;
            border-left: 4px solid #dc3545;
            background: #fff8f8;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .conclusion-title {
            color: #dc3545;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .conclusion-text {
            font-size: 9px;
            line-height: 1.5;
            text-align: justify;
        }

        /* =====================================================
           TANDA TANGAN
        ====================================================== */

        .footer {
            margin-top: 30px;
            font-size: 9px;
        }

        .ttd {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        /* =====================================================
           CLEAR
        ====================================================== */

        .clear {
            clear: both;
        }

    </style>

</head>

<body>

    {{-- =====================================================
         WATERMARK
    ====================================================== --}}

    @php
        $logoPath = public_path('template/img/logoicon.png');

        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        } else {
            $logoSrc = '';
        }
    @endphp

    @if($logoSrc)
        <img src="{{ $logoSrc }}" class="watermark">
    @endif


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                {{-- LOGO KIRI --}}
                <td width="70">

                    @if($logoSrc)

                        <img
                            src="{{ $logoSrc }}"
                            class="logo"
                        >

                    @endif

                </td>


                {{-- INFORMASI SALON --}}
                <td>

                    <div class="salon-name">
                        AZZAHRA SALON DAN SPA MUSLIMAH
                    </div>

                    <div class="salon-info">
                        Sistem Reservasi Berbasis Website
                    </div>

                    @if(!empty($alamat_salon))

                        <div class="salon-info">
                            {{ $alamat_salon }}
                        </div>

                    @endif

                    @if(!empty($telepon_salon))

                        <div class="salon-info">
                            Telp. {{ $telepon_salon }}
                        </div>

                    @endif

                </td>


                {{-- INFO KANAN --}}
                <td width="100" class="header-right">

                    LAPORAN RESERVASI

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         JUDUL
    ====================================================== --}}

    <div class="title">

        <h3>
            LAPORAN DATA RESERVASI
        </h3>

        <p>
            Rekapitulasi data reservasi pelanggan
        </p>

    </div>


    {{-- =====================================================
         INFORMASI LAPORAN
    ====================================================== --}}

    <div class="card">

        <div class="card-title">
            INFORMASI LAPORAN
        </div>

        <table class="info-table">

            <tr>

                <td class="info-label">
                    Nomor Laporan
                </td>

                <td>
                    : {{ $no_laporan }}
                </td>

            </tr>

            <tr>

                <td class="info-label">
                    Tanggal Laporan
                </td>

                <td>
                    : {{ $tanggalLaporan }}
                </td>

            </tr>

            <tr>

                <td class="info-label">
                    Periode
                </td>

                <td>
                    : {{ $periode }}
                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         KPI / RINGKASAN
    ====================================================== --}}

    <table class="summary-grid">

        <tr>

            {{-- TOTAL --}}
            <td>

                <div class="kpi-box">

                    <div class="kpi-title">
                        TOTAL RESERVASI
                    </div>

                    <div class="kpi-value">
                        {{ $total }}
                    </div>

                </div>

            </td>


            {{-- MENUNGGU --}}
            <td>

                <div class="kpi-box">

                    <div class="kpi-title">
                        MENUNGGU
                    </div>

                    <div class="kpi-value">
                        {{ $menunggu }}
                    </div>

                </div>

            </td>


            {{-- DIKONFIRMASI --}}
            <td>

                <div class="kpi-box">

                    <div class="kpi-title">
                        DIKONFIRMASI
                    </div>

                    <div class="kpi-value">
                        {{ $dikonfirmasi }}
                    </div>

                </div>

            </td>


            {{-- SELESAI --}}
            <td>

                <div class="kpi-box">

                    <div class="kpi-title">
                        SELESAI
                    </div>

                    <div class="kpi-value">
                        {{ $selesai }}
                    </div>

                </div>

            </td>


            {{-- DIBATALKAN --}}
            <td>

                <div class="kpi-box">

                    <div class="kpi-title">
                        DIBATALKAN
                    </div>

                    <div class="kpi-value">
                        {{ $dibatalkan }}
                    </div>

                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         DISTRIBUSI STATUS
    ====================================================== --}}

    <div class="card">

        <div class="card-title">
            DISTRIBUSI STATUS RESERVASI
        </div>

        <table class="status-table">

            <thead>

                <tr>

                    <th width="35%">
                        Status
                    </th>

                    <th width="25%">
                        Jumlah
                    </th>

                    <th width="40%">
                        Persentase
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        Menunggu
                    </td>

                    <td class="text-center">
                        {{ $menunggu }}
                    </td>

                    <td class="text-center">

                        {{ $total > 0
                            ? number_format(($menunggu / $total) * 100, 1)
                            : 0
                        }}%

                    </td>

                </tr>


                <tr>

                    <td>
                        Dikonfirmasi
                    </td>

                    <td class="text-center">
                        {{ $dikonfirmasi }}
                    </td>

                    <td class="text-center">

                        {{ $total > 0
                            ? number_format(($dikonfirmasi / $total) * 100, 1)
                            : 0
                        }}%

                    </td>

                </tr>


                <tr>

                    <td>
                        Selesai
                    </td>

                    <td class="text-center">
                        {{ $selesai }}
                    </td>

                    <td class="text-center">

                        {{ $total > 0
                            ? number_format(($selesai / $total) * 100, 1)
                            : 0
                        }}%

                    </td>

                </tr>


                <tr>

                    <td>
                        Dibatalkan
                    </td>

                    <td class="text-center">
                        {{ $dibatalkan }}
                    </td>

                    <td class="text-center">

                        {{ $total > 0
                            ? number_format(($dibatalkan / $total) * 100, 1)
                            : 0
                        }}%

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         DATA RESERVASI
    ====================================================== --}}

    <div class="card">

        <div class="card-title">
            DATA RESERVASI
        </div>

        <table class="data">

            <thead>

                <tr>

                    <th width="4%">
                        No
                    </th>

                    <th width="13%">
                        Pelanggan
                    </th>

                    <th width="13%">
                        Perawatan
                    </th>

                    <th width="12%">
                        Pegawai
                    </th>

                    <th width="10%">
                        Tanggal
                    </th>

                    <th width="12%">
                        Waktu
                    </th>

                    <th width="10%">
                        Status
                    </th>

                    <th width="16%">
                        Catatan
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reservasi as $item)

                    <tr>

                        {{-- NO --}}
                        <td class="text-center">
                            {{ $loop->iteration }}
                        </td>


                        {{-- PELANGGAN --}}
                        <td>
                            {{ $item->pelanggan->nama ?? '-' }}
                        </td>


                        {{-- PERAWATAN (REVISI: tampil semua perawatan) --}}
                        <td>

                            @php

                                $daftarPerawatan = $item->perawatans;

                                if (
                                    (!$daftarPerawatan || $daftarPerawatan->count() === 0)
                                    && $item->perawatan
                                ) {

                                    $daftarPerawatan = collect([$item->perawatan]);

                                }

                            @endphp

                            @if($daftarPerawatan && $daftarPerawatan->count() > 0)

                                @foreach($daftarPerawatan as $perawatan)

                                    {{ $perawatan->nama }}

                                    @if(isset($perawatan->pivot) && ($perawatan->pivot->jumlah ?? 1) > 1)
                                        (x{{ $perawatan->pivot->jumlah }})
                                    @endif

                                    @if(!$loop->last)
                                        <br>
                                    @endif

                                @endforeach

                            @else

                                -

                            @endif

                        </td>


                        {{-- PEGAWAI --}}
                        <td>
                            {{ $item->pegawai->nama ?? '-' }}
                        </td>


                        {{-- TANGGAL (REVISI: aman walau tanggal belum di-cast) --}}
                        <td class="text-center">

                            @if($item->tanggal)

                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- WAKTU --}}
                        <td class="text-center">

                            @if($item->jam_mulai && $item->jam_selesai)

                                {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td class="text-center">

                            @if(strtolower($item->status) == 'menunggu')

                                <span class="badge badge-menunggu">
                                    Menunggu
                                </span>

                            @elseif(strtolower($item->status) == 'dikonfirmasi')

                                <span class="badge badge-dikonfirmasi">
                                    Dikonfirmasi
                                </span>

                            @elseif(strtolower($item->status) == 'selesai')

                                <span class="badge badge-selesai">
                                    Selesai
                                </span>

                            @elseif(strtolower($item->status) == 'dibatalkan')

                                <span class="badge badge-dibatalkan">
                                    Dibatalkan
                                </span>

                            @else

                                {{ $item->status ?? '-' }}

                            @endif

                        </td>


                        {{-- CATATAN --}}
                        <td>
                            {{ $item->catatan ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center"
                        >

                            Tidak ada data reservasi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         KESIMPULAN
    ====================================================== --}}

    <div class="conclusion">

        <div class="conclusion-title">
            KESIMPULAN
        </div>

        <div class="conclusion-text">

            Berdasarkan laporan reservasi pada periode
            <strong>{{ $periode }}</strong>,
            terdapat sebanyak
            <strong>{{ $total }}</strong>
            reservasi.

            Dari keseluruhan reservasi tersebut,
            sebanyak
            <strong>{{ $menunggu }}</strong>
            reservasi masih menunggu,
            <strong>{{ $dikonfirmasi }}</strong>
            reservasi telah dikonfirmasi,
            <strong>{{ $selesai }}</strong>
            reservasi telah selesai,
            dan
            <strong>{{ $dibatalkan }}</strong>
            reservasi dibatalkan.

            Data ini dapat digunakan sebagai bahan evaluasi
            dan pemantauan pelayanan reservasi
            pada AZZAHRA SALON DAN SPA MUSLIMAH.

        </div>

    </div>


    {{-- =====================================================
         TANDA TANGAN
    ====================================================== --}}

    <div class="footer">

        <div class="ttd">

            Yogyakarta,
            {{ $tanggalLaporan }}

            <br>

            Mengetahui,

            <div class="signature-line">

                <strong>
                    Pimpinan
                </strong>

            </div>

        </div>

        <div class="clear"></div>

    </div>


    {{-- =====================================================
         NOMOR HALAMAN
         REVISI: koordinat disesuaikan untuk kertas A4 landscape
         (lebar 842 x tinggi 595). Sebelumnya 520, 820 sehingga
         berada di luar halaman.
    ====================================================== --}}

    <script type="text/php">

        if (isset($pdf)) {

            $pdf->page_text(
                700,
                565,
                "Halaman {PAGE_NUM} / {PAGE_COUNT}",
                null,
                10
            );

        }

    </script>


</body>

</html>