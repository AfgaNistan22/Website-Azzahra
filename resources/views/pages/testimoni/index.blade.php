@extends('pages.auth.guest')

@section('title', 'Testimoni | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman testimoni --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">

    <style>
        /* =========================================================
           TESTIMONI, pelengkap tema maroon. Prefix: ts-
           Memakai variabel dari booking-list-modern.css
        ========================================================= */

        .ts-gold { --gold: #D9A441; }

        .ts-head { max-width: 640px; margin-bottom: 28px; }
        .ts-title { margin: 0 0 10px; color: var(--deep); font-family: var(--display); font-size: clamp(26px, 3.4vw, 36px); font-weight: 600; letter-spacing: -.01em; line-height: 1.15; }
        .ts-sub { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.7; }
        .ts-section { margin-bottom: 56px; scroll-margin-top: 90px; }

        .ts-hero-btns { display: flex; flex-wrap: wrap; gap: 12px; }
        .bkl-page .bkl-btn-ghost { border: 1px solid rgba(255, 255, 255, .4); background: rgba(255, 255, 255, .08); color: #fff; -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
        .bkl-page .bkl-btn-ghost:hover { background: rgba(255, 255, 255, .2); color: #fff; transform: translateY(-3px); }
        .bkl-page .bkl-btn-line { border: 1px solid var(--line-strong); background: #fff; color: var(--maroon); }
        .bkl-page .bkl-btn-line:hover { background: var(--maroon); border-color: var(--maroon); color: #fff; transform: translateY(-3px); }
        .bkl-page .bkl-btn-sm { height: 40px; padding: 0 18px; font-size: 12.5px; }

        /* ---------- bintang ---------- */
        .ts-row i { color: #E6D9DC; }
        .ts-row i.on { color: #D9A441; }
        .ts-row i.pop { animation: ts-star-pop .5s var(--ease) both; animation-delay: calc(var(--k, 0) * 70ms); }
        @keyframes ts-star-pop { from { transform: scale(.2) rotate(-40deg); opacity: 0; } to { transform: none; opacity: 1; } }

        /* ---------- kartu skor di hero ---------- */
        .ts-score {
            position: relative; justify-self: end; width: min(330px, 100%); padding: 30px 28px 26px; text-align: center;
            border: 1px solid rgba(255, 255, 255, .25); border-radius: 30px; background: rgba(255, 255, 255, .12);
            -webkit-backdrop-filter: blur(14px); backdrop-filter: blur(14px); color: #fff; box-shadow: 0 30px 50px -26px rgba(0, 0, 0, .55);
            animation: bkl-rise 1s .25s var(--ease) both;
        }
        .ts-score-label { display: block; color: #F0CBD6; font-size: 11px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; }
        .ts-score-num { display: block; margin: 8px 0 4px; font-family: var(--display); font-size: 78px; font-weight: 600; line-height: 1; letter-spacing: -.02em; }
        .ts-score .ts-row { margin-bottom: 10px; font-size: 20px; letter-spacing: .08em; }
        .ts-score .ts-row i { color: rgba(255, 255, 255, .28); }
        .ts-score .ts-row i.on { color: #F6C65B; }
        .ts-score small { color: rgba(255, 255, 255, .82); font-size: 13.5px; }
        .ts-score-spark { position: absolute; color: #F0CBD6; font-size: 20px; animation: ts-twinkle 3.2s ease-in-out infinite; }
        .ts-score-spark.s1 { left: -14px; top: 22px; }
        .ts-score-spark.s2 { right: -10px; top: -12px; animation-delay: -1.2s; font-size: 28px; }
        .ts-score-spark.s3 { right: 24px; bottom: -14px; animation-delay: -2.2s; }
        @keyframes ts-twinkle { 0%, 100% { transform: scale(.7) rotate(0); opacity: .45; } 50% { transform: scale(1.2) rotate(45deg); opacity: 1; } }

        /* ---------- pemberitahuan ---------- */
        .ts-flash { display: flex; align-items: center; gap: 12px; margin-bottom: 26px; padding: 16px 20px; border: 1px solid #BFE3CC; border-radius: 18px; background: #EAF6EE; color: #17603A; font-size: 14px; font-weight: 600; animation: bkl-drop .5s var(--ease) both; }
        .ts-flash i { font-size: 18px; }
        .ts-alert { margin-bottom: 18px; padding: 14px 18px; border: 1px solid #F0C1C8; border-radius: 16px; background: #FDEFF2; color: #8A1B2E; font-size: 13.5px; line-height: 1.7; }
        .ts-alert ul { margin: 0; padding-left: 18px; }

        /* ---------- ringkasan ---------- */
        .ts-summary { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 36px; align-items: center; padding: 34px; border: 1px solid var(--line); border-radius: 30px; background: #fff; box-shadow: var(--shadow); }
        .ts-big { text-align: center; }
        .ts-big b { display: block; color: var(--deep); font-family: var(--display); font-size: 68px; font-weight: 600; line-height: 1; }
        .ts-big .ts-row { margin: 10px 0 6px; font-size: 20px; letter-spacing: .08em; }
        .ts-big small { color: var(--muted); font-size: 13.5px; }

        .ts-bars { display: grid; gap: 10px; }
        .ts-bar { display: grid; grid-template-columns: 38px minmax(0, 1fr) 34px; align-items: center; gap: 12px; color: var(--deep); font-size: 13px; font-weight: 700; }
        .ts-bar span:first-child { white-space: nowrap; }
        .ts-bar span:first-child i { color: #D9A441; font-size: 11px; }
        .ts-bar em { display: block; height: 10px; overflow: hidden; border-radius: 999px; background: var(--tint); }
        .ts-bar em i { display: block; width: var(--w, 0%); height: 100%; border-radius: inherit; background: var(--grad); transition: width 1.1s var(--ease); transition-delay: calc(var(--k, 0) * 90ms); }
        .ts-js .ts-bars:not(.is-in) .ts-bar em i { width: 0; }
        .ts-bar span:last-child { color: var(--muted); font-weight: 600; text-align: right; }

        /* ---------- ulasan ---------- */
        .ts-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 26px; }
        .ts-chip {
            display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px; border: 1px solid var(--line-strong); border-radius: 999px; background: #fff; color: var(--maroon);
            font-size: 13px; font-weight: 700; text-decoration: none; transition: transform .3s var(--ease), background .25s ease, color .25s ease, border-color .25s ease;
        }
        .ts-chip i { color: #D9A441; font-size: 11px; }
        .ts-chip:hover { transform: translateY(-2px); color: var(--maroon); border-color: var(--maroon); }
        .ts-chip.is-active { border-color: transparent; background: var(--grad); color: #fff; }
        .ts-chip.is-active i { color: #FFE3A3; }
        .ts-chip:focus-visible { outline: 2px solid var(--maroon-2); outline-offset: 3px; }

        .ts-masonry { column-count: 3; column-gap: 20px; }
        .ts-card {
            position: relative; display: inline-flex; flex-direction: column; gap: 14px; width: 100%; margin: 0 0 20px; padding: 24px 24px 20px; break-inside: avoid;
            border: 1px solid var(--line); border-radius: 26px; background: #fff; box-shadow: var(--shadow);
            transition: transform .45s var(--ease), border-color .3s ease, box-shadow .45s var(--ease);
        }
        .ts-card:hover { transform: translateY(-5px); border-color: var(--line-strong); box-shadow: var(--shadow-hover); }
        .ts-card.is-mine { border-color: #D9A441; box-shadow: 0 0 0 3px rgba(217, 164, 65, .18), var(--shadow); }
        .ts-card::before { content: "“"; position: absolute; right: 20px; top: 2px; color: var(--tint); font-family: var(--display); font-size: 90px; line-height: 1; pointer-events: none; }

        .ts-who { position: relative; display: flex; align-items: center; gap: 12px; }
        .ts-avatar { position: relative; display: grid; flex-shrink: 0; width: 46px; height: 46px; place-items: center; overflow: hidden; border-radius: 50%; background: var(--grad); color: #fff; font-family: var(--display); font-size: 18px; font-weight: 600; }
        .ts-avatar img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .ts-who h3 { margin: 0; color: var(--deep); font-family: var(--display); font-size: 16.5px; font-weight: 600; line-height: 1.3; }
        .ts-who time { color: var(--muted); font-size: 12px; }
        .ts-card .ts-row { font-size: 14px; letter-spacing: .1em; }
        .ts-text { margin: 0; color: #5a4a4f; font-size: 14.5px; line-height: 1.85; overflow-wrap: anywhere; }
        .ts-foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 28px; }
        .ts-mine { padding: 4px 11px; border-radius: 999px; background: #FBF1DA; color: #8A6212; font-size: 10.5px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }
        .ts-del { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border: 0; border-radius: 999px; background: transparent; color: #B4233A; font-size: 12px; font-weight: 700; cursor: pointer; transition: background .25s ease; }
        .ts-del:hover { background: #FDEFF2; }
        .ts-del:focus-visible { outline: 2px solid #B4233A; outline-offset: 2px; }

        .ts-empty { padding: 54px 24px; border: 1px dashed var(--line-strong); border-radius: 28px; background: #fff; text-align: center; }
        .ts-empty i { display: grid; width: 64px; height: 64px; place-items: center; margin: 0 auto 16px; border-radius: 50%; background: var(--tint); color: var(--maroon); font-size: 24px; animation: bkl-breathe 5s ease-in-out infinite; }
        .ts-empty h3 { margin: 0 0 6px; color: var(--deep); font-family: var(--display); font-size: 22px; font-weight: 600; }
        .ts-empty p { margin: 0 auto; max-width: 420px; color: var(--muted); font-size: 14.5px; line-height: 1.7; }

        .ts-pager { display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 10px; color: var(--muted); font-size: 13px; font-weight: 600; }
        .ts-pager .is-off { opacity: .4; pointer-events: none; }

        /* ---------- form ulasan ---------- */
        .ts-form { display: grid; grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr); gap: 34px; padding: 38px; border-radius: 32px; background: var(--deep); color: #fff; position: relative; isolation: isolate; overflow: hidden; }
        .ts-form::before { content: ""; position: absolute; z-index: -1; inset: -30%; background: radial-gradient(40% 44% at 14% 26%, rgba(168, 71, 106, .65), transparent 70%), radial-gradient(34% 38% at 88% 22%, rgba(124, 36, 65, .75), transparent 70%); filter: blur(30px); animation: bkl-drift 16s ease-in-out infinite alternate; }
        .ts-form h2 { margin: 0 0 10px; color: #fff; font-family: var(--display); font-size: clamp(24px, 3.2vw, 34px); font-weight: 600; line-height: 1.2; }
        .ts-form p { margin: 0; color: rgba(255, 255, 255, .8); font-size: 14.5px; line-height: 1.8; }
        .ts-as { display: inline-flex; align-items: center; gap: 8px; margin-top: 18px; padding: 8px 14px; border: 1px solid rgba(255, 255, 255, .25); border-radius: 999px; background: rgba(255, 255, 255, .1); font-size: 12.5px; font-weight: 600; }
        .ts-fields { display: grid; gap: 18px; align-content: start; }
        .ts-fields label.ts-lbl, .ts-fields legend { margin: 0 0 8px; padding: 0; color: #F0CBD6; font-size: 11.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
        .ts-fields fieldset { margin: 0; padding: 0; border: 0; min-width: 0; }

        .ts-pick { position: relative; display: inline-flex; flex-direction: row-reverse; justify-content: flex-end; gap: 6px; }
        .ts-pick input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .ts-pick label { margin: 0; color: rgba(255, 255, 255, .3); font-size: 34px; line-height: 1; cursor: pointer; transition: color .2s ease, transform .3s var(--ease); }
        .ts-pick label:hover { transform: scale(1.2) rotate(-8deg); }
        .ts-pick label:hover, .ts-pick label:hover ~ label, .ts-pick input:checked ~ label { color: #F6C65B; }
        .ts-pick input:focus-visible + label { outline: 2px solid #fff; outline-offset: 4px; border-radius: 6px; }
        .ts-hint { display: inline-block; margin-left: 14px; color: #F6C65B; font-size: 13.5px; font-weight: 700; vertical-align: 8px; min-height: 1.2em; }

        .ts-area { width: 100%; min-height: 130px; padding: 16px 18px; resize: vertical; border: 1px solid rgba(255, 255, 255, .28); border-radius: 18px; background: rgba(255, 255, 255, .1); color: #fff; font: 500 14.5px/1.7 'DM Sans', system-ui, sans-serif; transition: border-color .25s ease, background .25s ease; }
        .ts-area::placeholder { color: rgba(255, 255, 255, .55); }
        .ts-area:focus { outline: none; border-color: #F0CBD6; background: rgba(255, 255, 255, .16); }
        .ts-count { margin-top: 6px; color: rgba(255, 255, 255, .65); font-size: 12px; text-align: right; }
        .ts-form .ts-alert { margin: 0; }
        .ts-form-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 14px; }
        .ts-form-actions small { color: rgba(255, 255, 255, .7); font-size: 12.5px; }
        .ts-login { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 4px; }

        /* ---------- Google ---------- */
        .ts-google {
            display: flex; align-items: center; gap: 18px; padding: 22px 26px; border: 1px solid var(--line); border-radius: 26px; background: #fff; color: var(--deep); text-decoration: none; box-shadow: var(--shadow);
            transition: transform .45s var(--ease), border-color .3s ease, box-shadow .45s var(--ease);
        }
        .ts-google:hover { transform: translateY(-5px); border-color: var(--line-strong); box-shadow: var(--shadow-hover); color: var(--deep); }
        .ts-google:focus-visible { outline: 2px solid var(--maroon-2); outline-offset: 3px; }
        .ts-g-logo { display: grid; flex-shrink: 0; width: 56px; height: 56px; place-items: center; border: 1px solid var(--line-strong); border-radius: 50%; background: #fff; color: #4285F4; font-family: 'DM Sans', system-ui, sans-serif; font-size: 28px; font-weight: 800; }
        .ts-g-info { display: flex; flex: 1; flex-direction: column; min-width: 0; gap: 3px; }
        .ts-g-info b { font-family: var(--display); font-size: 20px; font-weight: 600; }
        .ts-g-info small { color: var(--muted); font-size: 13.5px; line-height: 1.55; }
        .ts-g-go { display: grid; flex-shrink: 0; width: 42px; height: 42px; place-items: center; border-radius: 50%; background: var(--tint); color: var(--maroon); font-size: 14px; transition: background .3s ease, color .3s ease, transform .5s var(--ease); }
        .ts-google:hover .ts-g-go { background: var(--maroon); color: #fff; transform: rotate(45deg); }

        /* ---------- responsif ---------- */
        @media (max-width: 1020px) {
            .ts-score { display: none; }
            .ts-masonry { column-count: 2; }
            .ts-form { grid-template-columns: 1fr; gap: 26px; padding: 32px 26px; }
        }
        @media (max-width: 760px) {
            .ts-summary { grid-template-columns: 1fr; gap: 26px; padding: 26px 22px; }
        }
        @media (max-width: 640px) {
            .ts-masonry { column-count: 1; }
            .ts-google { padding: 18px; gap: 14px; }
            .ts-g-logo { width: 46px; height: 46px; font-size: 23px; }
            .ts-hero-btns .bkl-btn, .ts-form-actions .bkl-btn { width: 100%; }
            .ts-pick label { font-size: 30px; }
            .ts-hint { display: block; margin: 6px 0 0; vertical-align: baseline; }
        }
        @media (prefers-reduced-motion: reduce) {
            .ts-score, .ts-score-spark, .ts-empty i, .ts-form::before, .ts-flash, .ts-row i.pop { animation: none; }
            .ts-card, .ts-google, .ts-g-go, .ts-chip, .ts-bar em i, .ts-pick label { transition: none; }
        }
    </style>
@endpush

@section('content')

@php
    $gmaps = 'https://www.google.com/maps/search/?api=1&query=Azzahra+Salon+%26+Spa+Muslimah+Yogyakarta';

    $pengguna = auth()->user();

    // nama ditampilkan singkat demi privasi: "Siti A."
    $namaSingkat = function ($u) {
        $bagian = preg_split('/\s+/', trim($u->name ?? 'Pelanggan')) ?: ['Pelanggan'];
        return count($bagian) > 1
            ? $bagian[0] . ' ' . mb_strtoupper(mb_substr(end($bagian), 0, 1)) . '.'
            : $bagian[0];
    };

    $inisial = fn ($u) => mb_strtoupper(mb_substr(trim($u->name ?? 'P'), 0, 1));

    $fotoUrl = fn ($u) => ($u && !empty($u->photo)) ? \Illuminate\Support\Facades\Storage::url($u->photo) : null;

    $bintangBulat = (int) round($rata);

    $kataHint = [1 => 'Kurang puas', 2 => 'Biasa saja', 3 => 'Lumayan', 4 => 'Puas banget', 5 => 'Mantap, juara!'];
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
                    CERITA PELANGGAN
                </span>

                <h1>
                    Kepercayaan yang
                    <em>Berarti.</em>
                </h1>

                <p>
                    Baca pengalaman pelanggan Azzahra, lalu ceritakan pengalamanmu.
                    Cukup punya akun, ulasanmu langsung tampil di sini.
                </p>

                <div class="ts-hero-btns">
                    <a href="#tulis" class="bkl-btn bkl-btn-light">
                        <i class="fas fa-pen" aria-hidden="true"></i>
                        {{ $milikku ? 'Ubah Ulasanmu' : 'Tulis Ulasan' }}
                        <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                    </a>

                    <a href="{{ $gmaps }}" target="_blank" rel="noopener noreferrer" class="bkl-btn bkl-btn-ghost">
                        <i class="fab fa-google" aria-hidden="true"></i>
                        Ulasan di Google
                    </a>
                </div>

            </div>

            {{-- kartu skor --}}
            <div class="ts-score" role="group" aria-label="Rating rata-rata pelanggan">
                <span class="ts-score-spark s1" aria-hidden="true">✦</span>
                <span class="ts-score-spark s2" aria-hidden="true">✧</span>
                <span class="ts-score-spark s3" aria-hidden="true">✦</span>

                <span class="ts-score-label">Rating pelanggan</span>

                @if ($total > 0)
                    <b class="ts-score-num" data-count="{{ $rata }}">{{ number_format($rata, 1, ',', '.') }}</b>

                    <div class="ts-row" aria-hidden="true">
                        @for ($s = 1; $s <= 5; $s++)
                            <i class="fas fa-star {{ $s <= $bintangBulat ? 'on' : '' }}"></i>
                        @endfor
                    </div>

                    <small>dari {{ number_format($total, 0, ',', '.') }} ulasan</small>
                @else
                    <b class="ts-score-num">–</b>
                    <div class="ts-row" aria-hidden="true">
                        @for ($s = 1; $s <= 5; $s++) <i class="fas fa-star"></i> @endfor
                    </div>
                    <small>Jadilah yang pertama mengulas</small>
                @endif
            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content">

        @if (session('success'))
            <div class="ts-flash" role="status">
                <i class="fas fa-circle-check" aria-hidden="true"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- ===================== RINGKASAN ===================== --}}
        @if ($total > 0)
            <section class="ts-section">

                <div class="ts-summary bkl-reveal">

                    <div class="ts-big">
                        <b data-count="{{ $rata }}">{{ number_format($rata, 1, ',', '.') }}</b>
                        <div class="ts-row" aria-hidden="true">
                            @for ($s = 1; $s <= 5; $s++)
                                <i class="fas fa-star {{ $s <= $bintangBulat ? 'on' : '' }}"></i>
                            @endfor
                        </div>
                        <small>{{ number_format($total, 0, ',', '.') }} ulasan dari pelanggan</small>
                    </div>

                    <div class="ts-bars" id="tsBars">
                        @foreach ([5, 4, 3, 2, 1] as $k => $n)
                            @php
                                $jml = (int) ($sebaran[$n] ?? 0);
                                $pct = $total ? round($jml / $total * 100) : 0;
                            @endphp
                            <div class="ts-bar">
                                <span>{{ $n }} <i class="fas fa-star" aria-hidden="true"></i></span>
                                <em role="img" aria-label="{{ $pct }} persen memberi {{ $n }} bintang"><i style="--w: {{ $pct }}%; --k: {{ $k }}"></i></em>
                                <span>{{ $jml }}</span>
                            </div>
                        @endforeach
                    </div>

                </div>

            </section>
        @endif


        {{-- ===================== DAFTAR ULASAN ===================== --}}
        <section class="ts-section" id="daftar-ulasan">

            <div class="ts-head bkl-reveal">
                <span class="bkl-kicker">ULASAN PELANGGAN</span>
                <h2 class="ts-title">Kata mereka tentang Azzahra</h2>
                <p class="ts-sub">Ditulis langsung oleh pemilik akun Azzahra.</p>
            </div>

            @if ($total > 0)
                <nav class="ts-chips bkl-reveal" aria-label="Saring ulasan berdasarkan bintang">
                    <a href="{{ route('testimoni.index') }}#daftar-ulasan" class="ts-chip {{ $bintang ? '' : 'is-active' }}" @if (!$bintang) aria-current="true" @endif>Semua</a>

                    @foreach ([5, 4, 3, 2, 1] as $n)
                        @if (($sebaran[$n] ?? 0) > 0 || $bintang === $n)
                            <a href="{{ route('testimoni.index', ['bintang' => $n]) }}#daftar-ulasan"
                               class="ts-chip {{ $bintang === $n ? 'is-active' : '' }}"
                               @if ($bintang === $n) aria-current="true" @endif>
                                {{ $n }} <i class="fas fa-star" aria-hidden="true"></i>
                            </a>
                        @endif
                    @endforeach
                </nav>
            @endif

            @if ($daftar->count() > 0)

                <div class="ts-masonry">
                    @foreach ($daftar as $t)
                        @php
                            $punyaSaya = $pengguna && $pengguna->id === $t->user_id;
                            $bolehHapus = $pengguna && ($punyaSaya || $pengguna->isAdmin());
                            $foto = $fotoUrl($t->user);
                        @endphp

                        <article class="ts-card bkl-reveal {{ $punyaSaya ? 'is-mine' : '' }}" style="--i: {{ $loop->index % 3 }}">

                            <div class="ts-who">
                                <span class="ts-avatar" aria-hidden="true">
                                    {{ $t->user ? $inisial($t->user) : 'P' }}
                                    @if ($foto)
                                        <img src="{{ $foto }}" alt="" loading="lazy" onerror="this.remove()">
                                    @endif
                                </span>
                                <div>
                                    <h3>{{ $t->user ? $namaSingkat($t->user) : 'Pelanggan' }}</h3>
                                    <time datetime="{{ $t->created_at->toIso8601String() }}">{{ $t->created_at->locale('id')->diffForHumans() }}</time>
                                </div>
                            </div>

                            <div class="ts-row" role="img" aria-label="{{ $t->rating }} dari 5 bintang">
                                @for ($s = 1; $s <= 5; $s++)
                                    <i class="fas fa-star {{ $s <= $t->rating ? 'on' : '' }}"></i>
                                @endfor
                            </div>

                            <p class="ts-text">{!! nl2br(e($t->komentar)) !!}</p>

                            @if ($punyaSaya || $bolehHapus)
                                <div class="ts-foot">
                                    @if ($punyaSaya)
                                        <span class="ts-mine">Ulasanmu</span>
                                    @else
                                        <span></span>
                                    @endif

                                    @if ($bolehHapus)
                                        <form action="{{ route('testimoni.destroy', $t->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus ulasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ts-del">
                                                <i class="far fa-trash-alt" aria-hidden="true"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                        </article>
                    @endforeach
                </div>

                @if ($daftar->hasPages())
                    <div class="ts-pager">
                        <a href="{{ $daftar->previousPageUrl() ?: '#' }}#daftar-ulasan"
                           class="bkl-btn bkl-btn-line bkl-btn-sm {{ $daftar->onFirstPage() ? 'is-off' : '' }}"
                           @if ($daftar->onFirstPage()) aria-disabled="true" tabindex="-1" @endif>
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> Sebelumnya
                        </a>

                        <span>Halaman {{ $daftar->currentPage() }} dari {{ $daftar->lastPage() }}</span>

                        <a href="{{ $daftar->nextPageUrl() ?: '#' }}#daftar-ulasan"
                           class="bkl-btn bkl-btn-line bkl-btn-sm {{ $daftar->hasMorePages() ? '' : 'is-off' }}"
                           @if (!$daftar->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>
                            Berikutnya <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif

            @else

                <div class="ts-empty bkl-reveal">
                    <i class="far fa-comment-dots" aria-hidden="true"></i>
                    <h3>{{ $bintang ? 'Belum ada ulasan dengan bintang ini' : 'Belum ada ulasan' }}</h3>
                    <p>{{ $bintang ? 'Coba pilih jumlah bintang lain, atau lihat semua ulasan.' : 'Jadilah yang pertama bercerita tentang pengalamanmu di Azzahra.' }}</p>
                </div>

            @endif

        </section>


        {{-- ===================== TULIS ULASAN ===================== --}}
        <section class="ts-section" id="tulis">

            <div class="ts-form bkl-reveal">

                <div>
                    <h2>{{ $milikku ? 'Ubah ulasanmu' : 'Ceritain pengalamanmu' }}</h2>
                    <p>
                        Beri bintang dan tulis komentar singkat. Satu akun satu ulasan,
                        dan kamu bisa memperbaruinya kapan saja.
                    </p>

                    @auth
                        <span class="ts-as">
                            <i class="fas fa-user-circle" aria-hidden="true"></i>
                            Tampil sebagai {{ $namaSingkat($pengguna) }}
                        </span>
                    @endauth
                </div>

                @auth

                    <form action="{{ route('testimoni.store') }}" method="POST" class="ts-fields" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="ts-alert" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <fieldset>
                            <legend>Penilaian</legend>

                            <div class="ts-pick" id="tsPick">
                                @php $nilaiLama = (int) old('rating', $milikku->rating ?? 0); @endphp

                                @for ($n = 5; $n >= 1; $n--)
                                    <input type="radio" name="rating" id="rate{{ $n }}" value="{{ $n }}" {{ $nilaiLama === $n ? 'checked' : '' }}>
                                    <label for="rate{{ $n }}" title="{{ $n }} bintang">
                                        <i class="fas fa-star" aria-hidden="true"></i>
                                        <span class="visually-hidden sr-only" style="position:absolute;left:-9999px">{{ $n }} bintang</span>
                                    </label>
                                @endfor
                            </div>

                            <span class="ts-hint" id="tsHint" aria-live="polite">{{ $nilaiLama ? $kataHint[$nilaiLama] : '' }}</span>
                        </fieldset>

                        <div>
                            <label class="ts-lbl" for="komentar">Komentar</label>
                            <textarea name="komentar" id="komentar" class="ts-area" maxlength="600" minlength="10"
                                      placeholder="Ceritakan perawatan yang kamu coba, pelayanan terapis, atau suasana salonnya...">{{ old('komentar', $milikku->komentar ?? '') }}</textarea>
                            <div class="ts-count"><span id="tsCount">0</span> / 600</div>
                        </div>

                        <div class="ts-form-actions">
                            <button type="submit" class="bkl-btn bkl-btn-light">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                {{ $milikku ? 'Perbarui ulasan' : 'Kirim ulasan' }}
                            </button>

                            @if ($milikku)
                                <small>Mengirim lagi akan menggantikan ulasanmu yang lama.</small>
                            @endif
                        </div>
                    </form>

                @else

                    <div class="ts-fields">
                        <p>Masuk dulu dengan akun Azzahra untuk menulis ulasan. Belum punya akun? Daftar gratis, cuma sebentar.</p>

                        <div class="ts-login">
                            <a href="{{ route('login') }}" class="bkl-btn bkl-btn-light">
                                <i class="fas fa-right-to-bracket" aria-hidden="true"></i>
                                Masuk
                            </a>

                            <a href="{{ route('register') }}" class="bkl-btn bkl-btn-ghost">
                                <i class="fas fa-user-plus" aria-hidden="true"></i>
                                Daftar
                            </a>
                        </div>
                    </div>

                @endauth

            </div>

        </section>


        {{-- ===================== GOOGLE ===================== --}}
        <a href="{{ $gmaps }}" target="_blank" rel="noopener noreferrer" class="ts-google bkl-reveal" style="margin-bottom:20px">
            <span class="ts-g-logo" aria-hidden="true">G</span>
            <span class="ts-g-info">
                <b>Ulasan di Google</b>
                <small>Mau baca lebih banyak? Lihat pengalaman pelanggan langsung dari Google Maps.</small>
            </span>
            <span class="ts-g-go" aria-hidden="true"><i class="fas fa-arrow-up-right-from-square"></i></span>
        </a>

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    var kurangiGerak = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var KATA = @json($kataHint);

    /* ---------- angka rating naik dari 0 ---------- */
    function hitungNaik(el) {
        var target = parseFloat(el.getAttribute('data-count'));
        if (isNaN(target) || kurangiGerak) return;

        var mulai = null, durasi = 1300;

        function langkah(t) {
            if (mulai === null) mulai = t;
            var p = Math.min(1, (t - mulai) / durasi);
            var e = 1 - Math.pow(1 - p, 3);
            el.textContent = (target * e).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
            if (p < 1) requestAnimationFrame(langkah);
        }

        el.textContent = (0).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
        requestAnimationFrame(langkah);
    }

    document.querySelectorAll('.ts-score-num[data-count]').forEach(hitungNaik);

    /* ---------- bar sebaran dan angka ringkasan: jalan saat terlihat ---------- */
    var bars = document.getElementById('tsBars');
    var besar = document.querySelector('.ts-big b[data-count]');

    function nyalakan() {
        if (bars) bars.classList.add('is-in');
        if (besar) hitungNaik(besar);
    }

    if (bars) {
        document.documentElement.classList.add('ts-js');

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entri) {
                if (entri.some(function (e) { return e.isIntersecting; })) {
                    nyalakan();
                    io.disconnect();
                }
            }, { threshold: 0.3 });
            io.observe(bars);
        } else {
            nyalakan();
        }
    }

    /* ---------- pemilih bintang: tulisan penyemangat ---------- */
    var pick = document.getElementById('tsPick');
    var hint = document.getElementById('tsHint');

    if (pick && hint) {
        pick.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'rating') hint.textContent = KATA[e.target.value] || '';
        });

        pick.querySelectorAll('label').forEach(function (lb) {
            lb.addEventListener('mouseenter', function () {
                var id = lb.getAttribute('for') || '';
                hint.textContent = KATA[id.replace('rate', '')] || '';
            });
        });

        pick.addEventListener('mouseleave', function () {
            var dipilih = pick.querySelector('input:checked');
            hint.textContent = dipilih ? (KATA[dipilih.value] || '') : '';
        });
    }

    /* ---------- penghitung karakter ---------- */
    var area = document.getElementById('komentar');
    var cacah = document.getElementById('tsCount');

    if (area && cacah) {
        function hitung() { cacah.textContent = area.value.length; }
        area.addEventListener('input', hitung);
        hitung();
    }

    /* ---------- bila ada galat validasi, langsung ke form ---------- */
    @if ($errors->any())
        var form = document.getElementById('tulis');
        if (form && form.scrollIntoView) form.scrollIntoView({ behavior: kurangiGerak ? 'auto' : 'smooth', block: 'start' });
    @endif
});
</script>

@endsection