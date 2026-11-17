@extends('pages.auth.guest')

@section('title', 'Kontak Kami | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman kontak --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/kontak-modern.css') }}?v={{ file_exists(public_path('template/css/kontak-modern.css')) ? filemtime(public_path('template/css/kontak-modern.css')) : time() }}">
@endpush

@section('content')

@php
    // ---------- Data lokasi: sama dengan halaman Lokasi ----------
    $namaSalon = 'Azzahra Salon & Spa Muslimah';
    $alamat    = 'Jl. Nyai Ahmad Dahlan No. 63, Ngupasan, Gondomanan, Kota Yogyakarta, DI Yogyakarta 55122';

    $urlPetunjuk = 'https://www.google.com/maps/search/?api=1&query='
        . urlencode('Azzahra Salon & Spa Muslimah Jl. Nyai Ahmad Dahlan 63 Yogyakarta');

    $urlPeta = 'https://maps.google.com/maps?q=Jl.%20Nyai%20Ahmad%20Dahlan%20No.63%2C%20Yogyakarta&t=&z=16&ie=UTF8&iwloc=&output=embed';

    $jam = [
        ['label' => 'Senin s.d. Jumat', 'buka' => '09:00', 'tutup' => '17:00'],
        ['label' => 'Sabtu',            'buka' => '09:00', 'tutup' => '17:00'],
        ['label' => 'Minggu',           'buka' => '09:00', 'tutup' => '17:00'],
    ];

    $tampilJam = fn (string $j) => str_replace(':', '.', $j);

    // ---------- Data kontak: ganti dengan data asli ----------
    // Nomor WhatsApp pakai format internasional tanpa tanda +, contoh: 6281234567890
    $telepon   = '081246771771';
    $whatsapp  = '081246771771';
    $email     = 'azzahraspamuslimah@gmail.com';
    $instagram = 'azzahraspamuslimah';

    $pesanWa = rawurlencode('Halo Azzahra Salon & Spa, saya ingin bertanya tentang layanan.');

    // ---------- Tambahan: link aman (tidak rusak bila nomor belum diisi) ----------
    $waOk  = (bool) preg_match('/^\d{8,15}$/', $whatsapp);
    $wa    = fn (string $teks) => $waOk
        ? 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode($teks)
        : '#';
    $linkWa = $wa('Halo Azzahra Salon & Spa, saya ingin bertanya tentang layanan.');

    $ringkasJam = collect($jam)
        ->map(fn ($b) => $b['label'] . ' ' . $tampilJam($b['buka']) . ' - ' . $tampilJam($b['tutup']) . ' WIB')
        ->implode('; ');

    // Topik cepat: pesan WhatsApp sudah terisi otomatis
    $topik = [
        ['😍', 'Tanya Harga',  'Cek daftar harga perawatan favoritmu.',       $wa('Halo Azzahra, saya mau tanya harga perawatan.')],
        ['📅', 'Cek Jadwal',   'Slot kosong minggu ini? Tanya dulu aja.',     $wa('Halo Azzahra, saya mau cek jadwal yang masih tersedia.')],
        ['💆‍♀️', 'Konsultasi',  'Bingung pilih perawatan? Kita bantuin.',      $wa('Halo Azzahra, saya mau konsultasi perawatan yang cocok untuk saya.')],
        ['🎁', 'Info Promo',   'Lagi ada promo apa nih? Biar nggak ketinggalan.', $wa('Halo Azzahra, lagi ada promo apa saja?')],
        ['🧾', 'Kirim Bukti Bayar', 'Habis transfer? Kirim bukti di sini.',   $wa('Halo Azzahra, saya mau kirim bukti pembayaran booking.')],
        ['📍', 'Arah ke Salon', 'Langsung buka rute di Google Maps.',         $urlPetunjuk],
    ];

    $kataGanti = ['harga', 'jadwal', 'promo', 'perawatan', 'booking'];
@endphp

