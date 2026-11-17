<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan Data Promo</title>

    <style>

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #2c3e50;
            margin-top: 90px;
        }


        /* ================= WATERMARK ================= */

        .watermark {
            position: fixed;
            top: 25%;
            left: 15%;
            width: 70%;
            opacity: 0.05;
            z-index: -1;
        }


        /* ================= HEADER ================= */

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;

            border-bottom: 3px solid #dc3545;
            padding-bottom: 12px;
        }

        .salon-name {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }

        .salon-info {
            font-size: 11px;
        }


        /* ================= TITLE ================= */

        .title {
            text-align: center;
            margin: 15px 0;
        }

        .title h3 {
            margin: 0;
        }


        /* ================= META ================= */

        .meta {
            margin-bottom: 10px;
        }


        /* ================= CARD ================= */

        .card {
            border: 1px solid #f1b0b7;
            border-left: 4px solid #dc3545;

            padding: 10px;

            margin-top: 20px;
            margin-bottom: 20px;
        }

        .card-title {
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 8px;
        }


        /* ================= TABLE ================= */

        table.data {
            width: 100%;
            border-collapse: collapse;

            margin-top: 10px;
            margin-bottom: 20px;
        }

        table.data th {
            background: #dc3545;
            color: white;

            padding: 6px;

            font-size: 11px;
        }

        table.data td {
            border: 1px solid #eee;

            padding: 6px;

            vertical-align: top;
        }

        table.data tr:nth-child(even) {
            background: #fff5f5;
        }


        /* ================= SUMMARY ================= */

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-grid td {
            width: 33.33%;
            padding: 8px;
            vertical-align: top;
        }

        .summary-grid.two td {
            width: 50%;
        }


        .kpi-box {
            border: 1px solid #f1b0b7;
            border-left: 4px solid #dc3545;

            background: #fff8f8;

            padding: 10px;
        }

        .kpi-title {
            color: #dc3545;
            font-weight: bold;

            margin-bottom: 5px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
        }


        /* ================= TEXT ================= */

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }


        /* ================= STATUS ================= */

        .status-aktif {
            font-weight: bold;
            color: #198754;
        }

        .status-nonaktif {
            font-weight: bold;
            color: #dc3545;
        }


        /* ================= PENANDA MEMBER ================= */

        .badge-member {
            background: #ffc107;
            color: #212529;

            padding: 1px 5px;

            font-size: 9px;
            font-weight: bold;
        }


        /* ================= FOOTER ================= */

        .footer {
            margin-top: 40px;
        }

        .ttd {
            float: right;

            width: 200px;

            text-align: center;
        }

        .signature-line {
            margin-top: 50px;

            border-top: 1px solid #000;
        }

    </style>

</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | LOGO
    |--------------------------------------------------------------------------
    */

    $logoPath = public_path('template/img/logoicon.png');

    $logo = file_exists($logoPath)
        ? base64_encode(file_get_contents($logoPath))
        : null;


    /*
    |--------------------------------------------------------------------------
    | DATA PROMO
    |--------------------------------------------------------------------------
    */

    $totalPromo = $promo->count();

    $jumlahAktif = $promo
        ->where('status', 'Aktif')
        ->count();

    $jumlahTidakAktif = $promo
        ->where('status', '!=', 'Aktif')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | PERSENTASE
    |--------------------------------------------------------------------------
    */

    $persenAktif = $totalPromo > 0
        ? round(($jumlahAktif / $totalPromo) * 100, 2)
        : 0;

    $persenTidakAktif = $totalPromo > 0
        ? round(($jumlahTidakAktif / $totalPromo) * 100, 2)
        : 0;

@endphp



{{-- ========================================================= --}}
{{-- WATERMARK --}}
{{-- ========================================================= --}}

@if($logo)

    <img
        src="data:image/png;base64,{{ $logo }}"
        class="watermark"
    >

@endif



{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="header">

    <table width="100%">

        <tr>


            {{-- LOGO KIRI --}}

            <td width="70">

                @if($logo)

                    <img
                        src="data:image/png;base64,{{ $logo }}"
                        width="60"
                    >

                @endif

            </td>



            {{-- INFORMASI SALON --}}

            <td class="text-center">

                <div class="salon-name">

                    AZZAHRA SALON DAN SPA MUSLIMAH

                </div>


                <div class="salon-info">

                    Sistem Reservasi Berbasis Website


                    @if(isset($alamat_salon))

                        <br>

                        {{ $alamat_salon }}

                    @endif


                    @if(isset($telepon_salon))

                        <br>

                        Telp: {{ $telepon_salon }}

                    @endif

                </div>

            </td>



            {{-- LOGO KANAN --}}

            <td width="70" class="text-right">

                @if($logo)

                    <img
                        src="data:image/png;base64,{{ $logo }}"
                        width="60"
                    >

                @endif

            </td>


        </tr>

    </table>

</div>



{{-- ========================================================= --}}
{{-- TITLE --}}
{{-- ========================================================= --}}

<div class="title">

    <h3>

        LAPORAN DATA PROMO

    </h3>

</div>



{{-- ========================================================= --}}
{{-- META --}}
{{-- ========================================================= --}}

<div class="meta">

    No Laporan :

    <b>

        {{ $no_laporan }}

    </b>


    <br>


    Tanggal :

    {{ \Carbon\Carbon::parse($tanggalLaporan)->translatedFormat('d F Y') }}


    @if(isset($periode))

        <br>

        Periode :

        <b>

            {{ $periode }}

        </b>

    @endif

</div>



{{-- ========================================================= --}}
{{-- RINGKASAN PROMO --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Ringkasan Promo

    </div>


    <table class="summary-grid">

        <tr>


            {{-- TOTAL --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Total Promo

                    </div>


                    <div class="kpi-value">

                        {{ number_format($totalPromo) }}

                    </div>

                </div>

            </td>



            {{-- AKTIF --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Promo Aktif

                    </div>


                    <div class="kpi-value">

                        {{ number_format($jumlahAktif) }}

                    </div>

                </div>

            </td>



            {{-- TIDAK AKTIF --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Promo Tidak Aktif

                    </div>


                    <div class="kpi-value">

                        {{ number_format($jumlahTidakAktif) }}

                    </div>

                </div>

            </td>


        </tr>

    </table>

</div>



{{-- ========================================================= --}}
{{-- DISTRIBUSI STATUS PROMO --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Distribusi Status Promo

    </div>


    <table class="data">

        <thead>

            <tr>

                <th>

                    Status Promo

                </th>


                <th>

                    Jumlah

                </th>


                <th>

                    Persentase

                </th>

            </tr>

        </thead>


        <tbody>


            {{-- AKTIF --}}

            <tr>

                <td>

                    Promo Aktif

                </td>


                <td class="text-center">

                    {{ number_format($jumlahAktif) }}

                </td>


                <td class="text-center">

                    {{ $persenAktif }}%

                </td>

            </tr>



            {{-- TIDAK AKTIF --}}

            <tr>

                <td>

                    Promo Tidak Aktif

                </td>


                <td class="text-center">

                    {{ number_format($jumlahTidakAktif) }}

                </td>


                <td class="text-center">

                    {{ $persenTidakAktif }}%

                </td>

            </tr>


        </tbody>

    </table>

</div>



{{-- ========================================================= --}}
{{-- DATA PROMO --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Data Promo

    </div>


    <table class="data">

        <thead>

            <tr>


                <th width="5%">

                    No

                </th>


                <th width="20%">

                    Nama Promo

                </th>


                <th width="12%">

                    Jenis

                </th>


                <th width="13%">

                    Nilai

                </th>


                <th width="15%">

                    Tanggal Mulai

                </th>


                <th width="15%">

                    Tanggal Selesai

                </th>


                <th width="10%">

                    Status

                </th>


            </tr>

        </thead>


        <tbody>


            @forelse($promo as $i => $item)

                <tr>


                    {{-- NOMOR --}}

                    <td class="text-center">

                        {{ $i + 1 }}

                    </td>



                    {{-- NAMA PROMO --}}

                    <td>

                        {{ $item->nama }}

                        @if($item->khusus_member)
                            <span class="badge-member">Member</span>
                        @endif

                    </td>



                    {{-- JENIS --}}

                    <td class="text-center">

                        {{ $item->jenis }}

                    </td>



                    {{-- NILAI --}}

                    <td class="text-right">


                        @if($item->jenis === 'Persentase')

                            {{ number_format(
                                $item->nilai,
                                0,
                                ',',
                                '.'
                            ) }}%


                        @else

                            Rp {{ number_format(
                                $item->nilai,
                                0,
                                ',',
                                '.'
                            ) }}

                        @endif


                    </td>



                    {{-- TANGGAL MULAI --}}

                    <td class="text-center">

                        {{ $item->tanggal_mulai
                            ? \Carbon\Carbon::parse(
                                $item->tanggal_mulai
                            )->translatedFormat('d F Y')
                            : '-'
                        }}

                    </td>



                    {{-- TANGGAL SELESAI --}}

                    <td class="text-center">

                        {{ $item->tanggal_selesai
                            ? \Carbon\Carbon::parse(
                                $item->tanggal_selesai
                            )->translatedFormat('d F Y')
                            : '-'
                        }}

                    </td>



                    {{-- STATUS --}}

                    <td class="text-center">


                        @if($item->status === 'Aktif')

                            <span class="status-aktif">

                                Aktif

                            </span>


                        @else

                            <span class="status-nonaktif">

                                Tidak Aktif

                            </span>

                        @endif


                    </td>


                </tr>


            @empty


                <tr>

                    <td
                        colspan="7"
                        class="text-center"
                    >

                        Tidak ada data promo.

                    </td>

                </tr>


            @endforelse


        </tbody>

    </table>

</div>



{{-- ========================================================= --}}
{{-- KESIMPULAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Kesimpulan Laporan

    </div>


    Berdasarkan data yang tersedia, Azzahra Salon dan Spa Muslimah
    memiliki


    <b>

        {{ number_format($totalPromo) }}

    </b>


    promo yang tercatat dalam sistem.


    <br><br>


    Promo yang berstatus <b>Aktif</b> sebanyak


    <b>

        {{ number_format($jumlahAktif) }}

    </b>


    promo atau sebesar


    <b>

        {{ $persenAktif }}%

    </b>


    dari total promo.


    <br><br>


    Sedangkan promo yang berstatus <b>Tidak Aktif</b> sebanyak


    <b>

        {{ number_format($jumlahTidakAktif) }}

    </b>


    promo atau sebesar


    <b>

        {{ $persenTidakAktif }}%

    </b>


    dari total promo.


    <br><br>


    Data promo tersebut dapat digunakan sebagai salah satu
    dasar dalam pengelolaan program promosi, pemberian potongan
    harga, serta mendukung strategi pemasaran dan peningkatan
    pelayanan kepada pelanggan Azzahra Salon dan Spa Muslimah.


    @if(isset($periode))

        <br><br>


        Laporan ini dibuat untuk periode


        <b>

            {{ $periode }}

        </b>


        berdasarkan data promo yang tercatat dalam sistem.

    @endif

</div>



{{-- ========================================================= --}}
{{-- TANDA TANGAN --}}
{{-- ========================================================= --}}

<div class="footer">

    <div class="ttd">


        Yogyakarta,


        {{ \Carbon\Carbon::parse($tanggalLaporan)->translatedFormat('d F Y') }}


        <div class="signature-line"></div>


        <strong>

            Pengelola Azzahra Salon

        </strong>


    </div>

</div>



{{-- ========================================================= --}}
{{-- NOMOR HALAMAN --}}
{{-- ========================================================= --}}

<script type="text/php">

    if (isset($pdf)) {

        $pdf->page_text(
            520,
            820,
            "Halaman {PAGE_NUM} / {PAGE_COUNT}",
            null,
            10
        );

    }

</script>


</body>

</html>