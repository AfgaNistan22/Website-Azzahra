{{-- =========================
     MENGAPA MEMILIH KAMI
========================= --}}
<section class="hz-section wy" id="tentang">
    <div class="container">

        <div class="hz-head">
            <span class="hz-eyebrow rv">Kenapa Memilih Kami</span>
            <h2 class="hz-title rv" style="--d:.1s">
                Kenyamanan Anda <em>Prioritas Kami</em>
            </h2>
            <p class="hz-desc rv" style="--d:.2s">
                Kami menghadirkan pengalaman perawatan yang nyaman, berkualitas,
                dan dirancang khusus untuk muslimah.
            </p>
        </div>

        <div class="row g-4">
            @php
                $features = [
                    ['icon' => 'fa-spa',         'title' => 'Terapis Profesional', 'text' => 'Ditangani oleh tenaga ahli yang terampil dan berpengalaman.'],
                    ['icon' => 'fa-leaf',        'title' => 'Produk Berkualitas',  'text' => 'Mengutamakan produk perawatan yang aman dan berkualitas.'],
                    ['icon' => 'fa-shield-heart','title' => 'Privasi Terjaga',     'text' => 'Suasana perawatan yang nyaman dan menjaga privasi muslimah.'],
                    ['icon' => 'fa-heart',       'title' => 'Pelayanan Ramah',     'text' => 'Kami berusaha memberikan pengalaman terbaik bagi setiap pelanggan.'],
                ];
            @endphp

            @foreach ($features as $i => $f)
                <div class="col-lg-3 col-md-6">
                    <div class="wy-card spot rv" style="--d: {{ $i * .1 }}s">
                        <span class="wy-num">0{{ $i + 1 }}</span>
                        <div class="wy-icon"><i class="fa-solid {{ $f['icon'] }}"></i></div>
                        <h3>{{ $f['title'] }}</h3>
                        <p>{{ $f['text'] }}</p>
                        <span class="wy-line"></span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>


