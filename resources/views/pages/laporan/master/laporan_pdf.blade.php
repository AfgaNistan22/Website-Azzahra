<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Master</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #3a2a26; }
        h2 { margin: 0 0 2px; color: #7c1013; font-size: 16px; }
        .sub { color: #7a6a62; margin-bottom: 12px; }
        .bagian { margin-bottom: 18px; }
        .bagian h3 { margin: 0 0 4px; padding: 6px 8px; background: #fdf3e2; color: #7c1013; font-size: 12px; }
        .meta { color: #8a6a2f; margin-bottom: 6px; padding: 0 2px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #fdf6ea; color: #8a6a2f; text-align: left; font-size: 8.5px; text-transform: uppercase; padding: 6px; border-bottom: 1.5px solid #d9ac4f; }
        td { padding: 5px 6px; border-bottom: 1px solid #ecd9bf; vertical-align: top; }
        tr:nth-child(even) td { background: #fffdf9; }
        .r { text-align: right; white-space: nowrap; }
        .total td { font-weight: bold; border-top: 1.5px solid #7c1013; background: #fff0f0; }
        .note { margin-top: 6px; color: #a9781f; font-size: 9px; }
        .pagebreak { page-break-after: always; }
    </style>
</head>
<body>

    <h2>Laporan Master</h2>
    <div class="sub">Azzahra Salon dan Spa Muslimah &middot; Dicetak {{ now()->format('d/m/Y H:i') }}</div>

    @forelse ($bagian as $b)

        @php
            $l = $b['laporan'];
            $periode = ($l->tanggal_mulai || $l->tanggal_selesai)
                ? ($l->tanggal_mulai?->format('d/m/Y') ?? '...') . ' - ' . ($l->tanggal_selesai?->format('d/m/Y') ?? 'sekarang')
                : 'Semua data';
        @endphp

        <div class="bagian {{ !$loop->last ? 'pagebreak' : '' }}">

            <h3>{{ $b['kat']['label'] ?? \Illuminate\Support\Str::headline($l->kategori) }} &middot; {{ $l->kode_laporan }}</h3>

            <div class="meta">
                Periode: {{ $periode }} &middot; Status: {{ $l->status }}
                @if ($b['kat'])
                    &middot; {{ number_format($b['totalData'], 0, ',', '.') }} data
                @endif
                @if ($l->catatan)
                    <br>Catatan: {{ $l->catatan }}
                @endif
            </div>

            @if (!$b['kat'])

                <div class="note">Kategori tidak lagi tersedia, data tidak dapat ditampilkan.</div>

            @else

                <table>
                    <thead>
                        <tr>
                            <th style="width:28px;">No</th>
                            @foreach ($b['kolom'] as $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($b['baris'] as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @foreach ($row as $sel)
                                    <td class="{{ ($sel['k'] ?? '') === 'money' ? 'r' : '' }}">{{ $sel['t'] }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($b['kolom']) + 1 }}" style="text-align:center;color:#a09a94;">Tidak ada data.</td>
                            </tr>
                        @endforelse

                        @if (!empty($b['totalKolom']))
                            <tr class="total">
                                <td>Total</td>
                                @foreach (array_keys($b['kolom']) as $key)
                                    <td class="r">{{ $b['totalKolom'][$key] ?? '' }}</td>
                                @endforeach
                            </tr>
                        @endif
                    </tbody>
                </table>

                @if ($b['terpotong'])
                    <div class="note">
                        PDF hanya memuat {{ number_format($maksBaris, 0, ',', '.') }} baris pertama dari
                        {{ number_format($b['totalData'], 0, ',', '.') }} data. Baris total tetap dihitung dari seluruh data.
                    </div>
                @endif

            @endif

        </div>

    @empty
        <p>Tidak ada laporan master.</p>
    @endforelse

    @if ($laporanTerpotong)
        <div class="note">
            Hanya {{ $maksLaporan }} laporan pertama yang dimuat dari {{ $jumlahTerfilter }} laporan terfilter.
            Persempit dengan filter untuk melihat sisanya.
        </div>
    @endif

</body>
</html>