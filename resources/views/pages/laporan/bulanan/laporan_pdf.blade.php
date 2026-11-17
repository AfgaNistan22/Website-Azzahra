<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan Keuangan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #3a2a26; }
        h2 { margin: 0 0 2px; color: #7c1013; }
        .sub { color: #7a6a62; margin-bottom: 14px; }
        .bulan { margin-bottom: 18px; page-break-inside: avoid; }
        .bulan h3 { margin: 0 0 6px; padding: 6px 8px; background: #fdf3e2; color: #7c1013; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #fdf6ea; color: #8a6a2f; text-align: left; font-size: 9.5px; text-transform: uppercase; }
        th, td { padding: 5px 7px; border-bottom: 1px solid #ecd9bf; }
        .r { text-align: right; }
        .ringkas td { border: none; padding: 2px 7px; }
        .total td { font-weight: bold; border-top: 1.5px solid #7c1013; background: #fffbf3; }
        .neg { color: #b91316; }
        .pos { color: #12865d; }
    </style>
</head>
<body>

    <h2>Laporan Bulanan Keuangan</h2>
    <div class="sub">Azzahra Salon dan Spa Muslimah &middot; Dicetak {{ now()->format('d/m/Y H:i') }}</div>

    @forelse ($bulan as $b)
        <div class="bulan">

            <h3>{{ $b['periode']->locale('id')->translatedFormat('F Y') }}</h3>

            <table class="ringkas">
                <tr><td>Saldo Awal</td>
                    <td class="r">Rp {{ number_format($b['saldo_awal'], 0, ',', '.') }}</td></tr>
                <tr><td>Total Pemasukan ({{ $b['jumlah_pemasukan'] }} transaksi)</td>
                    <td class="r pos">Rp {{ number_format($b['total_pemasukan'], 0, ',', '.') }}</td></tr>
                <tr><td>Total Pengeluaran ({{ $b['jumlah_pengeluaran'] }} transaksi)</td>
                    <td class="r neg">Rp {{ number_format($b['total_pengeluaran'], 0, ',', '.') }}</td></tr>
                <tr><td><strong>Saldo Akhir</strong></td>
                    <td class="r"><strong>Rp {{ number_format($b['saldo_akhir'], 0, ',', '.') }}</strong></td></tr>
            </table>

            <table style="margin-top:6px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="r">Pemasukan</th>
                        <th class="r">Pengeluaran</th>
                        <th class="r">Laba</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($b['hari'] as $h)
                        <tr>
                            <td>{{ $h['tanggal']->format('d/m/Y') }}</td>
                            <td class="r">Rp {{ number_format($h['total_pemasukan'], 0, ',', '.') }}</td>
                            <td class="r">Rp {{ number_format($h['total_pengeluaran'], 0, ',', '.') }}</td>
                            <td class="r {{ $h['laba_bersih'] < 0 ? 'neg' : '' }}">
                                Rp {{ number_format($h['laba_bersih'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#a09a94;">Tidak ada transaksi diterima.</td></tr>
                    @endforelse

                    <tr class="total">
                        <td>Total Bulan</td>
                        <td class="r">Rp {{ number_format($b['total_pemasukan'], 0, ',', '.') }}</td>
                        <td class="r">Rp {{ number_format($b['total_pengeluaran'], 0, ',', '.') }}</td>
                        <td class="r {{ $b['laba_bersih'] < 0 ? 'neg' : '' }}">
                            Rp {{ number_format($b['laba_bersih'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    @empty
        <p>Tidak ada data laporan bulanan.</p>
    @endforelse

</body>
</html>