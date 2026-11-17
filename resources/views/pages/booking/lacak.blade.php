@extends('pages.auth.guest')

@section('title', 'Lacak Booking | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman lacak --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/lacak-modern.css') }}?v={{ file_exists(public_path('template/css/lacak-modern.css')) ? filemtime(public_path('template/css/lacak-modern.css')) : time() }}">
@endpush

@section('content')

@php
    // Variabel yang dibaca dari controller:
    // $booking : model Reservasi hasil pencarian, atau null
    // $kode    : kode reservasi yang dicari
    $booking = $booking ?? null;
    $kode = $kode ?? request('kode');

    $statusRaw = strtolower((string) ($booking->status ?? ''));
    $statusClass = 'is-wait';
    $dibatalkan = false;
    $langkahSelesai = 1; // jumlah langkah yang sudah dilewati: 1 = dibuat, 2 = dikonfirmasi, 3 = selesai

    if (str_contains($statusRaw, 'batal') || str_contains($statusRaw, 'tolak') || str_contains($statusRaw, 'cancel')) {
        $statusClass = 'is-cancelled';
        $dibatalkan = true;
    } elseif (str_contains($statusRaw, 'selesai') || str_contains($statusRaw, 'done')) {
        $statusClass = 'is-complete';
        $langkahSelesai = 3;
    } elseif (str_contains($statusRaw, 'konfirmasi') || str_contains($statusRaw, 'terima') || str_contains($statusRaw, 'lunas') || str_contains($statusRaw, 'bayar')) {
        $statusClass = 'is-confirmed';
        $langkahSelesai = 2;
    } elseif (str_contains($statusRaw, 'proses')) {
        $statusClass = 'is-process';
        $langkahSelesai = 2;
    }

    $langkah = [
        ['judul' => 'Booking dibuat', 'ket' => 'Diterima sistem', 'ikon' => 'fa-calendar-check'],
        ['judul' => 'Dikonfirmasi',   'ket' => 'Oleh admin salon', 'ikon' => 'fa-circle-check'],
        ['judul' => 'Selesai',        'ket' => 'Perawatan tuntas', 'ikon' => 'fa-spa'],
    ];

    // Semua perawatan dalam reservasi (relasi belongsToMany dengan pivot)
    $items = $booking ? $booking->perawatans : collect();

    // Total dihitung dari harga x jumlah di pivot
    $total = $items->sum(function ($it) {
        return ($it->pivot->harga ?? 0) * ($it->pivot->jumlah ?? 1);
    });
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
                    LACAK BOOKING
                </span>

                <h1>
                    Cek status booking,
                    <em>kapan saja.</em>
                </h1>

                <p>
                    Masukkan kode booking dan nomor WhatsApp yang Anda pakai
                    saat memesan, lalu kami tampilkan status terbarunya.
                </p>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-ticket-alt"></i></div>

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


    <div class="bkl-wrap lk-narrow bkl-content">

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

        @if ($errors->any())
            <div class="bkl-alert bkl-alert-error" role="alert">
                <span class="bkl-alert-icon"><i class="fas fa-exclamation"></i></span>
                <div class="bkl-alert-list">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="bkl-alert-close" aria-label="Tutup notifikasi" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif


        {{-- ===================== FORM PENCARIAN ===================== --}}
        <section class="lk-card bkl-reveal">

            <div class="lk-card-head">
                <span class="lk-card-ico"><i class="fas fa-search"></i></span>
                <div>
                    <span class="bkl-kicker">TRACK YOUR BOOKING</span>
                    <h2>Cari Booking</h2>
                    <p>Kode booking dikirim lewat WhatsApp setelah booking dibuat.</p>
                </div>
            </div>

            <form action="{{ route('booking.lacak') }}" method="GET" id="formLacak">

                <div class="lk-grid">

                    <div class="lk-field">
                        <label for="kode">Kode Booking <span>*</span></label>
                        <div class="lk-input">
                            <i class="fas fa-ticket-alt"></i>
                            <input type="text"
                                name="kode"
                                id="kode"
                                value="{{ old('kode', $kode) }}"
                                class="lk-mono"
                                placeholder="Contoh: RSV-20261031-6415"
                                autocomplete="off"
                                required>
                        </div>
                    </div>

                    <div class="lk-field">
                        <label for="no_whatsapp">Nomor WhatsApp <span>*</span></label>
                        <div class="lk-input">
                            <i class="fab fa-whatsapp"></i>
                            <input type="tel"
                                name="no_whatsapp"
                                id="no_whatsapp"
                                value="{{ old('no_whatsapp', request('no_whatsapp')) }}"
                                inputmode="tel"
                                placeholder="08xxxxxxxxxx"
                                autocomplete="tel"
                                required>
                        </div>
                    </div>

                </div>

                <div class="lk-actions">
                    <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-line">
                        <i class="fas fa-plus"></i> Buat Booking Baru
                    </a>
                    <button type="submit" class="bkl-btn bkl-btn-maroon" id="btnLacak">
                        <i class="fas fa-search"></i> Lacak Sekarang
                    </button>
                </div>

            </form>

        </section>


        {{-- ===================== HASIL ===================== --}}
        @if ($booking)

            <article class="lk-ticket">

                <div class="lk-ticket-top">
                    <div class="lk-ticket-row">
                        <div>
                            <div class="lk-ticket-label">Kode Booking</div>
                            <div class="lk-code-line">
                                <span class="lk-code" id="kodeBooking">{{ $booking->kode_reservasi }}</span>
                                <button type="button" class="lk-copy" id="btnSalin" data-kode="{{ $booking->kode_reservasi }}">
                                    <i class="far fa-copy"></i> <span>Salin</span>
                                </button>
                            </div>
                        </div>

                        <span class="bkl-status {{ $statusClass }}">
                            <span class="bkl-status-dot"></span>
                            {{ ucfirst($booking->status ?? 'Menunggu') }}
                        </span>
                    </div>
                </div>

                <div class="lk-perf"></div>

                <div class="lk-ticket-body">

                    {{-- Progres status --}}
                    @if ($dibatalkan)
                        <div class="lk-cancel">
                            <i class="fas fa-circle-xmark"></i>
                            <span>Booking ini dibatalkan. Anda bisa membuat booking baru kapan saja.</span>
                        </div>
                    @else
                        <div class="lk-steps" style="--lk-p: {{ ($langkahSelesai - 1) / 2 }}">
                            @foreach ($langkah as $i => $l)
                                @php
                                    $kelas = $i < $langkahSelesai ? 'is-done' : ($i === $langkahSelesai ? 'is-now' : '');
                                @endphp
                                <div class="lk-step {{ $kelas }}">
                                    <div class="lk-dot">
                                        <i class="fas {{ $i < $langkahSelesai ? 'fa-check' : $l['ikon'] }}"></i>
                                    </div>
                                    <b>{{ $l['judul'] }}</b>
                                    <small>{{ $l['ket'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="lk-info">

                        <div class="lk-tile">
                            <div class="lk-tile-ico"><i class="far fa-calendar"></i></div>
                            <small>Tanggal</small>
                            <strong>{{ !empty($booking->tanggal) ? \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d F Y') : '-' }}</strong>
                        </div>

                        <div class="lk-tile">
                            <div class="lk-tile-ico"><i class="far fa-clock"></i></div>
                            <small>Jam</small>
                            <strong>
                                {{ !empty($booking->jam_mulai) ? substr($booking->jam_mulai, 0, 5) : '-' }}
                                @if (!empty($booking->jam_selesai))
                                    - {{ substr($booking->jam_selesai, 0, 5) }}
                                @endif
                            </strong>
                        </div>

                        <div class="lk-tile">
                            <div class="lk-tile-ico"><i class="fas fa-user-nurse"></i></div>
                            <small>Terapis</small>
                            <strong>{{ optional($booking->pegawai)->nama ?? '-' }}</strong>
                        </div>

                        <div class="lk-tile">
                            <div class="lk-tile-ico"><i class="far fa-user"></i></div>
                            <small>Atas Nama</small>
                            <strong>{{ optional($booking->pelanggan)->nama ?? '-' }}</strong>
                        </div>

                        <div class="lk-tile is-wide">
                            <div class="lk-tile-ico"><i class="far fa-note-sticky"></i></div>
                            <small>Catatan</small>
                            <strong>{{ $booking->catatan ?: '-' }}</strong>
                        </div>

                    </div>

                    {{-- Perawatan --}}
                    @if ($items->count())
                        <div class="lk-sec"><h3>Perawatan</h3></div>

                        <div class="lk-items">
                            @foreach ($items as $it)
                                @php
                                    $nama   = $it->nama ?? 'Perawatan';
                                    $durasi = $it->pivot->durasi ?? $it->durasi ?? null;
                                    $qty    = $it->pivot->jumlah ?? 1;
                                    $harga  = $it->pivot->harga ?? $it->harga ?? 0;
                                @endphp
                                <div class="lk-item">
                                    <div>
                                        <div class="lk-item-name">{{ $nama }}</div>
                                        <div class="lk-item-meta">
                                            <span class="lk-chip"><i class="far fa-clock"></i> {{ $durasi ? $durasi . ' menit' : '-' }}</span>
                                            <span class="lk-chip"><i class="fas fa-xmark"></i> {{ $qty }}</span>
                                        </div>
                                    </div>
                                    <div class="lk-item-price">Rp{{ number_format($harga * $qty, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="lk-total">
                            <span>Total</span>
                            <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    @endif

                    <div class="lk-note">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            Kirim bukti pembayaran lewat WhatsApp dalam waktu 2 jam setelah booking dibuat.
                            Booking tanpa bukti pembayaran akan dibatalkan otomatis.
                        </span>
                    </div>

                </div>

            </article>

        @elseif (request()->filled('kode') && !$errors->any())

            <div class="bkl-empty lk-empty-wrap">

                <div class="bkl-empty-art">
                    <span class="bkl-empty-ring"></span>
                    <i class="fas fa-search"></i>
                    <span class="bkl-empty-spark">✦</span>
                </div>

                <span class="bkl-kicker">TIDAK DITEMUKAN</span>

                <h3>Booking tidak ditemukan</h3>

                <p>
                    Periksa lagi kode booking dan nomor WhatsApp Anda.
                    Pastikan penulisannya sama seperti saat memesan.
                </p>

            </div>

        @endif

    </div>
</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Tombol cari: tampilkan status loading saat form dikirim
        var form = document.getElementById('formLacak');
        var btn  = document.getElementById('btnLacak');
        if (form && btn) {
            form.addEventListener('submit', function () {
                btn.classList.add('is-loading');
                var ico = btn.querySelector('i');
                if (ico) ico.className = 'fas fa-circle-notch';
            });
        }

        // Tombol salin kode booking
        var salin = document.getElementById('btnSalin');
        if (salin) {
            salin.addEventListener('click', function () {
                var kode  = salin.getAttribute('data-kode') || '';
                var label = salin.querySelector('span');

                function selesai() {
                    salin.classList.add('is-done');
                    if (label) label.textContent = 'Tersalin';
                    setTimeout(function () {
                        salin.classList.remove('is-done');
                        if (label) label.textContent = 'Salin';
                    }, 1800);
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(kode).then(selesai);
                } else {
                    var t = document.createElement('textarea');
                    t.value = kode;
                    document.body.appendChild(t);
                    t.select();
                    try { document.execCommand('copy'); selesai(); } catch (e) {}
                    document.body.removeChild(t);
                }
            });
        }

        // Gulir otomatis ke hasil setelah pencarian
        var hasil = document.querySelector('.lk-ticket, .lk-empty-wrap');
        if (hasil) {
            setTimeout(function () {
                hasil.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 150);
        }
    });
</script>

@endsection