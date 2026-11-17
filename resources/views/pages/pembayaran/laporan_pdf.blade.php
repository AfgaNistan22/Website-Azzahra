<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>

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

    $alamat_salon = $alamat_salon ?? 'Jl. Nyai Ahmad Dahlan No. 63, Yogyakarta 55122';

    $totalData = $data->count();
    $jumlahLunas = $data->where('status', 'Lunas')->count();
    $jumlahMenunggu = $data->where('status', 'Menunggu')->count();
    $jumlahBatal = $data->where('status', 'Dibatalkan')->count();
    $totalLunas = $data->where('status', 'Lunas')->sum('jumlah');
    $totalSemua = $data->sum('jumlah');
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
    <h3>LAPORAN PEMBAYARAN</h3>
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
    <div class="card-title">Ringkasan Pembayaran</div>

    <table class="summary-grid">
        <tr>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Jumlah Data</div>
                    <div class="kpi-value">{{ number_format($totalData) }}</div>
                </div>
            </td>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Status Lunas</div>
                    <div class="kpi-value">{{ number_format($jumlahLunas) }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Status Menunggu</div>
                    <div class="kpi-value">{{ number_format($jumlahMenunggu) }}</div>
                </div>
            </td>
            <td>
                <div class="kpi-box">
                    <div class="kpi-title">Status Dibatalkan</div>
                    <div class="kpi-value">{{ number_format($jumlahBatal) }}</div>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- DATA PEMBAYARAN --}}
<div class="card">
    <div class="card-title">Data Pembayaran</div>

    <table class="data kecil">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="11%">Kode Reservasi</th>
                <th width="13%">Pelanggan</th>
                <th width="20%">Perawatan</th>
                <th width="12%">Pegawai</th>
                <th width="9%">Tgl Bayar</th>
                <th width="9%">Metode</th>
                <th width="11%">Jumlah</th>
                <th width="9%">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
                @php
                    $reservasi = $item->reservasi;

                    // Prioritas relasi perawatans (multi perawatan); jika kosong pakai perawatan lama.
                    $perawatans = $reservasi?->perawatans;

                    if (!$perawatans || $perawatans->isEmpty()) {
                        $perawatans = collect();

                        if ($reservasi?->perawatan) {
                            $perawatans->push($reservasi->perawatan);
                        }
                    }
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $reservasi?->kode_reservasi ?? '-' }}</td>
                    <td>{{ $reservasi?->pelanggan?->nama ?? '-' }}</td>
                    <td>
                        @forelse($perawatans as $perawatan)
                            @php $jumlah = $perawatan->pivot->jumlah ?? 1; @endphp
                            <div class="treatment-item">
                                {{ $perawatan->nama }}
                                @if($jumlah > 1)
                                    <span class="qty">({{ $jumlah }}x)</span>
                                @endif
                            </div>
                        @empty
                            -
                        @endforelse
                    </td>
                    <td>{{ $reservasi?->pegawai?->nama ?? '-' }}</td>
                    <td class="text-center">
                        {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="text-center">{{ $item->metode_pembayaran ?: '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item->status === 'Lunas')
                            <span class="status-hijau">Lunas</span>
                        @elseif($item->status === 'Menunggu')
                            <span class="status-kuning">Menunggu</span>
                        @elseif($item->status === 'Dibatalkan')
                            <span class="status-merah">Dibatalkan</span>
                        @else
                            {{ $item->status ?? '-' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data pembayaran.</td>
                </tr>
            @endforelse

            @if($data->count())
                <tr class="total-row">
                    <td colspan="7" class="text-right">TOTAL PEMBAYARAN LUNAS</td>
                    <td class="text-right">Rp {{ number_format($totalLunas, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="total-row">
                    <td colspan="7" class="text-right">TOTAL SELURUH PEMBAYARAN</td>
                    <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{-- KESIMPULAN --}}
<div class="card">
    <div class="card-title">Kesimpulan Laporan</div>

    Berdasarkan data yang tersedia, tercatat <b>{{ number_format($totalData) }}</b>
    pembayaran, terdiri dari <b>{{ number_format($jumlahLunas) }}</b> lunas,
    <b>{{ number_format($jumlahMenunggu) }}</b> menunggu, dan
    <b>{{ number_format($jumlahBatal) }}</b> dibatalkan.

    <br><br>

    Total pembayaran yang telah lunas sebesar
    <b>Rp {{ number_format($totalLunas, 0, ',', '.') }}</b>, sedangkan total seluruh
    pembayaran sebesar <b>Rp {{ number_format($totalSemua, 0, ',', '.') }}</b>.
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