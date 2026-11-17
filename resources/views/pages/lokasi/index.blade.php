@extends('pages.auth.guest')

@section('title', 'Lokasi Kami | Azzahra Salon & Spa Muslimah')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman lokasi --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/lokasi-modern.css') }}?v={{ file_exists(public_path('template/css/lokasi-modern.css')) ? filemtime(public_path('template/css/lokasi-modern.css')) : time() }}">
@endpush

@section('content')

@php
    // Semua data lokasi dikumpulkan di sini supaya mudah diubah tanpa menyentuh markup.
    $namaSalon = 'Azzahra Salon & Spa Muslimah';
    $alamat    = 'Jl. Nyai Ahmad Dahlan No. 63, Ngupasan, Gondomanan, Kota Yogyakarta, DI Yogyakarta 55122';

    $urlPetunjuk = 'https://www.google.com/maps/search/?api=1&query='
        . urlencode('Azzahra Salon & Spa Muslimah Jl. Nyai Ahmad Dahlan 63 Yogyakarta');

    $urlPeta = 'https://maps.google.com/maps?q=Jl.%20Nyai%20Ahmad%20Dahlan%20No.63%2C%20Yogyakarta&t=&z=16&ie=UTF8&iwloc=&output=embed';

    // hari: 0 = Minggu, 1 = Senin, ... 6 = Sabtu (dipakai skrip untuk status buka/tutup)
    $jam = [
        ['label' => 'Senin s.d. Jumat', 'hari' => '1,2,3,4,5', 'buka' => '09:00', 'tutup' => '17:00'],
        ['label' => 'Sabtu',            'hari' => '6',         'buka' => '09:00', 'tutup' => '17:00'],
        ['label' => 'Minggu',           'hari' => '0',         'buka' => '09:00', 'tutup' => '17:00'],
    ];

    $tampilJam = fn (string $j) => str_replace(':', '.', $j);
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                <div class="lo-hero-top">
                    {{-- Teks awal berlaku tanpa JavaScript, lalu diperbarui menjadi status langsung --}}
                    <span class="lo-status" data-status>
                        <span class="lo-status-dot" aria-hidden="true"></span>
                        <span data-status-text>Buka setiap hari, 09.00 s.d. 17.00 WIB</span>
                    </span>
                </div>

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    LOKASI KAMI
                </span>

                <h1>
                    Temukan ruang
                    <em>kecantikan</em>
                    Anda.
                </h1>

                <p>
                    Kami hadir untuk memberikan pengalaman perawatan
                    yang nyaman, privat, dan penuh perhatian bagi setiap muslimah.
                </p>

                <div class="lo-hero-btns">
                    <a href="#daftar-lokasi" class="bkl-btn bkl-btn-light">
                        Lihat lokasi
                        <i class="fas fa-arrow-down bkl-btn-arrow" aria-hidden="true" style="transform:none"></i>
                    </a>

                    <a href="{{ $urlPetunjuk }}" target="_blank" rel="noopener noreferrer" class="bkl-btn bkl-btn-ghost">
                        <i class="fas fa-directions" aria-hidden="true"></i>
                        Petunjuk arah
                    </a>
                </div>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center lo-pin"><i class="fas fa-map-marker-alt"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Yogyakarta • Sejak 2004</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content" id="daftar-lokasi">

        {{-- ===================== JUDUL ===================== --}}
        <div class="lo-head bkl-reveal">
            <span class="bkl-kicker">FIND US</span>
            <h2 class="lo-title">Tempat untuk merawat diri, ruang untuk merasa nyaman.</h2>
            <p class="lo-sub">
                Temukan lokasi Azzahra dan rencanakan kunjungan Anda.
                Kami menantikan kehadiran Anda untuk menikmati momen
                perawatan dalam suasana yang tenang dan eksklusif.
            </p>
        </div>


        {{-- ===================== KARTU + PETA ===================== --}}
        <div class="lo-layout">

            <article class="lo-card bkl-reveal">

                <span class="lo-place">Yogyakarta</span>
                <h3>{{ $namaSalon }}</h3>

                <span class="lo-status is-light" data-status>
                    <span class="lo-status-dot" aria-hidden="true"></span>
                    <span data-status-text>Buka setiap hari, 09.00 s.d. 17.00 WIB</span>
                </span>

                <div class="lo-row" style="margin-top:22px">
                    <span class="lo-row-icon" aria-hidden="true">
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <div>
                        <strong>Alamat</strong>
                        <p>{{ $alamat }}</p>

                        <button type="button" class="lo-copy" data-copy="{{ $alamat }}">
                            <i class="far fa-copy" aria-hidden="true"></i>
                            <span data-copy-label>Salin alamat</span>
                        </button>
                    </div>
                </div>

                <div class="lo-row">
                    <span class="lo-row-icon" aria-hidden="true">
                        <i class="far fa-clock"></i>
                    </span>
                    <div style="flex:1;min-width:0">
                        <strong>Jam operasional</strong>

                        <table class="lo-hours">
                            <tbody>
                                @foreach ($jam as $baris)
                                    <tr data-hours-row
                                        data-days="{{ $baris['hari'] }}"
                                        data-open="{{ $baris['buka'] }}"
                                        data-close="{{ $baris['tutup'] }}">
                                        <th scope="row">{{ $baris['label'] }}</th>
                                        <td>{{ $tampilJam($baris['buka']) }} - {{ $tampilJam($baris['tutup']) }} WIB</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lo-actions">
                    <a href="{{ $urlPetunjuk }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="bkl-btn bkl-btn-maroon">
                        <i class="fas fa-directions" aria-hidden="true"></i>
                        Petunjuk arah
                    </a>
                </div>

                {{-- Pengumuman untuk pembaca layar saat alamat tersalin --}}
                <span class="lo-sr" role="status" aria-live="polite" data-live></span>
            </article>

            <div class="lo-map bkl-reveal">
                <iframe
                    src="{{ $urlPeta }}"
                    title="Peta {{ $namaSalon }} Yogyakarta"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>

                <a class="lo-map-open"
                   href="{{ $urlPetunjuk }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    Buka di Google Maps
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                </a>
            </div>

        </div>


        {{-- ===================== PENUTUP ===================== --}}
        <section class="lo-band bkl-reveal">

            <span class="lo-band-flower" aria-hidden="true">✿</span>

            <div>
                <h2>Luangkan waktu untuk diri Anda.</h2>
                <p>
                    Karena merawat diri juga merupakan bagian
                    dari mencintai diri sendiri.
                </p>
            </div>

            <div class="lo-band-actions">
                <a href="{{ route('perawatan-layanan.index') }}" class="bkl-btn bkl-btn-light">
                    Jelajahi perawatan kami
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

                <a href="{{ route('jadwal-terapis.index') }}" class="bkl-btn bkl-btn-ghost">
                    Lihat jadwal terapis
                </a>
            </div>

        </section>

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

    <script>
        (function () {
            var page = document.querySelector('.bkl-page');
            if (!page) return;

            /* ---------- Status buka / tutup berdasarkan waktu WIB ---------- */
            var namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var kodeHari = { Sun: 0, Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6 };

            var jadwal = {};   // nomor hari -> { buka, tutup } dalam menit
            var barisJam = page.querySelectorAll('[data-hours-row]');

            function keMenit(teks) {
                var p = teks.split(':');
                return parseInt(p[0], 10) * 60 + parseInt(p[1], 10);
            }

            function keTampil(menit) {
                var j = Math.floor(menit / 60), m = menit % 60;
                return (j < 10 ? '0' : '') + j + '.' + (m < 10 ? '0' : '') + m;
            }

            barisJam.forEach(function (row) {
                row.getAttribute('data-days').split(',').forEach(function (d) {
                    jadwal[parseInt(d, 10)] = {
                        buka:  keMenit(row.getAttribute('data-open')),
                        tutup: keMenit(row.getAttribute('data-close'))
                    };
                });
            });

            function sekarangWIB() {
                var parts = new Intl.DateTimeFormat('en-US', {
                    timeZone: 'Asia/Jakarta',
                    weekday: 'short',
                    hour: '2-digit',
                    minute: '2-digit',
                    hourCycle: 'h23'
                }).formatToParts(new Date());

                var v = {};
                parts.forEach(function (p) { v[p.type] = p.value; });

                return {
                    hari: kodeHari[v.weekday],
                    menit: parseInt(v.hour, 10) * 60 + parseInt(v.minute, 10)
                };
            }

            function perbaruiStatus() {
                if (!window.Intl || !Object.keys(jadwal).length) return;

                var now    = sekarangWIB();
                var hariIni = jadwal[now.hari];
                var terbuka = false;
                var teks;

                if (hariIni && now.menit >= hariIni.buka && now.menit < hariIni.tutup) {
                    terbuka = true;
                    teks = 'Buka sekarang, tutup pukul ' + keTampil(hariIni.tutup) + ' WIB';
                } else if (hariIni && now.menit < hariIni.buka) {
                    teks = 'Tutup, buka hari ini pukul ' + keTampil(hariIni.buka) + ' WIB';
                } else {
                    teks = 'Tutup sementara';
                    for (var i = 1; i <= 7; i++) {
                        var hari = (now.hari + i) % 7;
                        if (jadwal[hari]) {
                            teks = 'Tutup, buka kembali ' + (i === 1 ? 'besok' : namaHari[hari]) +
                                   ' pukul ' + keTampil(jadwal[hari].buka) + ' WIB';
                            break;
                        }
                    }
                }

                page.querySelectorAll('[data-status]').forEach(function (el) {
                    el.classList.toggle('is-open', terbuka);
                    el.classList.toggle('is-closed', !terbuka);
                    el.querySelector('[data-status-text]').textContent = teks;
                });

                /* Sorot baris hari ini pada tabel jam operasional */
                barisJam.forEach(function (row) {
                    var hariBaris = row.getAttribute('data-days').split(',').map(Number);
                    var inilah    = hariBaris.indexOf(now.hari) !== -1;
                    var tag       = row.querySelector('.lo-today-tag');

                    row.classList.toggle('is-today', inilah);

                    if (inilah && !tag) {
                        tag = document.createElement('span');
                        tag.className = 'lo-today-tag';
                        tag.textContent = 'Hari ini';
                        row.querySelector('th').appendChild(tag);
                    } else if (!inilah && tag) {
                        tag.remove();
                    }
                });
            }

            perbaruiStatus();
            setInterval(perbaruiStatus, 60000);


            /* ---------- Konfeti kecil saat alamat tersalin ---------- */
            var kurangiGerak = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            function ledakan(el) {
                if (kurangiGerak || !el.animate) return;

                var r = el.getBoundingClientRect();
                var emoji = ['✨', '🌸', '🤍', '✦', '🎀'];

                for (var i = 0; i < 14; i++) {
                    var c = document.createElement('span');
                    c.className = 'lo-confetti';
                    c.textContent = emoji[Math.floor(Math.random() * emoji.length)];
                    c.style.left = (r.left + r.width / 2) + 'px';
                    c.style.top = (r.top + r.height / 2) + 'px';
                    document.body.appendChild(c);

                    var sudut = Math.random() * Math.PI * 2;
                    var jarak = 60 + Math.random() * 80;

                    c.animate([
                        { transform: 'translate(-50%, -50%) scale(.4)', opacity: 1 },
                        { transform: 'translate(calc(-50% + ' + (Math.cos(sudut) * jarak) + 'px), calc(-50% + ' + (Math.sin(sudut) * jarak - 30) + 'px)) scale(1.1) rotate(' + (Math.random() * 360) + 'deg)', opacity: 0 }
                    ], { duration: 900 + Math.random() * 500, easing: 'cubic-bezier(.2,.8,.3,1)' }).onfinish = (function (n) {
                        return function () { n.remove(); };
                    })(c);
                }
            }

            /* ---------- Salin alamat ---------- */
            var live = page.querySelector('[data-live]');

            page.querySelectorAll('[data-copy]').forEach(function (btn) {
                var label = btn.querySelector('[data-copy-label]');
                var icon  = btn.querySelector('i');
                var awal  = label.textContent;
                var timer;

                function berhasil() {
                    btn.classList.add('is-copied');
                    ledakan(btn);
                    icon.className = 'fas fa-check';
                    label.textContent = 'Alamat tersalin';
                    if (live) live.textContent = 'Alamat berhasil disalin';

                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        btn.classList.remove('is-copied');
                        icon.className = 'far fa-copy';
                        label.textContent = awal;
                    }, 2200);
                }

                function cadangan(teks) {
                    var area = document.createElement('textarea');
                    area.value = teks;
                    area.setAttribute('readonly', '');
                    area.style.position = 'fixed';
                    area.style.opacity = '0';
                    document.body.appendChild(area);
                    area.select();
                    try { if (document.execCommand('copy')) berhasil(); } catch (e) {}
                    document.body.removeChild(area);
                }

                btn.addEventListener('click', function () {
                    var teks = btn.getAttribute('data-copy');

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(teks).then(berhasil, function () { cadangan(teks); });
                    } else {
                        cadangan(teks);
                    }
                });
            });

        })();
    </script>

@endsection