<main class="bkl-page">

    {{-- ===================== HERO ===================== --}}
    <section class="bkl-hero">

        <div class="bkl-hero-glow bkl-hero-glow-one"></div>
        <div class="bkl-hero-glow bkl-hero-glow-two"></div>

        <div class="bkl-wrap bkl-hero-inner">

            <div class="bkl-hero-copy">

                {{-- status buka/tutup, dihitung otomatis (waktu Yogyakarta) --}}
                <div class="kt-live" id="ktLive" data-state="buka">
                    <span class="kt-live-dot"></span>
                    <span id="ktLiveText">{{ $jam[0]['label'] }} · {{ $tampilJam($jam[0]['buka']) }} - {{ $tampilJam($jam[0]['tutup']) }} WIB</span>
                </div>

                <span class="bkl-eyebrow">
                    <span class="bkl-eyebrow-dot"></span>
                    HUBUNGI KAMI
                </span>

                <h1>
                    Yuk, ngobrol
                    soal <br>
                    <em><span class="kt-rot" id="ktRot" data-words='@json($kataGanti)'>{{ $kataGanti[0] }}</span>.</em> 
                </h1>

                <p>
                    Bingung pilih perawatan? Mau cek jadwal? Chat aja, tim Azzahra
                    fast respon dan siap bantu. No ribet, no judge ✨
                </p>

                <div class="kt-hero-btns">
                    <a href="{{ $linkWa }}" target="_blank" rel="noopener" class="bkl-btn bkl-btn-light">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        Chat sekarang
                        <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-ghost">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i>
                        Langsung booking
                    </a>
                </div>

            </div>

            {{-- percakapan animasi --}}
            <div class="kt-chat" aria-hidden="true">
                <div class="kt-bubble is-salon"><b>Azzahra</b>Halo kak! 👋 Selamat datang di Azzahra Salon &amp; Spa.</div>
                <div class="kt-bubble is-user">Mau tanya paket perawatan dong ?</div>
                <div class="kt-bubble is-salon"><b>Azzahra</b>Siap kak! Mau facial, creambath, atau spa? 💆‍♀️</div>
                <div class="kt-typing"><i></i><i></i><i></i></div>
            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        {{-- ===================== MARQUEE ===================== --}}
        @php
            $kata = ['Khusus Muslimah', 'Privasi Terjaga', 'Produk Halal', 'Terapis Profesional', 'Fast Respon', 'Yogyakarta', 'Me-time Aman'];
        @endphp
        <div class="kt-marquee" aria-hidden="true">
            <div class="kt-marquee-track">
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($kata as $k)
                        <span>{{ $k }}</span>
                    @endforeach
                @endfor
            </div>
        </div>


        {{-- ===================== KARTU KONTAK ===================== --}}
        <section class="kt-section">

            <div class="kt-head bkl-reveal">
                <span class="bkl-kicker">GET IN TOUCH</span>
                <h2 class="kt-title">Pilih jalur favoritmu</h2>
                <p class="kt-sub">Mau telepon, chat, atau email? Semua jalur kebuka kok.</p>
            </div>

            <div class="kt-contact" style="margin-bottom:0">

                <div class="kt-item bkl-reveal" style="--i:0">
                    <i class="fas fa-phone-alt"></i>
                    <small>Telepon</small>
                    <strong>{{ $telepon }}</strong>
                    <span class="kt-vibe">Pengen langsung ngobrol? Telepon aja, kita angkat 📞</span>
                </div>

                <div class="kt-item bkl-reveal" style="--i:1">
                    <span class="kt-sticker">Fast respon 🚀</span>
                    <i class="fab fa-whatsapp"></i>
                    <small>WhatsApp</small>
                    <strong><a href="{{ $linkWa }}" target="_blank" rel="noopener">Chat via WhatsApp</a></strong>
                    <span class="kt-vibe">Jalur paling santai. Kirim pesan, kita bales secepatnya 💬</span>
                </div>

                <div class="kt-item bkl-reveal" style="--i:2">
                    <i class="fas fa-envelope"></i>
                    <small>Email</small>
                    <strong>{{ $email }}</strong>
                    <span class="kt-vibe">Buat urusan yang lebih formal atau detail ✉️</span>
                </div>

            </div>

        </section>


        {{-- ===================== TOPIK CEPAT ===================== --}}
        <section class="kt-section">

            <div class="kt-head bkl-reveal">
                <span class="bkl-kicker">QUICK CHAT</span>
                <h2 class="kt-title">Mau ngobrolin apa?</h2>
                <p class="kt-sub">Tap salah satu, pesannya langsung terisi otomatis. Tinggal kirim.</p>
            </div>

            <div class="kt-chips">
                @foreach ($topik as $n => $t)
                    <a href="{{ $t[3] }}"
                       @if ($t[3] !== '#') target="_blank" rel="noopener noreferrer" @endif
                       class="kt-chip bkl-reveal"
                       style="--i:{{ $n % 3 }}">
                        <span class="kt-chip-go"><i class="fas fa-arrow-right"></i></span>
                        <span class="kt-chip-emoji" aria-hidden="true">{{ $t[0] }}</span>
                        <b>{{ $t[1] }}</b>
                        <small>{{ $t[2] }}</small>
                    </a>
                @endforeach
            </div>

        </section>


        {{-- ===================== INFORMASI + PETA ===================== --}}
        <div class="kt-split">

            <div class="kt-card bkl-reveal">

                <span class="bkl-kicker">VISIT US</span>
                <h2 class="kt-title">Mampir ke salon yuk</h2>
                <p class="kt-sub">Kunjungi kami atau hubungi pada jam operasional.</p>

                <ul class="kt-list" id="ktJam">
                    <li class="is-alamat">
                        <span>Alamat</span>
                        <strong>{{ $alamat }}</strong>
                    </li>

                    @foreach ($jam as $i => $baris)
                        <li class="kt-hari" data-row="{{ $i }}">
                            <span>{{ $baris['label'] }}</span>
                            <strong>{{ $tampilJam($baris['buka']) }} - {{ $tampilJam($baris['tutup']) }} WIB</strong>
                        </li>
                    @endforeach

                    <li>
                        <span>Instagram</span>
                        <strong>{{ $instagram }}</strong>
                    </li>
                </ul>

                <div class="kt-cta">
                    <a href="{{ $linkWa }}" target="_blank" rel="noopener" class="bkl-btn bkl-btn-wa">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                    </a>

                    <a href="{{ $urlPetunjuk }}" target="_blank" rel="noopener noreferrer" class="bkl-btn bkl-btn-line">
                        <i class="fas fa-directions"></i> Petunjuk Arah
                    </a>

                    <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-maroon">
                        <i class="fas fa-calendar-check"></i> Buat Booking
                    </a>
                </div>

                <div class="kt-copy-row">
                    <button type="button" class="bkl-btn bkl-btn-line bkl-btn-sm" id="salinAlamat" data-alamat="{{ $alamat }}">
                        <i class="far fa-copy"></i> <span>Salin alamat</span>
                    </button>
                </div>

            </div>

            <div class="kt-map bkl-reveal">
                <iframe
                    src="{{ $urlPeta }}"
                    title="Peta {{ $namaSalon }} Yogyakarta"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>

        </div>


        {{-- ===================== FAQ ===================== --}}
        <section class="kt-section" style="margin-top:64px">

            <div class="kt-head bkl-reveal">
                <span class="bkl-kicker">FAQ</span>
                <h2 class="kt-title">Sering ditanyain nih</h2>
                <p class="kt-sub">Mungkin jawabannya ada di sini, jadi nggak perlu nunggu balasan 😉</p>
            </div>

            <div class="kt-faq">

                <details class="bkl-reveal">
                    <summary>Jam bukanya kapan sih?</summary>
                    <div>{{ $ringkasJam }}.</div>
                </details>

                <details class="bkl-reveal">
                    <summary>Gimana cara booking?</summary>
                    <div>
                        Gampang banget. Klik <a href="{{ route('booking.create') }}">Buat Booking</a>, pilih perawatan,
                        terapis, dan jadwal. Kami cek bentrok jadwal otomatis sebelum booking disimpan.
                    </div>
                </details>

                <details class="bkl-reveal">
                    <summary>Habis booking, harus ngapain?</summary>
                    <div>
                        Kirim bukti pembayaran dalam waktu 2 jam ya. Booking tanpa bukti pembayaran akan dibatalkan otomatis.
                        Kode booking bisa dipakai buat cek status di halaman
                        <a href="{{ route('booking.lacak') }}">Lacak Booking</a>.
                    </div>
                </details>

                <details class="bkl-reveal">
                    <summary>Boleh batal atau ganti jadwal?</summary>
                    <div>
                        Boleh! Pembatalan lewat sistem bisa dilakukan paling lambat 3 jam sebelum jadwal.
                        Kalau waktunya sudah lebih dekat, chat kami lewat WhatsApp ya.
                    </div>
                </details>

                <details class="bkl-reveal">
                    <summary>Salonnya khusus siapa?</summary>
                    <div>
                        Khusus perempuan muslimah. Ruang perawatannya dijaga supaya privasi dan kenyamananmu tetap aman 🤍
                    </div>
                </details>

            </div>

        </section>


        {{-- ===================== AJAKAN AKHIR ===================== --}}
        <section class="kt-band bkl-reveal">

            <span class="kt-band-emoji e1" aria-hidden="true">🌸</span>
            <span class="kt-band-emoji e2" aria-hidden="true">✨</span>
            <span class="kt-band-emoji e3" aria-hidden="true">💖</span>

            <h2>Udah siap me-time? Yuk booking sekarang!</h2>

            <p>Pilih perawatan, pilih jadwal, selesai. Sisanya biar kami yang urus.</p>

            <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                Buat Booking
                <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
            </a>

        </section>

    </div>