{{-- =========================
     PROMO
========================= --}}
<section class="hz-section pm" id="promo">
    <div class="container">
        <div class="pm-card rv">

            <div class="pm-glow" aria-hidden="true"></div>

            <div class="row align-items-center g-0">

                <div class="col-lg-6">
                    <div class="pm-image">
                        <img src="{{ asset('template/img/slider2.jpg') }}"
                             alt="Perawatan Azzahra Salon dan Spa Muslimah"
                             loading="lazy"
                             onerror="this.onerror=null; this.src='{{ asset('template/img/service-default.png') }}';">

                        <svg class="pm-sticker" viewBox="0 0 120 120" aria-hidden="true">
                            <defs><path id="pmCircle" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
                            <circle cx="60" cy="60" r="58" fill="#d4ad5a"/>
                            <text font-size="11.5" font-weight="800" letter-spacing="2.4" fill="#3a0d12">
                                <textPath href="#pmCircle">PROMO SPESIAL ✦ PROMO SPESIAL ✦</textPath>
                            </text>
                        </svg>
                        <i class="fa-solid fa-gift pm-sticker-ico"></i>

                        <span class="pm-image-label">
                            <i class="fa-solid fa-heart"></i>
                            Waktu terbaik untuk merawat diri
                        </span>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="pm-content">
                        <span class="pm-badge"><i class="fa-solid fa-gift"></i> Promo Spesial</span>

                        <h2>Waktunya <em>Memanjakan Diri</em></h2>

                        <p>
                            Temukan perawatan favorit Anda dan nikmati
                            pengalaman salon yang nyaman bersama Azzahra.
                        </p>

                        <ul class="pm-list">
                            <li><i class="fa-solid fa-circle-check"></i> Layanan khusus muslimah</li>
                            <li><i class="fa-solid fa-circle-check"></i> Perawatan oleh terapis profesional</li>
                        </ul>

                        <a href="{{ auth()->check() ? (\Illuminate\Support\Facades\Route::has('perawatan-layanan.index') ? route('perawatan-layanan.index') : '#') : route('login') }}"
                           class="pm-btn">
                            Booking Sekarang
                            <span class="pm-btn-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                        </a>

                        <small class="pm-disclaimer">
                            Informasi harga dan promo dapat dikonfirmasi kepada pihak salon.
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<style>
    /* =============== WHY =============== */
    .wy { background: #fff; }

    .wy-card {
        height: 100%;
        padding: 34px 28px 30px;
        border: 1px solid #f3e1e7;
        border-radius: 28px;
        background: linear-gradient(180deg, #fff, #fffaf8);
        transition: transform .4s cubic-bezier(.2, .8, .2, 1), box-shadow .4s ease, border-color .4s;
    }

    .wy-card > * { position: relative; z-index: 1; }

    .wy-card:hover {
        transform: translateY(-10px);
        border-color: #efc3d1;
        box-shadow: 0 30px 60px rgba(139, 16, 46, .14);
    }

    .wy-num {
        position: absolute !important;
        top: 18px;
        right: 24px;
        color: #f4dfe6;
        font-size: 46px;
        font-weight: 800;
        line-height: 1;
        transition: color .4s, transform .4s;
    }

    .wy-card:hover .wy-num { color: var(--hz-gold-light); transform: scale(1.12); }

    .wy-icon {
        display: grid;
        place-items: center;
        width: 66px;
        height: 66px;
        margin-bottom: 24px;
        border-radius: 22px;
        background: linear-gradient(135deg, var(--hz-blush), #fff3dc);
        color: var(--hz-berry);
        font-size: 26px;
        transition: transform .5s cubic-bezier(.3, 1.6, .5, 1), background .4s, color .4s;
    }

    .wy-card:hover .wy-icon {
        background: linear-gradient(135deg, var(--hz-berry), var(--hz-pink));
        color: #fff;
        transform: rotate(-8deg) scale(1.1);
    }

    .wy-card h3 { margin: 0 0 10px; color: var(--hz-wine); font-size: 19px; font-weight: 700; letter-spacing: -.01em; }
    .wy-card p { margin: 0; color: #7b6068; font-size: 14.5px; line-height: 1.75; }

    .wy-line {
        display: block;
        width: 36px;
        height: 3px;
        margin-top: 22px;
        border-radius: 3px;
        background: linear-gradient(90deg, var(--hz-pink), var(--hz-gold));
        transition: width .5s ease;
    }

    .wy-card:hover .wy-line { width: 100%; }

    /* =============== PROMO =============== */
    .pm { background: linear-gradient(180deg, #fff 0%, #fff6f2 100%); }

    .pm-card {
        position: relative;
        overflow: hidden;
        border-radius: 40px;
        background: linear-gradient(135deg, var(--hz-wine), var(--hz-maroon) 55%, #6d1a2a);
        box-shadow: 0 40px 90px rgba(58, 13, 18, .35);
    }

    .pm-card::after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 2px;
        background: linear-gradient(120deg, var(--hz-gold), transparent 30%, transparent 70%, var(--hz-pink));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: .7;
        pointer-events: none;
    }

    .pm-glow {
        position: absolute;
        top: -140px;
        right: -100px;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212, 20, 90, .45), transparent 65%);
        filter: blur(20px);
        animation: hx-float 9s ease-in-out infinite;
    }

    .pm-image { position: relative; height: 100%; min-height: 420px; overflow: hidden; }

    .pm-image img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.2s ease;
    }

    .pm-card:hover .pm-image img { transform: scale(1.06); }

    .pm-image::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 55%, rgba(58, 13, 18, .9)), linear-gradient(0deg, rgba(58, 13, 18, .6), transparent 40%);
    }

    .pm-sticker {
        position: absolute;
        z-index: 2;
        top: 24px;
        left: 24px;
        width: 116px;
        height: 116px;
        animation: hx-spin 18s linear infinite;
        filter: drop-shadow(0 10px 18px rgba(0, 0, 0, .3));
    }

    .pm-sticker-ico {
        position: absolute;
        z-index: 3;
        top: 69px;
        left: 69px;
        color: var(--hz-wine);
        font-size: 22px;
    }

    .pm-image-label {
        position: absolute;
        z-index: 2;
        bottom: 24px;
        left: 24px;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 18px;
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 999px;
        background: rgba(255, 255, 255, .16);
        backdrop-filter: blur(12px);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
    }

    .pm-image-label i { color: #ff8fb4; }

    .pm-content { position: relative; z-index: 2; padding: 56px 52px; color: #f7ebe6; }

    .pm-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border: 1px solid rgba(212, 173, 90, .5);
        border-radius: 999px;
        background: rgba(212, 173, 90, .14);
        color: var(--hz-gold-light);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .pm-content h2 {
        margin: 20px 0 14px;
        color: #fff;
        font-size: clamp(30px, 4vw, 46px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -.03em;
    }

    .pm-content h2 em {
        font-style: normal;
        background: linear-gradient(110deg, var(--hz-gold-light), #ff8fb4);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .pm-content > p { margin: 0 0 22px; color: #e7cfc8; font-size: 16px; line-height: 1.8; }

    .pm-list { margin: 0 0 30px; padding: 0; list-style: none; }

    .pm-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
    }

    .pm-list i { color: var(--hz-gold); }

    .pm-btn {
        display: inline-flex;
        align-items: center;
        gap: 14px;
        padding: 8px 8px 8px 28px;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--hz-gold-light), var(--hz-gold));
        color: var(--hz-wine);
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 16px 34px rgba(212, 173, 90, .35);
        transition: transform .25s, box-shadow .25s;
    }

    .pm-btn:hover { color: var(--hz-wine); text-decoration: none; transform: translateY(-3px); box-shadow: 0 22px 44px rgba(212, 173, 90, .5); }

    .pm-btn-arrow {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--hz-wine);
        color: var(--hz-gold-light);
        transition: transform .3s;
    }

    .pm-btn:hover .pm-btn-arrow { transform: rotate(-45deg); }

    .pm-disclaimer { display: block; margin-top: 18px; color: #c9aaa3; font-size: 12px; }

    /* =============== TESTIMONI =============== */
    .tm { background: linear-gradient(180deg, #fff6f2 0%, #fff 100%); }

    .tm-google {
        display: flex;
        align-items: center;
        gap: 16px;
        max-width: 560px;
        margin: 0 auto 40px;
        padding: 14px 18px;
        border: 1px solid #f1d9e0;
        border-radius: 22px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        box-shadow: 0 14px 34px rgba(80, 19, 34, .08);
        transition: transform .3s, box-shadow .3s;
    }

    .tm-google:hover { color: inherit; text-decoration: none; transform: translateY(-3px); box-shadow: 0 20px 44px rgba(80, 19, 34, .14); }

    .tm-g-logo {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: #fff;
        box-shadow: inset 0 0 0 1px #eee, 0 6px 14px rgba(0, 0, 0, .08);
        background-image: conic-gradient(from -45deg, #ea4335 0 25%, #fbbc05 0 50%, #34a853 0 75%, #4285f4 0);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 28px;
        font-weight: 800;
    }

    .tm-g-info { flex: 1; min-width: 0; }
    .tm-g-info b { display: block; color: var(--hz-wine); font-size: 16px; }
    .tm-g-info small { color: #8a6c75; font-size: 13px; }

    .tm-g-go {
        display: grid;
        place-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--hz-blush);
        color: var(--hz-berry);
        transition: background .3s, color .3s, transform .3s;
    }

    .tm-google:hover .tm-g-go { background: var(--hz-berry); color: #fff; transform: rotate(45deg); }

    .tm-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 30px 28px;
        border: 1px solid #f3e1e7;
        border-radius: 28px;
        background: #fff;
        transition: transform .4s cubic-bezier(.2, .8, .2, 1), box-shadow .4s;
    }

    .tm-card > * { position: relative; z-index: 1; }

    .tm-card:hover { transform: translateY(-10px) rotate(-.6deg); box-shadow: 0 30px 60px rgba(139, 16, 46, .14); }

    .tm-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }

    .tm-quote {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--hz-berry), var(--hz-pink));
        color: #fff;
        box-shadow: 0 10px 22px rgba(212, 20, 90, .3);
    }

    .tm-stars { color: var(--hz-gold); font-size: 16px; letter-spacing: 2px; }

    .tm-tag {
        padding: 5px 12px;
        border-radius: 999px;
        background: #fff3dc;
        color: #8a6215;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .1em;
    }

    .tm-text { flex: 1; margin: 0 0 24px; color: #5f464e; font-size: 15.5px; line-height: 1.85; }

    .tm-person { display: flex; align-items: center; gap: 14px; padding-top: 20px; border-top: 1px dashed #efd3dc; }

    .tm-avatar {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: var(--hz-blush);
        color: var(--hz-berry);
    }

    .tm-person h3 { margin: 0; color: var(--hz-wine); font-size: 15px; font-weight: 700; }
    .tm-person span { color: #8a6c75; font-size: 12.5px; }

    .tm-link {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        align-self: flex-start;
        margin-top: 20px;
        color: var(--hz-berry);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        background-image: linear-gradient(var(--hz-pink), var(--hz-pink));
        background-repeat: no-repeat;
        background-position: 0 100%;
        background-size: 0 2px;
        transition: background-size .35s ease, gap .3s;
    }

    .tm-link:hover { color: var(--hz-pink); text-decoration: none; background-size: 100% 2px; gap: 14px; }
    .tm-link i { font-size: 11px; }

    .tm-cta { margin-top: 56px; text-align: center; }
    .tm-cta p { margin: 0 0 14px; color: #7b6068; font-size: 16px; }

    .tm-cta a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 30px;
        border-radius: 999px;
        background: var(--hz-wine);
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 14px 30px rgba(58, 13, 18, .25);
        transition: transform .25s, background .25s, gap .25s;
    }

    .tm-cta a:hover { color: #fff; text-decoration: none; background: var(--hz-berry); transform: translateY(-3px); gap: 16px; }

    /* =============== RESPONSIF =============== */
    @media (max-width: 991.98px) {
        .pm-image { min-height: 320px; }
        .pm-image::after { background: linear-gradient(0deg, rgba(58, 13, 18, .95), transparent 60%); }
        .pm-content { padding: 36px 28px 42px; }
    }

    @media (max-width: 575.98px) {
        .pm-card { border-radius: 28px; }
        .pm-sticker { width: 92px; height: 92px; top: 16px; left: 16px; }
        .pm-sticker-ico { top: 54px; left: 54px; font-size: 18px; }
        .tm-g-info small { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pm-sticker, .pm-glow { animation: none; }
    }
</style>