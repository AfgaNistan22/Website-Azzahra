@extends('pages.auth.guest')

@section('title', 'Promo | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman promo --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/promo-modern.css') }}?v={{ file_exists(public_path('template/css/promo-modern.css')) ? filemtime(public_path('template/css/promo-modern.css')) : time() }}">
@endpush

@section('content')

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
                    Promo
                    <em>Spesial.</em>
                </h1>

                <p>
                    Penawaran dan diskon terbaru dari Azzahra Salon &amp; Spa.
                    Manfaatkan sebelum periodenya berakhir.
                </p>

                <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                    <i class="far fa-calendar-check" aria-hidden="true"></i>
                    Booking Sekarang
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-tags"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Promo • Diskon • Penawaran</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        @if(isset($promo) && $promo->count() > 0)

            <div class="pr-head bkl-reveal">
                <span class="bkl-kicker">Penawaran</span>
                <h2 class="pr-title">Promo yang sedang berlangsung</h2>
                <p class="pr-sub">{{ $promo->count() }} promo aktif siap kamu nikmati.</p>
            </div>

            <div class="pr-grid">

                @foreach($promo as $item)

                    @php
                        $mulai   = $item->tanggal_mulai ?? null;
                        $selesai = $item->tanggal_selesai ?? null;

                        // Label diskon: persen bila ada, nominal bila tidak
                        if (!empty($item->diskon_persen)) {
                            $label = (float) $item->diskon_persen . '% OFF';
                        } elseif (!empty($item->diskon_nominal)) {
                            $label = 'Hemat Rp' . number_format($item->diskon_nominal, 0, ',', '.');
                        } else {
                            $label = 'PROMO';
                        }

                        $foto = $item->foto ?? null;

                        // sisa hari (hanya untuk penanda "segera berakhir")
                        $sisaHari = $selesai
                            ? \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($selesai)->startOfDay(), false)
                            : null;
                    @endphp

                    <article class="pr-card bkl-reveal">

                        <div class="pr-img">
                            <span class="pr-badge">{{ $label }}</span>

                            @if($sisaHari !== null && $sisaHari >= 0 && $sisaHari <= 7)
                                <span class="pr-soon"><i class="fas fa-fire" aria-hidden="true"></i> {{ $sisaHari === 0 ? 'Hari terakhir' : $sisaHari . ' hari lagi' }}</span>
                            @endif

                            @if($foto)
                                <img src="{{ asset('storage/' . (\Illuminate\Support\Str::contains($foto, '/') ? $foto : 'foto_promo/' . $foto)) }}"
                                     alt="{{ $item->nama }}" loading="lazy">
                            @else
                                <div class="pr-ph"><i class="fas fa-tags"></i></div>
                            @endif
                        </div>

                        <div class="pr-body">
                            <h3>{{ $item->nama }}</h3>

                            @if(!empty($item->deskripsi))
                                <p class="pr-desc">{{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}</p>
                            @endif

                            <div class="pr-foot">
                                <span class="pr-date">
                                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    @if($selesai)
                                        s/d {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d M Y') }}
                                    @else
                                        Tanpa batas waktu
                                    @endif
                                </span>

                                <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-maroon pr-btn">
                                    Pesan
                                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="pr-empty bkl-reveal">
                <i class="fas fa-tags" aria-hidden="true"></i>
                <h3>Belum ada promo aktif</h3>
                <p>Promo terbaru akan tampil di sini. Sementara itu, lihat daftar perawatan kami.</p>
                <a href="{{ route('perawatan-layanan.index') }}" class="bkl-btn bkl-btn-maroon">
                    <i class="fas fa-spa" aria-hidden="true"></i> Lihat Perawatan
                </a>
            </div>

        @endif

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

@endsection