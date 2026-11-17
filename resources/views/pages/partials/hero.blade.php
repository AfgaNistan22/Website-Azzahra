@php
    use Illuminate\Support\Facades\Route;

    $urlLayanan = Route::has('perawatan-layanan.index') ? route('perawatan-layanan.index') : '#tentang';
    $urlBooking = auth()->check()
        ? $urlLayanan
        : (Route::has('login') ? route('login') : '#');
@endphp

<section class="hx">

    {{-- Dekorasi latar --}}
    <div class="hx-bg" aria-hidden="true">
        <span class="hx-blob hx-blob-1"></span>
        <span class="hx-blob hx-blob-2"></span>
        <span class="hx-blob hx-blob-3"></span>
        <span class="hx-grid"></span>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center g-5">

            {{-- ============ TEKS ============ --}}
            <div class="col-lg-6">

                <span class="hx-badge rv" style="--d:.05s">
                    <span class="hx-badge-dot"></span>
                    Salon &amp; Spa Muslimah Profesional
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </span>

                <h1 class="hx-title">
                    <span class="visually-hidden sr-only">Tampil cantik bersama Azzahra</span>

                    <span class="hx-line" aria-hidden="true">
                        <span style="--i:0">Tampil</span>
                        {{-- Kata berganti, memakai gradasi berkilau yang sama dengan "Azzahra" --}}
                        <span class="hx-rot" style="--i:1">
                            <span class="hx-rot-track">
                                <span>cantik</span>
                                <span>segar</span>
                                <span>rileks</span>
                                <span>percaya diri</span>
                                <span>cantik</span>
                            </span>
                        </span>
                    </span>

                    <span class="hx-line" aria-hidden="true">
                        <span style="--i:2">bersama</span>
                        <span class="hx-grad" style="--i:3">Azzahra
                            <svg class="hx-squiggle" viewBox="0 0 200 14" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M2 9c18-9 30 8 48 0s30 8 48 0 30 8 48 0 30 8 50 0" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </span>
                </h1>

                <p class="hx-lead rv" style="--d:.35s">
                    Nikmati layanan salon dan spa khusus muslimah dengan terapis
                    profesional, produk berkualitas, serta suasana yang nyaman,
                    bersih, dan privat. Waktunya self-care tanpa khawatir.
                </p>

                <div class="hx-cta rv" style="--d:.45s">
                    <a href="{{ $urlLayanan }}" class="hx-btn hx-btn-primary">
                        <i class="fa-solid fa-spa"></i>
                        Lihat Layanan
                        <span class="hx-btn-shine"></span>
                    </a>
                    <a href="{{ $urlBooking }}" class="hx-btn hx-btn-ghost">
                        <i class="fa-solid fa-calendar-check"></i>
                        Booking Sekarang
                    </a>
                </div>

                <div class="hx-stats">
                    <div class="hx-stat rv" style="--d:.55s">
                        <strong><span data-count="22" data-suffix="+">0</span></strong>
                        <small>Tahun Pengalaman</small>
                    </div>
                    <div class="hx-stat rv" style="--d:.65s">
                        <strong><span data-count="5000" data-suffix="+">0</span></strong>
                        <small>Pelanggan</small>
                    </div>
                    <div class="hx-stat rv" style="--d:.75s">
                        <strong><span data-count="{{ (int) ($totalLayanan ?? 0) }}" data-suffix="+">0</span></strong>
                        <small>Jenis Layanan</small>
                    </div>
                </div>
            </div>

            {{-- ============ VISUAL ============ --}}
            <div class="col-lg-6">
                <div class="hx-visual rv" style="--d:.2s" data-tilt>

                    <span class="hx-ring" aria-hidden="true"></span>

                    <div class="hx-photo">
                        <img src="{{ asset('template/img/slider1.JPG') }}"
                             alt="Suasana perawatan Azzahra Salon &amp; Spa Muslimah"
                             onerror="this.onerror=null; this.src='{{ asset('template/img/slider2.jpg') }}';">
                    </div>

                    <div class="hx-chip hx-chip-1">
                        <span class="hx-chip-ico"><i class="fa-solid fa-user-nurse"></i></span>
                        <div><b>Terapis Profesional</b><small>Terampil &amp; berpengalaman</small></div>
                    </div>

                    <div class="hx-chip hx-chip-2">
                        <span class="hx-chip-ico"><i class="fa-solid fa-lock"></i></span>
                        <div><b>Ruang Privat</b><small>Khusus muslimah</small></div>
                    </div>

                    <div class="hx-chip hx-chip-3">
                        <span class="hx-chip-ico"><i class="fa-solid fa-leaf"></i></span>
                        <div><b>Produk Aman</b><small>Kualitas terjaga</small></div>
                    </div>

                    <span class="hx-spark hx-spark-1" aria-hidden="true">✦</span>
                    <span class="hx-spark hx-spark-2" aria-hidden="true">✦</span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Marquee --}}
<div class="hx-marquee" aria-hidden="true">
    <div class="hx-marquee-track">
        @for ($r = 0; $r < 2; $r++)
            <div class="hx-marquee-group">
                <span>Self Care</span><i>✦</i>
                <span>Relax &amp; Glow</span><i>✦</i>
                <span>Khusus Muslimah</span><i>✦</i>
                <span>Privasi Terjaga</span><i>✦</i>
                <span>Terapis Profesional</span><i>✦</i>
                <span>Nyaman &amp; Bersih</span><i>✦</i>
            </div>
        @endfor
    </div>
</div>

<style>
    .hx {
        position: relative;
        padding: 70px 0 96px;
        overflow: hidden;
        background: linear-gradient(180deg, #fff7f4 0%, #fffaf7 60%, #fff 100%);
    }

    /* ---------- Latar ---------- */
    .hx-bg { position: absolute; inset: 0; pointer-events: none; }

    .hx-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(70px);
        opacity: .55;
        animation: hx-float 12s ease-in-out infinite;
    }

    .hx-blob-1 { top: -120px; left: -100px; width: 380px; height: 380px; background: #f9c0d4; }
    .hx-blob-2 { top: 20%; right: -120px; width: 420px; height: 420px; background: #f3dba5; animation-delay: -4s; }
    .hx-blob-3 { bottom: -160px; left: 35%; width: 340px; height: 340px; background: #f1b8c8; animation-delay: -8s; }

    .hx-grid {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(80, 19, 34, .09) 1px, transparent 1px);
        background-size: 26px 26px;
        mask-image: radial-gradient(ellipse at 30% 30%, #000 10%, transparent 70%);
        -webkit-mask-image: radial-gradient(ellipse at 30% 30%, #000 10%, transparent 70%);
    }

    @keyframes hx-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50%      { transform: translate(30px, -26px) scale(1.08); }
    }

    /* ---------- Teks ---------- */
    .hx-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 18px;
        border: 1px solid #f1d3dd;
        border-radius: 999px;
        background: rgba(255, 255, 255, .8);
        backdrop-filter: blur(8px);
        color: var(--hz-maroon);
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 8px 22px rgba(139, 16, 46, .1);
    }

    .hx-badge i { color: var(--hz-gold); }

    .hx-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        animation: hz-pulse 1.8s infinite;
    }

    .hx-title {
        margin: 22px 0 20px;
        color: var(--hz-wine);
        font-size: clamp(40px, 6vw, 78px);
        font-weight: 800;
        line-height: 1.04;
        letter-spacing: -.045em;
    }

    .hx-line { display: block; }

    .hx-line > span {
        display: inline-block;
        opacity: 0;
        transform: translateY(60%) rotate(4deg);
        animation: hx-word .9s cubic-bezier(.2, .9, .2, 1) forwards;
        animation-delay: calc(.15s + var(--i) * .12s);
    }

    @keyframes hx-word {
        to { opacity: 1; transform: none; }
    }

    /* ---------- Gradasi berkilau (dipakai bersama) ---------- */
    .hx-grad,
    .hx-rot-track > span {
        background: linear-gradient(110deg, var(--hz-berry), var(--hz-pink) 55%, var(--hz-gold), var(--hz-pink), var(--hz-berry));
        background-size: 200% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hx-grad {
        position: relative;
        animation: hx-word .9s cubic-bezier(.2, .9, .2, 1) forwards, hx-shimmer 5s linear infinite !important;
        animation-delay: calc(.15s + var(--i) * .12s), 0s !important;
    }

    @keyframes hx-shimmer {
        to { background-position: -200% 0; }
    }

    /* ---------- Kata berganti ---------- */
    .hx-rot {
        height: 1.18em;
        overflow: hidden;
        vertical-align: bottom;
        white-space: nowrap;
    }

    .hx-rot-track {
        display: flex;
        flex-direction: column;
        animation: hx-rotate 10s cubic-bezier(.2, .9, .2, 1) infinite;
    }

    .hx-rot-track > span {
        height: 1.18em;
        line-height: 1.18em;
        animation: hx-shimmer 5s linear infinite;
    }

    @keyframes hx-rotate {
        0%, 20%   { transform: translateY(0); }
        25%, 45%  { transform: translateY(-1.18em); }
        50%, 70%  { transform: translateY(-2.36em); }
        75%, 95%  { transform: translateY(-3.54em); }
        100%      { transform: translateY(-4.72em); }
    }

    .hx-squiggle {
        position: absolute;
        left: 0;
        bottom: -10px;
        width: 100%;
        height: 14px;
        color: var(--hz-gold);
        -webkit-text-fill-color: initial;
    }

    .hx-squiggle path { stroke-dasharray: 260; stroke-dashoffset: 260; animation: hx-draw 1.2s .9s ease forwards; }

    @keyframes hx-draw { to { stroke-dashoffset: 0; } }

    .hx-lead {
        max-width: 540px;
        margin: 0 0 30px;
        color: #6f555d;
        font-size: 17px;
        line-height: 1.85;
    }

    /* ---------- Tombol ---------- */
    .hx-cta { display: flex; flex-wrap: wrap; gap: 14px; }

    .hx-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        overflow: hidden;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
    }

    .hx-btn:hover { transform: translateY(-3px); text-decoration: none; }

    .hx-btn-primary {
        background: linear-gradient(135deg, var(--hz-maroon), var(--hz-berry) 55%, var(--hz-pink));
        color: #fff;
        box-shadow: 0 16px 34px rgba(139, 16, 46, .32);
    }

    .hx-btn-primary:hover { color: #fff; box-shadow: 0 22px 44px rgba(212, 20, 90, .4); }

    .hx-btn-shine {
        position: absolute;
        top: 0;
        left: -80%;
        width: 50%;
        height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .45), transparent);
        transform: skewX(-20deg);
        animation: hx-shine 3.4s ease-in-out infinite;
    }

    @keyframes hx-shine {
        0%, 55% { left: -80%; }
        100%    { left: 140%; }
    }

    .hx-btn-ghost {
        border: 1.5px solid #e9c9d3;
        background: rgba(255, 255, 255, .75);
        backdrop-filter: blur(8px);
        color: var(--hz-maroon);
    }

    .hx-btn-ghost:hover {
        border-color: var(--hz-pink);
        background: #fff;
        color: var(--hz-berry);
        box-shadow: 0 14px 30px rgba(139, 16, 46, .14);
    }

    /* ---------- Statistik ---------- */
    .hx-stats { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 38px; }

    .hx-stat {
        min-width: 130px;
        padding: 16px 22px;
        border: 1px solid rgba(255, 255, 255, .9);
        border-radius: 20px;
        background: rgba(255, 255, 255, .7);
        backdrop-filter: blur(10px);
        box-shadow: 0 12px 30px rgba(80, 19, 34, .08);
    }

    .hx-stat strong {
        display: block;
        color: var(--hz-wine);
        font-size: 30px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -.03em;
    }

    .hx-stat small { color: #8a6c75; font-size: 12.5px; font-weight: 500; }

    /* ---------- Visual ---------- */
    .hx-visual {
        --rx: 0deg;
        --ry: 0deg;
        position: relative;
        max-width: 520px;
        margin: 0 auto;
        aspect-ratio: 1 / 1.08;
        perspective: 1000px;
    }

    .hx-photo {
        position: absolute;
        inset: 6% 6% 6% 6%;
        overflow: hidden;
        border: 6px solid #fff;
        border-radius: 42% 58% 55% 45% / 48% 42% 58% 52%;
        background: var(--hz-blush);
        box-shadow: 0 40px 80px rgba(80, 19, 34, .28);
        transform: rotateX(var(--rx)) rotateY(var(--ry));
        transition: transform .2s ease-out;
        animation: hx-morph 12s ease-in-out infinite;
    }

    .hx-photo img { width: 100%; height: 100%; object-fit: cover; transform: scale(1.08); }

    @keyframes hx-morph {
        0%, 100% { border-radius: 42% 58% 55% 45% / 48% 42% 58% 52%; }
        50%      { border-radius: 58% 42% 45% 55% / 55% 55% 45% 45%; }
    }

    .hx-ring {
        position: absolute;
        inset: 0;
        border: 2px dashed rgba(212, 173, 90, .8);
        border-radius: 50%;
        animation: hx-spin 40s linear infinite;
    }

    @keyframes hx-spin { to { transform: rotate(360deg); } }

    .hx-chip {
        position: absolute;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 18px 11px 11px;
        border: 1px solid rgba(255, 255, 255, .9);
        border-radius: 18px;
        background: rgba(255, 255, 255, .82);
        backdrop-filter: blur(14px);
        box-shadow: 0 18px 40px rgba(80, 19, 34, .18);
        animation: hx-bob 5s ease-in-out infinite;
    }

    .hx-chip b { display: block; color: var(--hz-wine); font-size: 13.5px; line-height: 1.2; }
    .hx-chip small { color: #8a6c75; font-size: 11.5px; }

    .hx-chip-ico {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        border-radius: 13px;
        background: linear-gradient(135deg, var(--hz-berry), var(--hz-pink));
        color: #fff;
        font-size: 15px;
    }

    .hx-chip-1 { top: 8%; left: -6%; }
    .hx-chip-2 { top: 46%; right: -8%; animation-delay: -1.6s; }
    .hx-chip-2 .hx-chip-ico { background: linear-gradient(135deg, #b8872c, var(--hz-gold)); }
    .hx-chip-3 { bottom: 6%; left: 2%; animation-delay: -3.2s; }
    .hx-chip-3 .hx-chip-ico { background: linear-gradient(135deg, #2f7d54, #4fb37e); }

    @keyframes hx-bob {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }

    .hx-spark {
        position: absolute;
        color: var(--hz-gold);
        animation: hx-twinkle 2.8s ease-in-out infinite;
    }

    .hx-spark-1 { top: 2%; right: 14%; font-size: 30px; }
    .hx-spark-2 { bottom: 14%; right: 4%; font-size: 20px; animation-delay: -1.4s; color: var(--hz-pink); }

    @keyframes hx-twinkle {
        0%, 100% { transform: scale(.7) rotate(0); opacity: .5; }
        50%      { transform: scale(1.2) rotate(45deg); opacity: 1; }
    }

    /* ---------- Marquee ---------- */
    .hx-marquee {
        position: relative;
        z-index: 2;
        overflow: hidden;
        padding: 18px 0;
        background: linear-gradient(90deg, var(--hz-wine), var(--hz-maroon) 50%, var(--hz-wine));
        border-top: 2px solid var(--hz-gold);
        border-bottom: 2px solid var(--hz-gold);
    }

    .hx-marquee-track {
        display: flex;
        width: max-content;
        animation: hx-scroll 32s linear infinite;
    }

    .hx-marquee:hover .hx-marquee-track { animation-play-state: paused; }

    .hx-marquee-group { display: flex; align-items: center; gap: 28px; padding-right: 28px; }

    .hx-marquee-group span {
        color: #f7ebe6;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    .hx-marquee-group i { color: var(--hz-gold); font-style: normal; font-size: 16px; }

    @keyframes hx-scroll { to { transform: translateX(-50%); } }

    /* ---------- Responsif ---------- */
    @media (max-width: 991.98px) {
        .hx { padding: 44px 0 70px; }
        .hx-chip-1 { left: 0; }
        .hx-chip-2 { right: 0; }
    }

    @media (max-width: 575.98px) {
        .hx-cta .hx-btn { flex: 1 1 100%; justify-content: center; }
        .hx-stat { flex: 1 1 calc(33% - 10px); min-width: 0; padding: 14px 12px; text-align: center; }
        .hx-stat strong { font-size: 24px; }
        .hx-chip small { display: none; }
        .hx-chip { padding: 8px 14px 8px 8px; }
        .hx-marquee-group span { font-size: 16px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .hx-line > span { opacity: 1; transform: none; animation: none; }
        .hx-rot-track { animation: none; }
        .hx-rot-track > span, .hx-grad { animation: none !important; }
        .hx-squiggle path { stroke-dashoffset: 0; animation: none; }
        .hx-marquee-track { animation: none; }
    }
</style>