</main>

{{-- tombol WhatsApp melayang --}}
<a href="{{ $linkWa }}" target="_blank" rel="noopener" class="kt-fab" aria-label="Chat WhatsApp">
    <span class="kt-fab-tip">Chat yuk! 👋</span>
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
</a>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var tombol = document.getElementById('salinAlamat');
    if (!tombol) return;

    var label = tombol.querySelector('span');
    var ikon  = tombol.querySelector('i');
    var awal  = label.textContent;
    var timer;

    function berhasil() {
        ikon.className = 'fas fa-check';
        label.textContent = 'Alamat tersalin';

        clearTimeout(timer);
        timer = setTimeout(function () {
            ikon.className = 'far fa-copy';
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

    tombol.addEventListener('click', function () {
        var teks = tombol.getAttribute('data-alamat');

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(teks).then(berhasil, function () { cadangan(teks); });
        } else {
            cadangan(teks);
        }
    });
});
</script>

{{-- efek tambahan: kata berganti, status buka/tutup, hari ini, konfeti --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    var JAM = @json($jam);
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- kata berganti di judul: efek mengetik ---------- */
    var rot = document.getElementById('ktRot');

    if (rot && !reduce) {
        var kata = [];
        try { kata = JSON.parse(rot.getAttribute('data-words')) || []; } catch (e) {}

        if (kata.length > 1) {
            var urut  = 0;                    // kata yang sedang diketik
            var huruf = kata[0].length;       // mulai dari kata pertama yang sudah tampil
            var hapus = true;                 // jadi langkah pertama adalah menghapusnya

            function ketik() {
                var kataIni = kata[urut];

                rot.classList.add('is-typing');

                if (!hapus) {
                    huruf++;
                    rot.textContent = kataIni.slice(0, huruf);

                    if (huruf === kataIni.length) {
                        hapus = true;
                        rot.classList.remove('is-typing');
                        return setTimeout(ketik, 1800);                 // tahan kata utuh
                    }
                    return setTimeout(ketik, 80 + Math.random() * 70);  // ritme ketik agak acak
                }

                huruf--;
                rot.textContent = kataIni.slice(0, huruf);

                if (huruf <= 0) {
                    hapus = false;
                    urut  = (urut + 1) % kata.length;
                    rot.classList.remove('is-typing');
                    return setTimeout(ketik, 320);                      // jeda sebelum kata baru
                }
                return setTimeout(ketik, 45);
            }

            setTimeout(ketik, 1800);
        }
    }

    /* ---------- status buka / tutup (waktu Yogyakarta) ---------- */
    var live = document.getElementById('ktLive');
    var liveTeks = document.getElementById('ktLiveText');
    var HARI = { Sun: 0, Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6 };

    function menit(jamStr) {
        var p = String(jamStr).split(':');
        return parseInt(p[0], 10) * 60 + parseInt(p[1], 10);
    }

    function format(jamStr) { return String(jamStr).replace(':', '.'); }

    function barisUntuk(hari) {
        if (hari === 0) return 2;
        if (hari === 6) return 1;
        return 0;
    }

    function sekarang() {
        var bagian = new Intl.DateTimeFormat('en-US', {
            timeZone: 'Asia/Jakarta', weekday: 'short', hour: '2-digit', minute: '2-digit', hourCycle: 'h23'
        }).formatToParts(new Date());

        var o = {};
        bagian.forEach(function (b) { o[b.type] = b.value; });

        return { hari: HARI[o.weekday], menit: parseInt(o.hour, 10) * 60 + parseInt(o.minute, 10) };
    }

    function perbarui() {
        var s = sekarang();
        var baris = JAM[barisUntuk(s.hari)];
        if (!baris) return;

        var buka = menit(baris.buka);
        var tutup = menit(baris.tutup);

        document.querySelectorAll('#ktJam .kt-hari').forEach(function (li) {
            li.classList.toggle('is-today', parseInt(li.getAttribute('data-row'), 10) === barisUntuk(s.hari));
        });

        if (!live || !liveTeks) return;

        if (s.menit >= buka && s.menit < tutup) {
            var sisa = tutup - s.menit;
            var teks = sisa >= 60 ? Math.floor(sisa / 60) + ' jam ' + (sisa % 60 ? (sisa % 60) + ' menit ' : '') : sisa + ' menit ';
            live.setAttribute('data-state', 'buka');
            liveTeks.textContent = 'Buka sekarang · tutup ' + format(baris.tutup) + ' WIB (' + teks.trim() + ' lagi)';
        } else if (s.menit < buka) {
            live.setAttribute('data-state', 'tutup');
            liveTeks.textContent = 'Belum buka · buka jam ' + format(baris.buka) + ' WIB';
        } else {
            var besok = JAM[barisUntuk((s.hari + 1) % 7)] || baris;
            live.setAttribute('data-state', 'tutup');
            liveTeks.textContent = 'Udah tutup · buka lagi besok jam ' + format(besok.buka) + ' WIB';
        }
    }

    perbarui();
    setInterval(perbarui, 30000);

    /* ---------- konfeti saat salin alamat ---------- */
    function ledakan(el) {
        if (reduce || !el.animate) return;

        var r = el.getBoundingClientRect();
        var emoji = ['✨', '💖', '🎀', '🌸', '✦', '🤍'];

        for (var i = 0; i < 16; i++) {
            var c = document.createElement('span');
            c.className = 'kt-confetti';
            c.textContent = emoji[Math.floor(Math.random() * emoji.length)];
            c.style.left = (r.left + r.width / 2) + 'px';
            c.style.top = (r.top + r.height / 2) + 'px';
            document.body.appendChild(c);

            var sudut = Math.random() * Math.PI * 2;
            var jarak = 60 + Math.random() * 90;

            c.animate([
                { transform: 'translate(-50%, -50%) scale(.4) rotate(0deg)', opacity: 1 },
                { transform: 'translate(calc(-50% + ' + (Math.cos(sudut) * jarak) + 'px), calc(-50% + ' + (Math.sin(sudut) * jarak - 30) + 'px)) scale(1.1) rotate(' + (Math.random() * 360) + 'deg)', opacity: 0 }
            ], { duration: 900 + Math.random() * 500, easing: 'cubic-bezier(.2,.8,.3,1)' }).onfinish = (function (n) {
                return function () { n.remove(); };
            })(c);
        }
    }

    var salin = document.getElementById('salinAlamat');
    if (salin) { salin.addEventListener('click', function () { ledakan(salin); }); }
});
</script>

@endsection