@extends('pages.auth.guest')

@section('title', 'Jadwal Terapis | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman jadwal --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/jadwal-modern.css') }}?v={{ file_exists(public_path('template/css/jadwal-modern.css')) ? filemtime(public_path('template/css/jadwal-modern.css')) : time() }}">
@endpush

@section('content')

@php
    use Illuminate\Support\Carbon;

    // Kata kunci kosong dibuang dari URL supaya tidak muncul "?cari="
    $cari = $pencarian ?: null;

    // Satu kali pengelompokan per tanggal. Sebelumnya setiap sel kalender
    // menyaring seluruh reservasi dan mem-parse tanggalnya berulang kali.
    $perTanggal = $reservasi->groupBy(
        fn ($item) => Carbon::parse($item->tanggal)->toDateString()
    );

    // Satu penentu kelas status untuk kalender dan tabel
    $kelasStatus = function (?string $status): string {
        $s = strtolower($status ?? '');

        return str_contains($s, 'selesai')
            ? 'completed'
            : (str_contains($s, 'menunggu') ? 'pending' : '');
    };

    $tanggalDipilih = request('tanggal');

    $detailReservasi = $tanggalDipilih
        ? $perTanggal->get($tanggalDipilih, collect())
        : $reservasi;

    $hariIni = now()->toDateString();
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    AZZAHRA SALON &amp; SPA MUSLIMAH
                </span>

                <h1>
                    Jadwal
                    <em>Terapis.</em>
                </h1>

                <p>
                    Pantau reservasi dan jadwal perawatan dalam satu
                    kalender bulanan yang terorganisasi.
                </p>

                <a href="{{ route('jadwal-terapis.index', [
                        'bulan' => now()->month,
                        'tahun' => now()->year
                    ]) }}"
                   class="bkl-btn bkl-btn-light">
                    <i class="fas fa-calendar-day" aria-hidden="true"></i>
                    Bulan ini
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="far fa-calendar-alt"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Jadwal • Terapis • Reservasi</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        {{-- STATISTIK --}}
        <div class="jt-stats bkl-reveal">
            <div class="jt-stat">
                <div class="jt-stat-label">Total reservasi</div>
                <div class="jt-stat-value" data-count="{{ $totalJadwal }}">{{ $totalJadwal }}</div>
            </div>

            <div class="jt-stat is-confirmed">
                <div class="jt-stat-label">Dikonfirmasi</div>
                <div class="jt-stat-value" data-count="{{ $totalDikonfirmasi }}">{{ $totalDikonfirmasi }}</div>
            </div>

            <div class="jt-stat is-waiting">
                <div class="jt-stat-label">Menunggu</div>
                <div class="jt-stat-value" data-count="{{ $totalMenunggu }}">{{ $totalMenunggu }}</div>
            </div>
        </div>

        {{-- FILTER DAN PENCARIAN --}}
        <form action="{{ route('jadwal-terapis.index') }}"
              method="GET"
              class="jt-filter bkl-reveal">

            <div class="jt-search-field">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input type="search"
                       name="cari"
                       class="jt-input"
                       value="{{ $pencarian }}"
                       placeholder="Cari terapis, pelanggan, perawatan"
                       aria-label="Cari jadwal">
            </div>

            <select name="bulan" class="jt-input" aria-label="Pilih bulan">
                @foreach (range(1, 12) as $nomorBulan)
                    <option value="{{ $nomorBulan }}"
                        @selected((int) $bulan === $nomorBulan)>
                        {{-- Carbon::create(null, bulan, 1) menghindari bug tanggal 29-31 yang
                             membuat nama bulan meloncat (misal 31 Jan + bulan 2 menjadi Maret). --}}
                        {{ Carbon::create(null, $nomorBulan, 1)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="jt-input" aria-label="Pilih tahun">
                @foreach (range(now()->year - 2, now()->year + 3) as $nomorTahun)
                    <option value="{{ $nomorTahun }}"
                        @selected((int) $tahun === $nomorTahun)>
                        {{ $nomorTahun }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="jt-search-btn">
                Cari
            </button>

            <a href="{{ route('jadwal-terapis.index', [
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ]) }}"
               class="jt-reset-btn">
                Reset
            </a>
        </form>

        {{-- KALENDER BULANAN --}}
        <section class="jt-calendar bkl-reveal" aria-label="Kalender reservasi {{ $awalBulan->translatedFormat('F Y') }}">
            <div class="jt-calendar-head">
                <div>
                    <h2>{{ $awalBulan->translatedFormat('F Y') }}</h2>
                    <p>
                        <span>{{ $totalJadwal }} reservasi pada bulan ini</span>

                        @if ($pencarian !== '' && $pencarian !== null)
                            <a class="jt-search-chip"
                               title="Hapus pencarian"
                               href="{{ route('jadwal-terapis.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}">
                                Pencarian: “{{ $pencarian }}”
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </p>
                </div>

                <div class="jt-month-nav">
                    <a class="jt-month-btn"
                       title="Bulan sebelumnya"
                       aria-label="Bulan sebelumnya"
                       href="{{ route('jadwal-terapis.index', [
                           'bulan' => $bulanSebelumnya->month,
                           'tahun' => $bulanSebelumnya->year,
                           'cari'  => $cari
                       ]) }}">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </a>

                    <a class="jt-month-btn"
                       title="Bulan berikutnya"
                       aria-label="Bulan berikutnya"
                       href="{{ route('jadwal-terapis.index', [
                           'bulan' => $bulanBerikutnya->month,
                           'tahun' => $bulanBerikutnya->year,
                           'cari'  => $cari
                       ]) }}">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="jt-calendar-wrap">
                <div class="jt-calendar-grid">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $hari)
                        <div class="jt-weekday">{{ $hari }}</div>
                    @endforeach

                    @foreach ($kalender as $minggu)
                        @foreach ($minggu as $nomorHari)
                            @php
                                // urutan sel, dipakai untuk efek sapuan animasi
                                $urutan = $loop->parent->index * 7 + $loop->index;

                                $tanggalCell = $nomorHari
                                    ? $awalBulan->copy()->day($nomorHari)->toDateString()
                                    : null;

                                $reservasiHari = $tanggalCell
                                    ? $perTanggal->get($tanggalCell, collect())->sortBy('jam_mulai')->values()
                                    : collect();

                                $isToday    = $tanggalCell === $hariIni;
                                $isSelected = $tanggalCell && $tanggalDipilih === $tanggalCell;
                            @endphp

                            @if ($nomorHari)
                                <div class="jt-day {{ $isToday ? 'is-today' : '' }} {{ $isSelected ? 'is-selected' : '' }}"
                                     style="--n: {{ $urutan }}">

                                    {{-- Satu tautan per hari (sebelumnya satu tautan per reservasi) --}}
                                    <a class="jt-day-link"
                                       @if ($isToday) aria-current="date" @endif
                                       aria-label="{{ $nomorHari }} {{ $awalBulan->translatedFormat('F') }}, {{ $reservasiHari->count() }} reservasi"
                                       href="{{ route('jadwal-terapis.index', [
                                           'bulan'   => $bulan,
                                           'tahun'   => $tahun,
                                           'cari'    => $cari,
                                           'tanggal' => $tanggalCell
                                       ]) }}#jt-detail">

                                        <span class="jt-day-number">{{ $nomorHari }}</span>

                                        @foreach ($reservasiHari->take(3) as $item)
                                            <span class="jt-event {{ $kelasStatus($item->status) }}">
                                                <b>{{ substr($item->jam_mulai ?? '', 0, 5) }}</b>{{ $item->pegawai->nama ?? 'Terapis' }}
                                            </span>
                                        @endforeach

                                        @if ($reservasiHari->count() > 3)
                                            <span class="jt-more">
                                                +{{ $reservasiHari->count() - 3 }} lainnya
                                            </span>
                                        @endif
                                    </a>
                                </div>
                            @else
                                <div class="jt-day is-empty" style="--n: {{ $urutan }}" aria-hidden="true"></div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="jt-calendar-legend">
                <span class="jt-legend-item">
                    <span class="jt-legend-dot"></span>
                    Dikonfirmasi / lainnya
                </span>
                <span class="jt-legend-item">
                    <span class="jt-legend-dot pending"></span>
                    Menunggu
                </span>
                <span class="jt-legend-item">
                    <span class="jt-legend-dot completed"></span>
                    Selesai
                </span>
            </div>
        </section>

        {{-- DETAIL RESERVASI --}}
        <section class="jt-detail bkl-reveal" id="jt-detail">
            <div class="jt-detail-head">
                <div>
                    <h3>
                        {{ $tanggalDipilih
                            ? Carbon::parse($tanggalDipilih)->translatedFormat('l, d F Y')
                            : 'Daftar reservasi bulan ini' }}
                    </h3>
                    <p>Rincian reservasi dan terapis yang bertugas</p>
                </div>

                <div class="jt-detail-tools">
                    @if ($tanggalDipilih)
                        <a class="jt-link-btn"
                           href="{{ route('jadwal-terapis.index', [
                               'bulan' => $bulan,
                               'tahun' => $tahun,
                               'cari'  => $cari
                           ]) }}#jt-detail">
                            Lihat seluruh bulan
                        </a>
                    @endif

                    <span class="jt-detail-count">
                        {{ $detailReservasi->count() }} reservasi
                    </span>
                </div>
            </div>

            @if ($detailReservasi->isNotEmpty())
                <div class="jt-table-wrap">
                    <table class="jt-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Terapis</th>
                                <th>Pelanggan</th>
                                <th>Perawatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($detailReservasi->sortBy('jam_mulai') as $item)
                                <tr style="--r: {{ min($loop->index, 10) }}">
                                    <td data-label="Waktu">
                                        <span class="jt-time">
                                            {{ substr($item->jam_mulai ?? '', 0, 5) }}
                                        </span>
                                        <span class="jt-secondary">
                                            s.d. {{ substr($item->jam_selesai ?? '', 0, 5) }}
                                        </span>
                                    </td>

                                    <td data-label="Terapis">
                                        <span class="jt-person">
                                            {{ $item->pegawai->nama ?? 'Belum ditentukan' }}
                                        </span>
                                    </td>

                                    <td data-label="Pelanggan">
                                        {{ $item->pelanggan->nama ?? '-' }}
                                    </td>

                                    <td data-label="Perawatan">
                                        {{ $item->perawatans->pluck('nama')->join(', ')
                                            ?: ($item->perawatan->nama ?? '-') }}
                                    </td>

                                    <td data-label="Status">
                                        <span class="jt-status {{ $kelasStatus($item->status) }}">
                                            {{ $item->status ?: 'Belum ditentukan' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="jt-empty">
                    <i class="far fa-calendar-times" aria-hidden="true"></i>
                    <h4>Belum ada reservasi</h4>
                    <p>
                        Tidak ada reservasi yang cocok dengan
                        periode atau pencarian ini.
                    </p>
                </div>
            @endif
        </section>

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, angka berhitung naik, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection