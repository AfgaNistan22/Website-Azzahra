@extends('pages.auth.guest')

@section('title', 'Daftar Booking | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- booking.css tetap dimuat hanya untuk kebutuhan lain di layout; gaya halaman ini ada di bawah --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
@endpush

@section('content')

@php
    $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');

    $statusTerpilih = request('status');
    $daftarStatus   = ['Menunggu', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'];

    $jumlah = method_exists($bookings, 'total') ? $bookings->total() : $bookings->count();

    $totalSemua    = $ringkasan['semua']    ?? $jumlah;
    $countMenunggu = $ringkasan['menunggu'] ?? 0;
    $countProses   = $ringkasan['diproses'] ?? 0;
    $countSelesai  = $ringkasan['selesai']  ?? 0;

    $kelasStatus = function ($status) {
        $s = \Illuminate\Support\Str::lower(trim((string) $status));

        return match (true) {
            str_contains($s, 'menunggu')                              => 'is-wait',
            str_contains($s, 'proses')                                => 'is-process',
            str_contains($s, 'konfirmasi'), str_contains($s, 'diterima') => 'is-confirmed',
            str_contains($s, 'batal'), str_contains($s, 'tolak')      => 'is-cancelled',
            str_contains($s, 'selesai')                               => 'is-complete',
            default                                                   => 'is-neutral',
        };
    };

    $createUrl = \Illuminate\Support\Facades\Route::has('booking.create')
        ? route('booking.create')
        : (\Illuminate\Support\Facades\Route::has('perawatan-layanan.index') ? route('perawatan-layanan.index') : url('/'));

    $adaDetail     = \Illuminate\Support\Facades\Route::has('booking.detail');
    $adaPembayaran = \Illuminate\Support\Facades\Route::has('booking.pembayaran');
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
                    RUANG PERSONAL AZZAHRA
                </span>

                <h1>
                    Setiap momen,
                    <em>lebih istimewa.</em>
                </h1>

                <p>
                    Kelola jadwal perawatan, pantau status booking,
                    dan lanjutkan pembayaran dengan praktis dalam satu tempat.
                </p>

                <a href="{{ $createUrl }}" class="bkl-btn bkl-btn-light">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Buat Booking Baru
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-list-ul"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Beauty • Calm • You</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        {{-- ===================== NOTIFIKASI ===================== --}}
        @if (session('success'))
            <div class="bkl-alert bkl-alert-success" role="status">
                <span class="bkl-alert-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
                <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bkl-alert bkl-alert-error" role="alert">
                <span class="bkl-alert-icon"><i class="fas fa-exclamation"></i></span>
                <span>{{ session('error') }}</span>
                <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif


        {{-- ===================== RINGKASAN ===================== --}}
        <section class="bkl-overview" aria-label="Ringkasan booking">

            <div class="bkl-section-heading bkl-reveal">
                <div>
                    <span class="bkl-kicker">OVERVIEW</span>
                    <h2>Ringkasan Booking</h2>
                </div>

                <span class="bkl-live-label"><span></span> Riwayat Anda</span>
            </div>

            <div class="bkl-stats">

                <article class="bkl-stat bkl-stat-total bkl-reveal" style="--i:0">
                    <div class="bkl-stat-top">
                        <span class="bkl-stat-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="bkl-stat-caption">Semua Booking</span>
                    </div>
                    <div class="bkl-stat-bottom">
                        <strong data-count="{{ $totalSemua }}">{{ $totalSemua }}</strong>
                        <span class="bkl-stat-note">Total reservasi</span>
                    </div>
                    <span class="bkl-stat-watermark"><i class="far fa-calendar-alt"></i></span>
                </article>

                <article class="bkl-stat bkl-stat-wait bkl-reveal" style="--i:1">
                    <div class="bkl-stat-top">
                        <span class="bkl-stat-icon"><i class="fas fa-hourglass-half"></i></span>
                        <span class="bkl-stat-caption">Menunggu</span>
                    </div>
                    <div class="bkl-stat-bottom">
                        <strong data-count="{{ $countMenunggu }}">{{ $countMenunggu }}</strong>
                        <span class="bkl-stat-note">Perlu ditindaklanjuti</span>
                    </div>
                    <span class="bkl-stat-watermark"><i class="far fa-clock"></i></span>
                </article>

                <article class="bkl-stat bkl-stat-process bkl-reveal" style="--i:2">
                    <div class="bkl-stat-top">
                        <span class="bkl-stat-icon"><i class="fas fa-spinner"></i></span>
                        <span class="bkl-stat-caption">Diproses</span>
                    </div>
                    <div class="bkl-stat-bottom">
                        <strong data-count="{{ $countProses }}">{{ $countProses }}</strong>
                        <span class="bkl-stat-note">Dalam penanganan</span>
                    </div>
                    <span class="bkl-stat-watermark"><i class="fas fa-spa"></i></span>
                </article>

                <article class="bkl-stat bkl-stat-done bkl-reveal" style="--i:3">
                    <div class="bkl-stat-top">
                        <span class="bkl-stat-icon"><i class="fas fa-check-double"></i></span>
                        <span class="bkl-stat-caption">Selesai</span>
                    </div>
                    <div class="bkl-stat-bottom">
                        <strong data-count="{{ $countSelesai }}">{{ $countSelesai }}</strong>
                        <span class="bkl-stat-note">Perawatan tuntas</span>
                    </div>
                    <span class="bkl-stat-watermark"><i class="fas fa-check-circle"></i></span>
                </article>

            </div>

        </section>


        {{-- ===================== DAFTAR BOOKING ===================== --}}
        <section class="bkl-bookings-section">

            <div class="bkl-section-heading bkl-bookings-heading bkl-reveal">
                <div>
                    <span class="bkl-kicker">YOUR APPOINTMENTS</span>
                    <h2>Daftar Booking</h2>
                    <p>Semua jadwal perawatan Anda, tersusun rapi.</p>
                </div>

                <div class="bkl-total-pill">
                    <i class="fas fa-layer-group"></i>
                    {{ $jumlah }} Booking
                </div>
            </div>

            {{-- filter status --}}
            <nav class="bkl-filters bkl-reveal" aria-label="Filter status booking">

                <a href="{{ url()->current() }}"
                   class="bkl-filter {{ ! $statusTerpilih ? 'is-active' : '' }}">
                    Semua
                    <span>{{ $totalSemua }}</span>
                </a>

                @foreach ($daftarStatus as $st)
                    <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => $st])) }}"
                       class="bkl-filter {{ $statusTerpilih === $st ? 'is-active' : '' }}">
                        {{ $st }}
                    </a>
                @endforeach

            </nav>


            @if ($bookings->count())

                <div class="bkl-list-head" aria-hidden="true">
                    <span>#</span>
                    <span>Booking &amp; Jadwal</span>
                    <span>Total Pembayaran</span>
                    <span>Status</span>
                    <span class="bkl-right">Aksi</span>
                </div>

                <div class="bkl-list">

                    @foreach ($bookings as $b)

                        @php
                            $nomor = method_exists($bookings, 'firstItem') && $bookings->firstItem() !== null
                                ? $bookings->firstItem() + $loop->index
                                : $loop->iteration;

                            $statusText = (string) ($b->status ?? 'Tidak diketahui');
                            $perluBayar = (bool) ($b->perlu_bayar ?? (stripos($statusText, 'menunggu') !== false));

                            $detailUrl  = $adaDetail     ? route('booking.detail', $b->id)     : '#';
                            $paymentUrl = $adaPembayaran ? route('booking.pembayaran', $b->id) : '#';
                        @endphp

                        <article class="bkl-item bkl-reveal" style="--i:{{ min($loop->index, 8) }}">

                            {{-- nomor --}}
                            <div class="bkl-number-cell">{{ $nomor }}</div>

                            {{-- kode dan jadwal --}}
                            <div class="bkl-booking-info">

                                <a class="bkl-booking-code" href="{{ $detailUrl }}">
                                    {{ $b->kode_booking ?? 'Booking #' . $b->id }}
                                    <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                </a>

                                <div class="bkl-date-line">
                                    <span>
                                        <i class="far fa-calendar" aria-hidden="true"></i>
                                        {{ $b->tanggal ? \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') : 'Jadwal belum ditentukan' }}
                                    </span>

                                    @if (! empty($b->jam))
                                        <span>
                                            <i class="far fa-clock" aria-hidden="true"></i>
                                            {{ substr($b->jam, 0, 5) }}
                                        </span>
                                    @endif
                                </div>

                            </div>

                            {{-- total --}}
                            <div class="bkl-total-cell">
                                <span class="bkl-mobile-label">Total</span>
                                <strong data-rp="{{ (int) ($b->total ?? 0) }}">{{ $rp($b->total ?? 0) }}</strong>
                            </div>

                            {{-- status --}}
                            <div class="bkl-item-status">
                                <span class="bkl-status {{ $kelasStatus($statusText) }}">
                                    <span class="bkl-status-dot"></span>
                                    {{ $statusText }}
                                </span>
                            </div>

                            {{-- aksi --}}
                            <div class="bkl-action-cell">

                                @if ($perluBayar && $adaPembayaran)

                                    <a href="{{ $paymentUrl }}" class="bkl-action bkl-action-primary">
                                        <i class="fas fa-credit-card" aria-hidden="true"></i>
                                        <span>Bayar</span>
                                    </a>

                                @elseif ($adaDetail)

                                    <a href="{{ $detailUrl }}" class="bkl-action bkl-action-secondary">
                                        <i class="far fa-eye" aria-hidden="true"></i>
                                        <span>Detail</span>
                                    </a>

                                @else

                                    <span class="bkl-action-disabled">-</span>

                                @endif

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                {{-- data kosong --}}
                <div class="bkl-empty bkl-reveal">

                    <div class="bkl-empty-art">
                        <span class="bkl-empty-ring"></span>
                        <i class="far fa-calendar-times"></i>
                        <span class="bkl-empty-spark">✦</span>
                    </div>

                    <span class="bkl-kicker">BELUM ADA RESERVASI</span>

                    <h3>
                        {{ $statusTerpilih ? 'Belum Ada Booking dengan Status Ini' : 'Waktunya Memanjakan Diri' }}
                    </h3>

                    <p>
                        Booking perawatan yang Anda buat akan muncul di sini.
                        Yuk, rencanakan waktu untuk diri sendiri.
                    </p>

                    <a href="{{ $createUrl }}" class="bkl-btn bkl-btn-maroon">
                        <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                        Buat Booking Pertama
                    </a>

                </div>

            @endif


            {{-- pagination --}}
            @if (method_exists($bookings, 'hasPages') && $bookings->hasPages())
                <div class="bkl-pagination">
                    {{ $bookings->withQueryString()->links('vendor.pagination.layanan') }}
                </div>
            @endif

        </section>


        {{-- ===================== CATATAN BAWAH ===================== --}}
        <div class="bkl-footer-note bkl-reveal">

            <span class="bkl-footer-icon"><i class="fas fa-heart"></i></span>

            <p>
                <strong>Me-time Anda itu penting.</strong>
                <br>
                Terima kasih telah memilih Azzahra untuk menemani
                perawatan dan relaksasi Anda.
            </p>

            <span class="bkl-footer-flower" aria-hidden="true">✿</span>

        </div>

    </div>

</main>

{{-- animasi halaman ini. Dimuat langsung di sini agar tidak bergantung pada @stack di layout --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection