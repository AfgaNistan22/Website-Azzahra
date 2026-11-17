<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pelanggan</title>

    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #2c3e50; margin-top: 90px; }

        /* ================= WATERMARK ================= */
        .watermark { position: fixed; top: 25%; left: 15%; width: 70%; opacity: 0.05; z-index: -1; }

        /* ================= HEADER ================= */
        .header { position: fixed; top: 0; left: 0; right: 0; border-bottom: 3px solid #dc3545; padding-bottom: 12px; }
        .salon-name { font-size: 18px; font-weight: bold; color: #dc3545; }
        .salon-info { font-size: 11px; }

        /* ================= TITLE ================= */
        .title { text-align: center; margin: 15px 0; }
        .title h3 { margin: 0; }

        /* ================= META ================= */
        .meta { margin-bottom: 10px; }

        /* ================= CARD ================= */
        .card { border: 1px solid #f1b0b7; border-left: 4px solid #dc3545; padding: 10px; margin-top: 20px; margin-bottom: 20px; }
        .card-title { font-weight: bold; color: #dc3545; margin-bottom: 8px; }

        /* ================= TABLE ================= */
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        table.data th { background: #dc3545; color: white; padding: 6px; font-size: 11px; }
        table.data td { border: 1px solid #eee; padding: 6px; vertical-align: top; }
        table.data tr:nth-child(even) { background: #fff5f5; }
        table.kecil th, table.kecil td { font-size: 9px; padding: 5px 4px; }
        tr.total-row td { background: #fff0f0; font-weight: bold; color: #dc3545; }

        /* ================= SUMMARY ================= */
        .summary-grid { width: 100%; border-collapse: collapse; }
        .summary-grid td { width: 50%; padding: 8px; vertical-align: top; }
        .kpi-box { border: 1px solid #f1b0b7; border-left: 4px solid #dc3545; background: #fff8f8; padding: 10px; }
        .kpi-title { color: #dc3545; font-weight: bold; margin-bottom: 5px; }
        .kpi-value { font-size: 16px; font-weight: bold; }
        .kpi-value.sm { font-size: 14px; }

        /* ================= TEXT / STATUS ================= */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-hijau { font-weight: bold; color: #198754; }
        .status-kuning { font-weight: bold; color: #b7791f; }
        .status-merah { font-weight: bold; color: #dc3545; }
        .qty { font-size: 9px; color: #666; }
        .treatment-item { margin-bottom: 3px; }

        /* ================= BUKU KAS (LAPORAN HARIAN) ================= */
        table.lh { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .blok { page-break-inside: avoid; margin-top: -1px; }
        table.lh td { border: 1px solid #dc3545; padding: 4px 6px; vertical-align: middle; font-size: 10px; }
        table.lh tr.kolom td { background: #dc3545; color: #fff; font-weight: bold; text-align: center; font-size: 11px; }
        table.lh td.tgl { text-align: center; background: #fff8f8; }
        table.lh td.label-seksi { font-weight: bold; color: #dc3545; background: #fff5f5; }
        table.lh tr.baris-total td { background: #fff0f0; font-weight: bold; color: #dc3545; }

        /* ================= FOOTER ================= */
        .footer { margin-top: 40px; }
        .ttd { float: right; width: 200px; text-align: center; }
        .signature-line { margin-top: 50px; border-top: 1px solid #000; }
    </style>
</head>

<body>

@php
    $logoPath = public_path('template/img/logoicon.png');

    $logo = file_exists($logoPath)
        ? base64_encode(file_get_contents($logoPath))
        : null;

    $no_laporan = $no_laporan ?? ('LAP-' . date('Ymd-His'));

    $tglLaporan = \Carbon\Carbon::parse($tanggal ?? $tanggalLaporan ?? now())
        ->translatedFormat('d F Y');

    $jumlahMember = $pelanggan->where('member', 'Ya')->count();
    $jumlahBukanMember = $pelanggan->where('member', 'Tidak')->count();
    $totalPelanggan = $pelanggan->count();

    $persenMember = $totalPelanggan > 0 ? round(($jumlahMember / $totalPelanggan) * 100, 2) : 0;
    $persenBukanMember = $totalPelanggan > 0 ? round(($jumlahBukanMember / $totalPelanggan) * 100, 2) : 0;
@endphp

{{-- WATERMARK --}}
@if($logo)
    <img src="data:image/png;base64,{{ $logo }}" class="watermark">
@endif

{{-- HEADER --}}
<div class="header">
    <table width="100%">
        <tr>
            <td width="70">
                @if($logo)
                    <img src="data:image/png;base64,{{ $logo }}" width="60">
                @endif
            </td>

            <td class="text-center">
                <div class="salon-name">AZZAHRA SALON DAN SPA MUSLIMAH</div>

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

            <td width="70" class="text-right">
                @if($logo)
                    <img src="data:image/png;base64,{{ $logo }}" width="60">
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- TITLE --}}
<div class="title">
    <h3>LAPORAN DATA PELANGGAN</h3>
</div>

{{-- META --}}
<div class="meta">
    No Laporan :
    <b>{{ $no_laporan }}</b>

    <br>

    Tanggal :
    {{ $tglLaporan }}

    @if(isset($periode))
        <br>
        Periode :
        <b>{{ $periode }}</b>
    @endif
</div>

{{-- RINGKASAN --}}
<div class="card">
    <div class="card-title">Ringkasan Pelanggan</div>

    <table class="summary-grid">
        <tr>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Total Pelanggan</div>
                    <div class="kpi-value">{{ number_format($totalPelanggan) }}</div>
                </div>
            </td>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Pelanggan Member</div>
                    <div class="kpi-value">{{ number_format($jumlahMember) }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Pelanggan Bukan Member</div>
                    <div class="kpi-value">{{ number_format($jumlahBukanMember) }}</div>
                </div>
            </td>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Persentase Member</div>
                    <div class="kpi-value">{{ $persenMember }}%</div>
                </div>
            </td>
        </tr>
    </table>
</div>
{{-- DISTRIBUSI --}}
<div class="card">
    <div class="card-title">Distribusi Status Member</div>

    <table class="data">
        <thead>
            <tr><th>Status Pelanggan</th><th>Jumlah</th><th>Persentase</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Member</td>
                <td class="text-center">{{ number_format($jumlahMember) }}</td>
                <td class="text-center">{{ $persenMember }}%</td>
            </tr>
            <tr>
                <td>Bukan Member</td>
                <td class="text-center">{{ number_format($jumlahBukanMember) }}</td>
                <td class="text-center">{{ $persenBukanMember }}%</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- DISTRIBUSI UMUR --}}
@if(isset($umurGroup))
<div class="card">
    <div class="card-title">Distribusi Umur Pelanggan</div>

    <table class="data">
        <thead>
            <tr><th>Kelompok Umur</th><th>Jumlah Pelanggan</th></tr>
        </thead>
        <tbody>
            @foreach($umurGroup as $k => $v)
                <tr>
                    <td>{{ $k }}</td>
                    <td class="text-center">{{ $v }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- DATA PELANGGAN --}}
<div class="card">
    <div class="card-title">Data Pelanggan</div>

    <table class="data kecil">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Status Member</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pelanggan as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="text-center">{{ $p->member === 'Ya' ? 'Member' : 'Bukan Member' }}</td>
                    <td class="text-center">
                        {{ $p->tgl_lahir ? \Carbon\Carbon::parse($p->tgl_lahir)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $p->tgl_lahir ? \Carbon\Carbon::parse($p->tgl_lahir)->age : '-' }}
                    </td>
                    <td>{{ $p->no_telepon ?? '-' }}</td>
                    <td>{{ $p->email ?? '-' }}</td>
                    <td>{{ $p->alamat ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pelanggan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- KESIMPULAN --}}
<div class="card">
    <div class="card-title">Kesimpulan Laporan</div>

    Berdasarkan data yang tersedia, Azzahra Salon dan Spa Muslimah
    memiliki <b>{{ number_format($totalPelanggan) }}</b> pelanggan.

    <br><br>

    Pelanggan yang berstatus <b>Member</b> sebanyak <b>{{ number_format($jumlahMember) }}</b>
    orang atau sebesar <b>{{ $persenMember }}%</b> dari total pelanggan.

    <br><br>

    Sedangkan pelanggan yang berstatus <b>Bukan Member</b> sebanyak
    <b>{{ number_format($jumlahBukanMember) }}</b> orang atau sebesar
    <b>{{ $persenBukanMember }}%</b> dari total pelanggan.

    <br><br>

    Data pelanggan tersebut dapat digunakan sebagai salah satu dasar
    pengelolaan data pelanggan dan proses reservasi layanan pada sistem
    Azzahra Salon dan Spa Muslimah.
</div>

{{-- TANDA TANGAN --}}
<div class="footer">
    <div class="ttd">
        Yogyakarta,
        {{ $tglLaporan }}

        <div class="signature-line"></div>

        <strong>Pengelola Azzahra Salon</strong>
    </div>
</div>

{{-- NOMOR HALAMAN --}}
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(520, 820, "Halaman {PAGE_NUM} / {PAGE_COUNT}", null, 10);
    }
</script>

</body>

</html>