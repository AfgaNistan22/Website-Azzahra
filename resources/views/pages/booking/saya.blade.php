@extends('pages.auth.guest')

@section('title', 'Bookingan Saya | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman Daftar Booking --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    {{-- pelengkap khusus halaman ini --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-saya.css') }}?v={{ file_exists(public_path('template/css/booking-saya-extra.css')) ? filemtime(public_path('template/css/booking-saya-extra.css')) : time() }}">
@endpush

@section('content')

@php
    $rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.');

    $rute = fn ($nama, $param = [], $cadangan = '#') =>
        \Illuminate\Support\Facades\Route::has($nama)
            ? route($nama, $param)
            : $cadangan;

    $items = $booking ? ($booking->perawatan ?? collect()) : collect();

    $durasi = $items->sum(
        fn ($i) => (int) $i->durasi * (int) data_get($i, 'pivot.jumlah', 1)
    );

    $fotoDef = asset('template/img/service-default.png');

    $hargaBaris = fn ($i) =>
        (int) data_get($i, 'pivot.harga', $i->harga)
        * (int) data_get($i, 'pivot.jumlah', 1);

    $subtotal = $items->sum($hargaBaris);

    $bayar = $booking
        ? optional($booking->pembayaran)->jumlah
        : null;

    $diskon = $booking
        ? (int) optional($booking->pembayaran)->jumlah_diskon
        : 0;

    $total = $bayar ?? ($booking->total ?? $subtotal);

    $status = $booking->status ?? 'Menunggu';

    $lunas = $booking &&
        optional($booking->pembayaran)->status === 'Lunas';

    $kirim = $booking &&
        (bool) ($booking->sudah_kirim_bukti ?? false);

    $perluBayar = $booking &&
        (bool) ($booking->perlu_bayar ?? false);

    $batal   = $status === 'Dibatalkan';
    $selesai = $status === 'Selesai';

    $kelasStatus = match ($status) {
        'Dikonfirmasi' => 'is-confirmed',
        'Selesai'      => 'is-complete',
        'Dibatalkan'   => 'is-cancelled',
        default        => 'is-wait',
    };

    $konfirmasi = in_array(
        $status,
        ['Dikonfirmasi', 'Selesai'],
        true
    ) || $lunas;

    $tahap = [
        ['Booking dibuat', 'done'],
        ['Pembayaran', ($kirim || $konfirmasi) ? 'done' : 'current'],
        ['Verifikasi admin', $konfirmasi ? 'done' : ($kirim ? 'current' : 'todo')],
        ['Selesai', $status === 'Selesai' ? 'done' : ($konfirmasi ? 'current' : 'todo')],
    ];

    $jamMulai = $booking && !empty($booking->jam)
        ? substr($booking->jam, 0, 5)
        : null;

    $jamSelesai = $booking && !empty($booking->jam_selesai)
        ? substr($booking->jam_selesai, 0, 5)
        : null;

    $adaHapus = \Illuminate\Support\Facades\Route::has('booking.item.hapus');

    // ---- ATURAN PEMBATALAN ----
    // > 3 jam sebelum jadwal : boleh dibatalkan lewat sistem
    // < 3 jam sebelum jadwal : pembatalan lewat sistem ditutup
    // (aturan yang sama dicek ulang di BookingController::batal)
    $batasJam = 3;
    $jadwal   = null;

    if ($booking && !empty($booking->tanggal)) {
        $jadwal = \Carbon\Carbon::parse(
            \Carbon\Carbon::parse($booking->tanggal)->format('Y-m-d') . ' ' . ($jamMulai ?? '00:00')
        );
    }

    $sisaMenit  = $jadwal ? now()->diffInMinutes($jadwal, false) : null;   // negatif = jadwal sudah lewat
    $bisaBatal  = $booking && !$batal && !$selesai
                  && $sisaMenit !== null && $sisaMenit > ($batasJam * 60);
    $batasBatal = $jadwal
        ? $jadwal->copy()->subHours($batasJam)->translatedFormat('d F Y, H:i')
        : null;
    $adaBatal   = \Illuminate\Support\Facades\Route::has('booking.batal');
    $waSalon    = !empty($kontakSalon) ? preg_replace('/\D+/', '', $kontakSalon) : null;

    // Nama pelanggan: pelanggan login atau tamu
    $namaPelanggan = optional($booking->pelanggan ?? null)->nama
        ?? optional(auth()->user())->name
        ?? 'Pelanggan';

    $createUrl = $rute('booking.create', [], $rute('perawatan-layanan.index', [], url('/')));
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
                    Bookingan
                    <em>Saya.</em>
                </h1>

                <p>
                    Pantau jadwal, perawatan, dan pembayaran Anda
                    dengan nyaman dalam satu tempat.
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

                <div class="bkl-art-center"><i class="fas fa-calendar-check"></i></div>

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


        {{-- ===================== BOOKING KOSONG ===================== --}}
        @if (!$booking || $items->isEmpty())

            <div class="bkl-empty bkl-reveal">

                <div class="bkl-empty-art">
                    <span class="bkl-empty-ring"></span>
                    <i class="far fa-calendar-times"></i>
                    <span class="bkl-empty-spark">✦</span>
                </div>

                <span class="bkl-kicker">BELUM ADA BOOKING AKTIF</span>

                <h3>Waktunya Memanjakan Diri</h3>

                <p>
                    Pilih perawatan favorit Anda dan buat jadwal
                    kunjungan bersama Azzahra.
                </p>

                <a href="{{ $createUrl }}" class="bkl-btn bkl-btn-maroon">
                    <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                    Buat Booking
                </a>

            </div>

        @else

            {{-- ===================== TAHAPAN ===================== --}}
            @if ($batal)

                <div class="bks-note is-bad bkl-reveal">
                    <i class="fas fa-ban" aria-hidden="true"></i>
                    <span>
                        Booking ini sudah dibatalkan.
                        Anda dapat membuat booking baru kapan saja.
                    </span>
                </div>

            @else

                <div class="bks-steps bkl-reveal" aria-label="Tahapan booking">
                    @foreach ($tahap as $i => [$label, $keadaan])
                        <div class="bks-step is-{{ $keadaan }}"
                             @if ($keadaan === 'current') aria-current="step" @endif>

                            <div class="bks-dot">
                                @if ($keadaan === 'done')
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>

                            <div class="bks-step-label">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>

            @endif


            <div class="bks-grid">

                {{-- ===================== KOLOM KIRI ===================== --}}
                <div class="bks-stack">

                    {{-- INFORMASI BOOKING --}}
                    <div class="bks-card bkl-reveal">
                        <div class="bks-card-head">
                            <div>
                                <span class="bkl-kicker">BOOKING INFORMATION</span>
                                <h2>Terima kasih, {{ $namaPelanggan }}</h2>
                                <p>
                                    Anda memilih {{ $items->count() }} perawatan
                                    di Azzahra Salon &amp; Spa Muslimah.
                                </p>
                            </div>

                            <span class="bkl-status {{ $kelasStatus }}">
                                <span class="bkl-status-dot"></span>
                                {{ $status }}
                            </span>
                        </div>

                        <div class="bks-card-body">
                            <div class="bks-tiles">

                                {{-- KODE BOOKING --}}
                                <div class="bks-tile">
                                    <div class="bks-tile-icon">
                                        <i class="fas fa-ticket-alt" aria-hidden="true"></i>
                                    </div>

                                    <div>
                                        <small>Kode Booking</small>

                                        <div class="bks-code-row">
                                            <strong class="bks-code" id="kodeBooking">
                                                {{ $booking->kode_booking }}
                                            </strong>

                                            <button type="button"
                                                    class="bks-copy"
                                                    data-copy="{{ $booking->kode_booking }}">
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- TANGGAL DAN JAM --}}
                                <div class="bks-tile">
                                    <div class="bks-tile-icon">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    </div>

                                    <div>
                                        <small>Tanggal &amp; Jam</small>

                                        <strong>
                                            {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') }}

                                            @if ($jamMulai)
                                                · {{ $jamMulai }}

                                                @if ($jamSelesai)
                                                    - {{ $jamSelesai }}
                                                @endif

                                                WIB
                                            @endif
                                        </strong>
                                    </div>
                                </div>

                                {{-- TERAPIS --}}
                                <div class="bks-tile">
                                    <div class="bks-tile-icon">
                                        <i class="fas fa-user-nurse" aria-hidden="true"></i>
                                    </div>

                                    <div>
                                        <small>Terapis</small>
                                        <strong>
                                            {{ optional($booking->terapis)->nama ?? 'Ditentukan salon' }}
                                        </strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    {{-- DAFTAR PERAWATAN --}}
                    <div class="bks-card bkl-reveal">
                        <div class="bks-card-head">
                            <div>
                                <span class="bkl-kicker">YOUR TREATMENT</span>
                                <h2>Perawatan yang Dipilih</h2>
                                <p>Total durasi sekitar {{ $durasi }} menit.</p>
                            </div>
                        </div>

                        <div class="bks-items">

                            @foreach ($items as $item)

                                @php
                                    $jml = (int) data_get($item, 'pivot.jumlah', 1);
                                @endphp

                                <div class="bks-item">

                                    <img class="bks-thumb"
                                         src="{{ $item->foto ? asset('storage/' . $item->foto) : $fotoDef }}"
                                         alt="{{ $item->nama }}"
                                         loading="lazy"
                                         onerror="this.onerror=null;this.src='{{ $fotoDef }}';">

                                    <div class="bks-item-info">
                                        <div class="bks-item-name">{{ $item->nama }}</div>

                                        <div class="bks-item-meta">

                                            @if (optional($item->kategori)->nama)
                                                <span class="bks-chip is-gold">{{ $item->kategori->nama }}</span>
                                            @endif

                                            <span class="bks-chip">
                                                <i class="far fa-clock" aria-hidden="true"></i>
                                                {{ $item->durasi ?: '-' }} menit
                                            </span>

                                            @if ($jml > 1)
                                                <span class="bks-chip">× {{ $jml }}</span>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="bks-item-side">
                                        <span class="bks-price">{{ $rp($hargaBaris($item)) }}</span>

                                        {{-- Hapus item hanya saat booking masih bisa diubah --}}
                                        @if ($adaHapus && !$batal && !$selesai)
                                            <form action="{{ route('booking.item.hapus', [$booking->id, $item->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Hapus perawatan ini dari booking?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="bks-del"
                                                        aria-label="Hapus {{ $item->nama }}">
                                                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>

                            @endforeach

                        </div>

                        <div class="bks-sum">
                            <span>Jumlah Perawatan</span>
                            <strong>{{ $items->count() }} item</strong>
                        </div>
                    </div>


                    {{-- METODE PEMBAYARAN (disembunyikan bila booking dibatalkan) --}}
                    @if (!$batal && !empty($metodePembayaran) && count($metodePembayaran))

                        <div class="bks-card bkl-reveal">
                            <div class="bks-card-head">
                                <div>
                                    <span class="bkl-kicker">PAYMENT INFORMATION</span>
                                    <h2>Metode Pembayaran</h2>
                                    <p>Pilih salah satu rekening berikut untuk melakukan pembayaran.</p>
                                </div>
                            </div>

                            <div class="bks-card-body">

                                <div class="bks-pay">

                                    @foreach ($metodePembayaran as $metode)

                                        <div class="bks-pay-item">

                                            @if (!empty($metode->logo))
                                                <img class="bks-pay-logo"
                                                     src="{{ asset('storage/' . $metode->logo) }}"
                                                     alt="{{ $metode->metode }}"
                                                     onerror="this.style.display='none'">
                                            @endif

                                            <div class="bks-pay-body">
                                                <div class="bks-pay-name">{{ $metode->metode }}</div>
                                                <div class="bks-pay-no">{{ $metode->norek }}</div>
                                                <div class="bks-pay-an">a/n {{ $metode->an }}</div>
                                            </div>

                                            <button type="button"
                                                    class="bks-copy"
                                                    data-copy="{{ preg_replace('/\s+/', '', (string) $metode->norek) }}">
                                                Salin
                                            </button>

                                        </div>

                                    @endforeach

                                </div>

                                <div class="bks-note">
                                    <i class="fas fa-shield-alt" aria-hidden="true"></i>

                                    <span>
                                        Sertakan nama pemilik rekening atau sumber dana,
                                        tanggal pembayaran, metode pembayaran, dan jumlah bayar
                                        saat konfirmasi. Booking diproses maksimal 1×24 jam
                                        setelah pembayaran diterima.
                                    </span>
                                </div>

                            </div>
                        </div>

                    @endif

                </div>


                {{-- ===================== KOLOM KANAN ===================== --}}
                <aside class="bks-aside">

                    <div class="bks-card bkl-reveal">
                        <div class="bks-card-head">
                            <div>
                                <span class="bkl-kicker">PAYMENT SUMMARY</span>
                                <h2>Ringkasan Pembayaran</h2>
                                <p>Rincian biaya booking Anda.</p>
                            </div>
                        </div>

                        <div class="bks-card-body">

                            <div class="bks-rows">

                                <div class="bks-row">
                                    <span>Subtotal Perawatan</span>
                                    <b>{{ $rp($subtotal) }}</b>
                                </div>

                                @if ($diskon > 0)
                                    <div class="bks-row is-disc">
                                        <span>Diskon Promo</span>
                                        <b>- {{ $rp($diskon) }}</b>
                                    </div>
                                @endif

                                <div class="bks-row is-main">
                                    <span>Total yang Dibayar</span>
                                    <b data-rp="{{ (int) $total }}">{{ $rp($total) }}</b>
                                </div>

                            </div>

                            {{-- STATUS PEMBAYARAN --}}
                            @if ($batal)

                                {{-- booking dibatalkan: tidak ada catatan pembayaran --}}

                            @elseif ($kirim && !$lunas)

                                <div class="bks-note is-wait">
                                    <i class="fas fa-hourglass-half" aria-hidden="true"></i>
                                    <span>
                                        Bukti pembayaran sudah kami terima
                                        dan sedang diverifikasi admin.
                                    </span>
                                </div>

                            @elseif ($lunas)

                                <div class="bks-note is-ok">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>
                                        Pembayaran sudah lunas.
                                        Sampai jumpa di salon.
                                    </span>
                                </div>

                            @else

                                <div class="bks-note">
                                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                                    <span>
                                        Booking akan kami proses setelah pembayaran dikonfirmasi.
                                        Simpan kode booking untuk memantau statusnya.
                                        @guest
                                            Untuk membuka kembali booking ini, gunakan halaman
                                            <a href="{{ $rute('booking.lacak', [], '#') }}">Lacak Booking</a>
                                            dengan kode booking dan nomor WhatsApp Anda.
                                        @endguest
                                    </span>
                                </div>

                            @endif

                            {{-- TOMBOL AKSI --}}
                            <div class="bks-cta">

                                @if (!$batal && !$selesai)

                                    @if ($perluBayar && \Illuminate\Support\Facades\Route::has('booking.pembayaran'))
                                        <a href="{{ route('booking.pembayaran', $booking->id) }}"
                                           class="bkl-btn bkl-btn-maroon">
                                            <i class="fas fa-wallet" aria-hidden="true"></i>
                                            Bayar Sekarang
                                        </a>
                                    @endif

                                    @if (\Illuminate\Support\Facades\Route::has('booking.konfirmasi'))
                                        <form action="{{ route('booking.konfirmasi', $booking->id) }}"
                                              method="POST"
                                              id="formKonfirmasi">

                                            @csrf

                                            <button type="submit" class="bkl-btn bkl-btn-maroon">
                                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                Saya Setuju &amp; Konfirmasi Booking
                                            </button>
                                        </form>
                                    @endif

                                @endif

                                <a href="{{ $rute('perawatan-layanan.index', [], url('/')) }}"
                                   class="bkl-btn bkl-btn-line">
                                    <i class="fas fa-plus" aria-hidden="true"></i>
                                    {{ $batal ? 'Buat Booking Baru' : 'Tambah Perawatan' }}
                                </a>

                                {{-- Semua Booking hanya untuk akun login --}}
                                @auth
                                    @if (\Illuminate\Support\Facades\Route::has('booking.daftar'))
                                        <a href="{{ route('booking.daftar') }}"
                                           class="bkl-btn bkl-btn-line">
                                            <i class="fas fa-list" aria-hidden="true"></i>
                                            Semua Booking
                                        </a>
                                    @endif
                                @endauth

                            </div>

                        </div>
                    </div>


                    {{-- PEMBATALAN BOOKING --}}
                    @if (!$batal && !$selesai)

                        <div class="bks-card bkl-reveal">
                            <div class="bks-card-head">
                                <div>
                                    <span class="bkl-kicker">CANCELLATION</span>
                                    <h2>Batalkan Booking</h2>
                                    <p>
                                        Pembatalan langsung dapat dilakukan paling lambat
                                        {{ $batasJam }} jam sebelum jadwal.
                                    </p>
                                </div>
                            </div>

                            <div class="bks-card-body">

                                @if ($bisaBatal && $adaBatal)

                                    <div class="bks-note is-wait is-first">
                                        <i class="fas fa-clock" aria-hidden="true"></i>
                                        <span>
                                            Booking ini masih bisa dibatalkan sampai
                                            <b>{{ $batasBatal }} WIB</b>.
                                        </span>
                                    </div>

                                    <form action="{{ route('booking.batal', $booking->id) }}"
                                          method="POST"
                                          class="bks-cancel-form"
                                          id="formBatal">

                                        @csrf
                                        @method('PATCH')

                                        <label class="bks-label" for="alasanBatal">
                                            Alasan pembatalan (opsional)
                                        </label>

                                        <select name="alasan_batal" id="alasanBatal" class="bks-select">
                                            <option value="">— Pilih alasan —</option>
                                            <option>Jadwal saya berubah</option>
                                            <option>Ingin mengganti perawatan</option>
                                            <option>Ingin mengganti tanggal / jam</option>
                                            <option>Berhalangan hadir</option>
                                            <option>Lainnya</option>
                                        </select>

                                        <button type="submit" class="bks-btn-cancel">
                                            <i class="fas fa-times-circle" aria-hidden="true"></i>
                                            Batalkan Booking
                                        </button>

                                    </form>

                                @else

                                    <div class="bks-lock">
                                        <i class="fas fa-lock" aria-hidden="true"></i>
                                        <span>
                                            Pembatalan melalui sistem sudah ditutup karena jadwal
                                            kurang dari {{ $batasJam }} jam lagi.
                                            @if ($waSalon)
                                                Silakan
                                                <a href="https://wa.me/{{ $waSalon }}?text={{ rawurlencode('Halo Azzahra, saya ingin menanyakan booking ' . $booking->kode_booking) }}"
                                                   target="_blank" rel="noopener">hubungi salon</a>.
                                            @else
                                                Silakan hubungi salon secara langsung.
                                            @endif
                                        </span>
                                    </div>

                                @endif

                                @if ($lunas || $kirim)
                                    <div class="bks-note">
                                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                                        <span>
                                            Pembayaran yang sudah masuk akan ditindaklanjuti admin
                                            sesuai kebijakan salon.
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>

                    @endif

                </aside>

            </div>

        @endif


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

{{-- animasi halaman (sama dengan Daftar Booking) --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

{{-- JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Mencegah tombol konfirmasi diklik berulang kali
    const formKonfirmasi = document.getElementById('formKonfirmasi');

    if (formKonfirmasi) {
        formKonfirmasi.addEventListener('submit', function () {
            const button = formKonfirmasi.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
                button.style.opacity = '.7';
                button.style.cursor = 'not-allowed';
            }
        });
    }

    // Konfirmasi pembatalan + cegah klik ganda
    const formBatal = document.getElementById('formBatal');

    if (formBatal) {
        formBatal.addEventListener('submit', function (event) {

            if (!confirm('Yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.')) {
                event.preventDefault();
                return;
            }

            const button = formBatal.querySelector('button[type="submit"]');

            if (button) {
                button.disabled = true;
                button.style.opacity = '.7';
                button.style.cursor = 'not-allowed';
            }

        });
    }

    // Fungsi salin kode booking dan nomor rekening
    document.querySelectorAll('.bks-copy').forEach(function (button) {

        button.addEventListener('click', async function () {

            const teks = button.getAttribute('data-copy') || '';
            const teksAwal = button.textContent;

            const selesai = function () {
                button.textContent = 'Tersalin';
                button.classList.add('is-done');

                setTimeout(function () {
                    button.textContent = teksAwal;
                    button.classList.remove('is-done');
                }, 1600);
            };

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(teks);
                } else {
                    const textarea = document.createElement('textarea');

                    textarea.value = teks;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';

                    document.body.appendChild(textarea);
                    textarea.select();

                    document.execCommand('copy');
                    textarea.remove();
                }

                selesai();

            } catch (error) {
                console.error('Gagal menyalin teks:', error);
            }

        });

    });

});
</script>

@endsection