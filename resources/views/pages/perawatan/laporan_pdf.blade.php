```html
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Laporan Data Perawatan</title>

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
    | DATA PERAWATAN
    |--------------------------------------------------------------------------
    */

    $totalPerawatan = $perawatan->count();


    /*
    |--------------------------------------------------------------------------
    | RATA-RATA DURASI
    |--------------------------------------------------------------------------
    */

    $rataDurasi = $totalPerawatan > 0
        ? $perawatan->avg('durasi')
        : 0;


    /*
    |--------------------------------------------------------------------------
    | RATA-RATA HARGA
    |--------------------------------------------------------------------------
    */

    $rataHarga = $totalPerawatan > 0
        ? $perawatan->avg('harga')
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

        LAPORAN DATA PERAWATAN

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

    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}

</div>



{{-- ========================================================= --}}
{{-- RINGKASAN PERAWATAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Ringkasan Perawatan

    </div>


    <table class="summary-grid">

        <tr>


            {{-- TOTAL PERAWATAN --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Total Perawatan

                    </div>


                    <div class="kpi-value">

                        {{ number_format($totalPerawatan) }}

                    </div>

                </div>

            </td>



            {{-- RATA-RATA DURASI --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Rata-rata Durasi

                    </div>


                    <div class="kpi-value">

                        {{ number_format($rataDurasi, 0, ',', '.') }}

                        menit

                    </div>

                </div>

            </td>



            {{-- RATA-RATA HARGA --}}

            <td>

                <div class="kpi-box">

                    <div class="kpi-title">

                        Rata-rata Harga

                    </div>


                    <div class="kpi-value">

                        Rp {{ number_format($rataHarga, 0, ',', '.') }}

                    </div>

                </div>

            </td>


        </tr>

    </table>

</div>



{{-- ========================================================= --}}
{{-- INFORMASI PERAWATAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Informasi Perawatan

    </div>


    <table class="data">

        <thead>

            <tr>

                <th>

                    Keterangan

                </th>


                <th>

                    Nilai

                </th>


                <th>

                    Satuan

                </th>

            </tr>

        </thead>


        <tbody>


            <tr>

                <td>

                    Total Perawatan

                </td>


                <td class="text-center">

                    {{ number_format($totalPerawatan) }}

                </td>


                <td class="text-center">

                    Layanan

                </td>

            </tr>



            <tr>

                <td>

                    Rata-rata Durasi Perawatan

                </td>


                <td class="text-center">

                    {{ number_format($rataDurasi, 0, ',', '.') }}

                </td>


                <td class="text-center">

                    Menit

                </td>

            </tr>



            <tr>

                <td>

                    Rata-rata Harga Perawatan

                </td>


                <td class="text-center">

                    Rp {{ number_format($rataHarga, 0, ',', '.') }}

                </td>


                <td class="text-center">

                    Rupiah

                </td>

            </tr>


        </tbody>

    </table>

</div>



{{-- ========================================================= --}}
{{-- DATA PERAWATAN --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-title">

        Data Perawatan

    </div>


    <table class="data">

        <thead>

            <tr>


                <th width="6%">

                    No

                </th>


                <th width="25%">

                    Nama Perawatan

                </th>


                <th width="12%">

                    Durasi

                </th>


                <th width="18%">

                    Harga

                </th>


                <th width="39%">

                    Deskripsi

                </th>


            </tr>

        </thead>


        <tbody>


            @forelse($perawatan as $i => $item)

                <tr>


                    {{-- NOMOR --}}

                    <td class="text-center">

                        {{ $i + 1 }}

                    </td>



                    {{-- NAMA --}}

                    <td>

                        {{ $item->nama }}

                    </td>



                    {{-- DURASI --}}

                    <td class="text-center">

                        {{ $item->durasi }}

                        menit

                    </td>



                    {{-- HARGA --}}

                    <td class="text-right">

                        Rp

                        {{ number_format(
                            $item->harga,
                            0,
                            ',',
                            '.'
                        ) }}

                    </td>



                    {{-- DESKRIPSI --}}

                    <td>

                        {{ $item->deskripsi ?: '-' }}

                    </td>


                </tr>


            @empty


                <tr>

                    <td
                        colspan="5"
                        class="text-center"
                    >

                        Tidak ada data perawatan.

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

        {{ number_format($totalPerawatan) }}

    </b>


    jenis layanan perawatan yang tercatat dalam sistem.


    <br><br>


    Rata-rata durasi layanan perawatan adalah


    <b>

        {{ number_format($rataDurasi, 0, ',', '.') }}

        menit

    </b>


    dengan rata-rata harga sebesar


    <b>

        Rp {{ number_format($rataHarga, 0, ',', '.') }}

    </b>


    per layanan.


    <br><br>


    Data perawatan tersebut dapat digunakan sebagai salah satu
    dasar dalam pengelolaan layanan salon, penentuan harga,
    pengaturan durasi pelayanan, serta mendukung proses
    reservasi pada sistem Azzahra Salon dan Spa Muslimah.

</div>



{{-- ========================================================= --}}
{{-- TANDA TANGAN --}}
{{-- ========================================================= --}}

<div class="footer">

    <div class="ttd">


        Yogyakarta,


        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}


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