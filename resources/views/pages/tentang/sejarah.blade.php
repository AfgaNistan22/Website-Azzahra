@extends('pages.auth.guest')

@section('title', 'Sejarah | Azzahra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">

    {{-- tema dasar sama dengan halaman booking, lalu pelengkap halaman sejarah --}}
    <link rel="stylesheet" href="{{ asset('template/css/booking-list-modern.css') }}?v={{ file_exists(public_path('template/css/booking-list-modern.css')) ? filemtime(public_path('template/css/booking-list-modern.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('template/css/sejarah-modern.css') }}?v={{ file_exists(public_path('template/css/sejarah-modern.css')) ? filemtime(public_path('template/css/sejarah-modern.css')) : time() }}">
@endpush

@push('styles')
<style>
/* =========================================================
   SLIDER SEJARAH (class berawalan sjx- agar tidak bentrok
   dengan sejarah-modern.css)
========================================================== */
.sjx {
    --x-maroon: #64172D;
    --x-dark:   #450D1D;
    --x-rose:   #C2415D;
    --x-gold:   #A98B4F;
    --x-ink:    #2E1B20;
    --x-muted:  #8A7880;
    --x-line:   #F0E2E1;
    --x-blush:  #FFF0F3;
    --x-grad:   linear-gradient(135deg, #8A2442 0%, #64172D 55%, #450D1D 100%);
    --x-soft:   linear-gradient(135deg, #FFF0F3 0%, #FFF8EF 100%);
    --x-ease:   cubic-bezier(.2,.8,.2,1);
    font-family: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
}

/* ---------- panel ---------- */
.sjx-panel {
    position: relative;
    overflow: hidden;
    background: rgba(255,255,255,.96);
    border: 1px solid var(--x-line);
    border-radius: 28px;
    box-shadow: 0 1px 2px rgba(69,13,29,.04), 0 24px 50px -24px rgba(100,23,45,.30);
}
.sjx-panel::before {                      /* blob dekor */
    content: ''; position: absolute; top: -90px; right: -90px;
    width: 260px; height: 260px; border-radius: 50%;
    background: radial-gradient(circle, rgba(194,65,93,.14), transparent 70%);
    pointer-events: none;
}

/* ---------- baris atas: penghitung + progress ---------- */
.sjx-top { padding: 22px 28px 0; position: relative; z-index: 1; }
.sjx-count {
    display: flex; align-items: baseline; justify-content: space-between; gap: 12px;
    margin-bottom: 12px;
    color: var(--x-muted); font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
}
.sjx-count b { color: var(--x-maroon); font-family: 'Playfair Display', serif; font-size: 22px; letter-spacing: 0; }
.sjx-bar { height: 5px; border-radius: 99px; background: #F5E9E7; overflow: hidden; }
.sjx-bar > i {
    display: block; height: 100%; width: 0; border-radius: 99px;
    background: linear-gradient(90deg, var(--x-rose), var(--x-gold));
    transition: width .6s var(--x-ease);
}

/* ---------- rail tab (bab / tahun) ---------- */
.sjx-rail {
    position: relative; z-index: 1;
    display: flex; gap: 8px; padding: 18px 28px 6px;
    overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;
}
.sjx-rail::-webkit-scrollbar { display: none; }
.sjx-tab {
    flex-shrink: 0;
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 15px; border-radius: 999px;
    border: 1.5px solid var(--x-line); background: #fff;
    color: var(--x-muted); font: 700 12px/1 'DM Sans', sans-serif;
    cursor: pointer; transition: all .25s var(--x-ease);
}
.sjx-tab span { opacity: .6; font-weight: 800; }
.sjx-tab:hover { border-color: var(--x-rose); color: var(--x-maroon); transform: translateY(-1px); }
.sjx-tab.is-passed { background: var(--x-blush); color: var(--x-maroon); border-color: rgba(194,65,93,.25); }
.sjx-tab.is-active {
    background: var(--x-grad); color: #fff; border-color: transparent;
    box-shadow: 0 12px 22px -10px rgba(100,23,45,.7);
}
.sjx-tab.is-active span { opacity: .8; }

/* ---------- stage: semua slide menumpuk di satu sel grid,
   jadi tinggi otomatis mengikuti slide terpanjang ---------- */
.sjx-stage { display: grid; position: relative; z-index: 1; padding: 8px 28px 6px; outline: none; }
.sjx-slide {
    grid-area: 1 / 1;
    position: relative;
    padding: 18px 4px 10px;
    opacity: 0; visibility: hidden;
    transform: translateX(36px);
    transition: opacity .45s ease, transform .6s var(--x-ease), visibility 0s .45s;
}
.sjx-slide.is-before { transform: translateX(-36px); }
.sjx-slide.is-active {
    opacity: 1; visibility: visible; transform: none;
    transition: opacity .5s ease .08s, transform .6s var(--x-ease) .08s, visibility 0s;
}

.sjx-ghost {                              /* angka besar transparan */
    position: absolute; top: -6px; right: 0;
    font-family: 'Playfair Display', serif; font-weight: 600; font-style: italic;
    font-size: clamp(80px, 12vw, 130px); line-height: 1;
    color: transparent; -webkit-text-stroke: 1.5px rgba(100,23,45,.12);
    pointer-events: none; user-select: none;
}
.sjx-chip {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 6px 14px; border-radius: 999px;
    background: var(--x-soft); border: 1px solid rgba(169,139,79,.4);
    color: #7a5f24; font-size: 11.5px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
}
.sjx-chip::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--x-gold); }
.sjx-slide-title {
    position: relative;
    margin: 14px 0 14px; max-width: 80%;
    font-family: 'Playfair Display', serif; font-weight: 600;
    font-size: clamp(24px, 3.2vw, 34px); line-height: 1.2; letter-spacing: -.01em;
    color: var(--x-dark);
}
.sjx-slide-body { position: relative; max-width: 68ch; }
.sjx-slide-body p { margin: 0 0 14px; color: #5b4650; font-size: 15px; line-height: 1.85; }
.sjx-slide-body p:last-child { margin-bottom: 0; }
.sjx-slide-body strong { color: var(--x-maroon); }
.sjx-slide-body em { color: var(--x-maroon); font-style: italic; }

/* paragraf muncul bertahap saat slide aktif */
.sjx-slide.is-active .sjx-chip,
.sjx-slide.is-active .sjx-slide-title,
.sjx-slide.is-active .sjx-slide-body p { animation: sjx-rise .6s var(--x-ease) both; }
.sjx-slide.is-active .sjx-slide-title { animation-delay: .08s; }
.sjx-slide.is-active .sjx-slide-body p:nth-child(1) { animation-delay: .16s; }
.sjx-slide.is-active .sjx-slide-body p:nth-child(2) { animation-delay: .26s; }
.sjx-slide.is-active .sjx-slide-body p:nth-child(3) { animation-delay: .36s; }
.sjx-slide.is-active .sjx-slide-body p:nth-child(4) { animation-delay: .46s; }
.sjx-slide.is-active .sjx-slide-body p:nth-child(5) { animation-delay: .56s; }
@keyframes sjx-rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }

/* ---------- kontrol ---------- */
.sjx-controls {
    position: relative; z-index: 1;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 16px 28px 24px;
}
.sjx-hint { color: var(--x-muted); font-size: 11.5px; font-weight: 500; }
.sjx-btns { display: flex; gap: 10px; }
.sjx-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    min-height: 44px; padding: 0 20px; border-radius: 999px;
    border: 1.5px solid rgba(100,23,45,.22); background: #fff; color: var(--x-maroon);
    font: 700 13px/1 'DM Sans', sans-serif; cursor: pointer;
    transition: all .25s var(--x-ease);
}
.sjx-btn:hover:not(:disabled) { background: var(--x-blush); border-color: var(--x-maroon); transform: translateY(-1px); }
.sjx-btn.is-primary { border-color: transparent; background: var(--x-grad); color: #fff; box-shadow: 0 12px 22px -10px rgba(100,23,45,.7); }
.sjx-btn.is-primary:hover:not(:disabled) { background: var(--x-grad); filter: brightness(1.08); transform: translateY(-2px); }
.sjx-btn:disabled { opacity: .4; cursor: not-allowed; box-shadow: none; }
.sjx-btn:focus-visible, .sjx-tab:focus-visible { outline: 3px solid rgba(194,65,93,.4); outline-offset: 2px; }

/* ---------- layout cerita: slider + samping ---------- */
.sjx-story-wrap { display: grid; grid-template-columns: minmax(0, 1fr) 290px; gap: 24px; align-items: start; }
.sjx-side { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 14px; }
.sjx-art {
    position: relative; overflow: hidden; aspect-ratio: 1 / 1; border-radius: 28px;
    display: grid; place-items: center;
    background:
        radial-gradient(circle at 20% 10%, rgba(255,214,150,.30), transparent 45%),
        var(--x-grad);
    color: rgba(255,255,255,.92); font-size: 64px;
    box-shadow: 0 24px 44px -22px rgba(100,23,45,.6);
}
.sjx-art img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.sjx-art::before, .sjx-art::after {
    content: ''; position: absolute; border-radius: 50%; border: 1px dashed rgba(255,255,255,.3);
}
.sjx-art::before { width: 70%; height: 70%; animation: sjx-spin 40s linear infinite; }
.sjx-art::after  { width: 95%; height: 95%; animation: sjx-spin 70s linear infinite reverse; }
@keyframes sjx-spin { to { transform: rotate(360deg); } }
.sjx-art i { position: relative; z-index: 1; animation: sjx-float 4s ease-in-out infinite; }
@keyframes sjx-float { 50% { transform: translateY(-8px); } }

.sjx-facts { display: grid; gap: 10px; }
.sjx-fact {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 13px 18px; border-radius: 18px; background: #fff; border: 1px solid var(--x-line);
    box-shadow: 0 10px 24px -16px rgba(100,23,45,.3);
    transition: transform .25s var(--x-ease);
}
.sjx-fact:hover { transform: translateX(4px); }
.sjx-fact strong { font-family: 'Playfair Display', serif; font-size: 19px; color: var(--x-maroon); }
.sjx-fact span { color: var(--x-muted); font-size: 11.5px; font-weight: 600; text-align: right; }

/* ---------- timeline ---------- */
.sjx-tl-year {
    font-family: 'Playfair Display', serif; font-style: italic; font-weight: 600;
    font-size: clamp(46px, 8vw, 84px); line-height: 1; letter-spacing: -.02em;
    background: var(--x-grad); -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent; color: var(--x-maroon);
}
.sjx-tl-slide { padding-top: 10px; display: grid; grid-template-columns: auto 1fr; gap: 26px; align-items: center; }
.sjx-tl-slide .sjx-slide-title { max-width: none; margin-top: 0; font-size: clamp(21px, 2.6vw, 28px); }
.sjx-tl-slide .sjx-slide-body { max-width: 62ch; }
.sjx-tl-slide.is-active .sjx-tl-year { animation: sjx-rise .6s var(--x-ease) both; }

.sjx-tl .sjx-rail { padding-top: 24px; }
.sjx-tl .sjx-tab { flex-direction: column; align-items: center; gap: 8px; padding: 10px 16px 12px; border-radius: 18px; }
.sjx-tl .sjx-tab::before {
    content: ''; width: 11px; height: 11px; border-radius: 50%;
    background: #E9D6D2; transition: all .25s ease;
}
.sjx-tl .sjx-tab.is-passed::before { background: var(--x-rose); }
.sjx-tl .sjx-tab.is-active::before { background: #fff; box-shadow: 0 0 0 4px rgba(255,255,255,.3); }

/* ---------- responsif ---------- */
@media (max-width: 980px) {
    .sjx-story-wrap { grid-template-columns: 1fr; }
    .sjx-side { position: static; flex-direction: row; align-items: stretch; }
    .sjx-art { width: 150px; aspect-ratio: auto; flex-shrink: 0; font-size: 40px; }
    .sjx-facts { flex: 1; }
}
@media (max-width: 640px) {
    .sjx-top, .sjx-stage, .sjx-controls { padding-left: 18px; padding-right: 18px; }
    .sjx-rail { padding-left: 18px; padding-right: 18px; }
    .sjx-slide-title { max-width: 100%; }
    .sjx-slide-body p { font-size: 14px; }
    .sjx-hint { display: none; }
    .sjx-btns { width: 100%; }
    .sjx-btn { flex: 1; }
    .sjx-side { flex-direction: column; }
    .sjx-art { width: 100%; aspect-ratio: 16 / 9; }
    .sjx-tl-slide { grid-template-columns: 1fr; gap: 10px; }
}
@media (prefers-reduced-motion: reduce) {
    .sjx *, .sjx *::before, .sjx *::after { animation: none !important; transition-duration: .01ms !important; }
}
</style>
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
                    TENTANG KAMI
                </span>

                <h1>
                    Sejarah
                    <em>Azzahra.</em>
                </h1>

                <p>
                    Berawal dari sebuah kepedulian pada tahun 2004,
                    tumbuh menjadi perjalanan panjang dalam menghadirkan
                    perawatan kecantikan khusus perempuan muslimah.
                </p>

                <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    Buat Booking
                    <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
                </a>

            </div>

            <div class="bkl-hero-art" aria-hidden="true">

                <div class="bkl-orbit bkl-orbit-one"></div>
                <div class="bkl-orbit bkl-orbit-two"></div>

                <div class="bkl-art-center"><i class="fas fa-landmark"></i></div>

                <span class="bkl-art-spark bkl-spark-one">✦</span>
                <span class="bkl-art-spark bkl-spark-two">✧</span>
                <span class="bkl-art-spark bkl-spark-three">✦</span>

                <div class="bkl-art-caption">
                    <span>AZZAHRA</span>
                    <small>Sejak 2004 • Yogyakarta</small>
                </div>

            </div>

        </div>

    </section>


    <div class="bkl-wrap bkl-content sjx">

        {{-- ==========================================
             CERITA KAMI — SLIDER PER BAB
        =========================================== --}}
        <section class="sj-section">

            <div class="sj-head bkl-reveal">
                <span class="bkl-kicker">OUR STORY</span>
                <h2 class="sj-title">Cerita Kami</h2>

                <p class="sj-sub">
                    Berawal dari Kepedulian, Tumbuh Menjadi Dedikasi.
                </p>
            </div>

            <div class="sjx-story-wrap bkl-reveal">

                <div class="sjx-panel" data-sjx aria-roledescription="carousel" aria-label="Cerita Kami">

                    <div class="sjx-top">
                        <div class="sjx-count">
                            <span>Bab</span>
                            <span><b data-sjx-cur>1</b> / <span data-sjx-total>12</span></span>
                        </div>
                        <div class="sjx-bar"><i data-sjx-bar></i></div>
                    </div>

                    <div class="sjx-rail" role="tablist" aria-label="Daftar bab">
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>01</span> Sebuah Pertanyaan</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>02</span> Belajar &amp; Berprinsip</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>03</span> Azzahra Berdiri</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>04</span> Edukasi Masyarakat</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>05</span> Tumbuh &amp; Diuji</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>06</span> Reborn Azzahra</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>07</span> Melangkah Lebih Jauh</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>08</span> Babak Baru di AMC</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>09</span> Franchise &amp; Doa</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>10</span> Jaringan Meluas</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>11</span> Azzahra Ambassador</button>
                        <button type="button" class="sjx-tab" role="tab" data-sjx-tab><span>12</span> Hingga Kini</button>
                    </div>

                    <div class="sjx-stage" tabindex="0" aria-live="polite">

                        {{-- 01 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">01</span>
                            <span class="sjx-chip">2004</span>
                            <h3 class="sjx-slide-title">Sebuah Pertanyaan</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra Salon &amp; Spa Muslimah lahir dari sebuah
                                    pemikiran sederhana pada tahun 2004. Saat itu,
                                    pendiri Azzahra mendapatkan kesempatan mengunjungi
                                    sebuah tempat spa yang menawarkan perawatan khusus
                                    perempuan. Namun, kenyataannya tempat tersebut
                                    masih memungkinkan laki-laki untuk masuk.
                                </p>
                                <p>
                                    Pengalaman tersebut memunculkan sebuah pertanyaan:
                                    apakah ada tempat perawatan kecantikan yang benar-benar
                                    dikhususkan bagi perempuan, khususnya muslimah,
                                    dengan tetap memperhatikan privasi dan kenyamanan
                                    mereka?
                                </p>
                            </div>
                        </article>

                        {{-- 02 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">02</span>
                            <span class="sjx-chip">Belajar</span>
                            <h3 class="sjx-slide-title">Belajar &amp; Berprinsip</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Berangkat dari kecintaan terhadap perawatan rambut,
                                    wajah, dan tubuh, pendiri Azzahra mulai mempelajari
                                    kebutuhan perawatan bagi seorang muslimah. Berbagai
                                    referensi keislaman dipelajari, termasuk Al-Qur'an
                                    Surah An-Nur ayat 31 dan Himpunan Putusan Tarjih
                                    Muhammadiyah. Pendiri juga berdiskusi dengan
                                    sejumlah tokoh agama dari Muhammadiyah dan
                                    Nahdlatul Ulama untuk memperluas pemahaman mengenai
                                    batasan aurat dan privasi perempuan.
                                </p>
                                <p>
                                    Dari proses tersebut, lahirlah sebuah prinsip
                                    yang menjadi fondasi Azzahra: menyediakan tempat
                                    perawatan yang secara khusus menjaga privasi
                                    perempuan muslimah. Keputusan ini menjadi bagian
                                    dari komitmen pendiri dalam menghadirkan ruang
                                    perawatan yang lebih nyaman dan terjaga.
                                </p>
                            </div>
                        </article>

                        {{-- 03 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">03</span>
                            <span class="sjx-chip">November 2004</span>
                            <h3 class="sjx-slide-title">Azzahra Berdiri di Jogokariyan</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada November 2004, Azzahra Salon &amp; Spa Muslimah
                                    resmi didirikan di kawasan Jogokariyan, Yogyakarta.
                                    Nama Azzahra diambil dari nama putri pertama
                                    pendiri, Mutia Azzahra. Kata Zahra memiliki makna
                                    bunga, yang melambangkan keindahan dan keberagaman
                                    yang berpadu dalam satu kesatuan.
                                </p>
                                <p>
                                    Meskipun memiliki latar belakang pendidikan sebagai
                                    insinyur pertambangan, pendiri percaya bahwa
                                    membangun usaha tidak harus selalu sesuai dengan
                                    bidang pendidikan. Berbekal pemahaman bisnis,
                                    keberanian, dan semangat untuk memberikan manfaat,
                                    Azzahra memulai perjalanan dengan membentuk tim
                                    pertama yang terdiri dari lima orang terapis.
                                </p>
                            </div>
                        </article>

                        {{-- 04 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">04</span>
                            <span class="sjx-chip">Masa Awal</span>
                            <h3 class="sjx-slide-title">Edukasi untuk Masyarakat</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada masa awal berdirinya, Azzahra juga aktif
                                    melakukan edukasi kepada masyarakat. Melalui
                                    talk show radio dan televisi, kegiatan di
                                    pengajian, perkantoran, sekolah, masjid, hingga
                                    pusat perbelanjaan, Azzahra memperkenalkan
                                    pentingnya merawat diri tanpa mengesampingkan
                                    nilai-nilai yang diyakini.
                                </p>
                                <p>
                                    Berbagai program seperti
                                    <em>Azzahra Go to School</em>,
                                    <em>Azzahra Go to Mosque</em>, dan
                                    <em>Azzahra Go to Office</em> menjadi bagian
                                    dari pendekatan edukasi tersebut.
                                </p>
                                <p>
                                    Selain pelayanan, Azzahra juga memberikan perhatian
                                    khusus terhadap pemilihan produk perawatan yang
                                    aman dan halal. Hal ini menjadi salah satu prinsip
                                    penting sejak awal perjalanan usaha.
                                </p>
                            </div>
                        </article>

                        {{-- 05 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">05</span>
                            <span class="sjx-chip">2005 • Gempa Bantul</span>
                            <h3 class="sjx-slide-title">Tumbuh &amp; Diuji</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Respons positif masyarakat mendorong Azzahra
                                    untuk terus berkembang. Pada tahun 2005,
                                    Azzahra membuka unit usaha kedua di kawasan
                                    Kauman melalui kerja sama permodalan.
                                    Perkembangan ini menjadi bukti bahwa kebutuhan
                                    terhadap tempat perawatan khusus perempuan
                                    muslimah mendapatkan sambutan yang baik.
                                </p>
                                <p>
                                    Perjalanan Azzahra tentu tidak selalu mudah.
                                    Gempa Bantul memberikan dampak besar terhadap
                                    operasional dan kondisi bangunan, termasuk unit
                                    Jogokariyan yang mengalami kerusakan sehingga
                                    membutuhkan renovasi. Namun, keadaan tersebut
                                    tidak menghentikan langkah Azzahra untuk bangkit
                                    dan melanjutkan perjalanan.
                                </p>
                            </div>
                        </article>

                        {{-- 06 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">06</span>
                            <span class="sjx-chip">Kebangkitan</span>
                            <h3 class="sjx-slide-title">Reborn Azzahra</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Setelah menghadapi masa sulit tersebut, Azzahra
                                    tidak memilih untuk berhenti. Justru dari masa
                                    pemulihan itu, lahir semangat untuk bangkit
                                    melalui sebuah kegiatan bertajuk
                                    <strong>Reborn Azzahra</strong>.
                                </p>
                                <p>
                                    Melalui acara tersebut, Azzahra menyelenggarakan
                                    talk show kecantikan yang menghadirkan Wakil
                                    Wali Kota, perwakilan industri produk kecantikan,
                                    serta sejumlah narasumber lainnya. Acara ini juga
                                    dimeriahkan dengan fashion show hasil kerja sama
                                    bersama desainer dan diselenggarakan di lingkungan
                                    Universitas Gadjah Mada (UGM).
                                </p>
                                <p>
                                    Kegiatan tersebut mendapatkan sambutan luar biasa
                                    dari masyarakat, khususnya kalangan muslimah.
                                    Di tengah suasana pemulihan pascagempa, acara
                                    ini menjadi ruang untuk kembali membangun
                                    semangat, kepercayaan diri, dan kebersamaan
                                    perempuan.
                                </p>
                                <p>
                                    Kiprah pendiri Azzahra juga mulai mendapatkan
                                    perhatian. Melalui pengalaman membangun bisnis
                                    salon dan spa muslimah, pendiri beberapa kali
                                    mendapatkan undangan dari Dinas Tenaga Kerja
                                    untuk berbagi pengalaman mengenai kewirausahaan
                                    perempuan. Pada masa itu, perempuan yang terjun
                                    langsung membangun dan mengelola bisnis masih
                                    belum sebanyak sekarang.
                                </p>
                            </div>
                        </article>

                        {{-- 07 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">07</span>
                            <span class="sjx-chip">2006 – 2007</span>
                            <h3 class="sjx-slide-title">Melangkah Lebih Jauh</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada tahun 2006, Azzahra juga mengembangkan
                                    kerja sama bisnis dengan mendirikan salon dan
                                    spa di lingkungan kampus penghafal Al-Qur'an
                                    milik Ustaz Yusuf Mansur, yang berlokasi di
                                    Jalan Imogiri Timur.
                                </p>
                                <p>
                                    Pengembangan usaha berlanjut pada tahun 2007.
                                    Azzahra dipercaya untuk mengawal dan mengembangkan
                                    salon di kawasan Bantul, yang berada di lingkungan
                                    sebuah pusat perbelanjaan atau swalayan besar.
                                    Kepercayaan tersebut menjadi salah satu langkah
                                    dalam memperluas jangkauan pelayanan Azzahra.
                                </p>
                            </div>
                        </article>

                        {{-- 08 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">08</span>
                            <span class="sjx-chip">2009</span>
                            <h3 class="sjx-slide-title">Babak Baru di Asri Medical Center</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada tahun 2009, Azzahra kembali mendapatkan
                                    kepercayaan untuk membuka unit usaha di
                                    Asri Medical Center (AMC), Yogyakarta.
                                    Kehadiran Azzahra di pusat layanan kesehatan
                                    dan bisnis terpadu tersebut menjadi babak baru
                                    dalam perjalanan pengembangan usaha.
                                </p>
                                <p>
                                    Di AMC, Azzahra tidak hanya menghadirkan layanan
                                    salon dan spa muslimah. Azzahra juga mengembangkan
                                    kegiatan lain melalui <strong>Azzahra Modeling</strong>
                                    dan <strong>Klub Muslimah</strong>, sebuah komunitas
                                    yang menyediakan kegiatan senam khusus perempuan.
                                </p>
                                <p>
                                    Grand opening Azzahra di AMC diselenggarakan
                                    dengan meriah melalui talk show yang menghadirkan
                                    model muslimah terkenal, serta berbagai kegiatan
                                    pendukung. Saat itu, Azzahra juga telah memiliki
                                    jingle khusus yang diciptakan bersama rekan-rekan
                                    dari komunitas nasyid, termasuk Mbak Candy Yudita.
                                    Acara tersebut turut dimeriahkan dengan lomba
                                    nasyid yang menjadi salah satu kegiatan istimewa
                                    dalam perjalanan Azzahra.
                                </p>
                                <p>
                                    Semua kegiatan tersebut mencerminkan bahwa
                                    Azzahra sejak awal membawa misi yang lebih luas
                                    daripada sekadar bisnis. Ada semangat dakwah,
                                    edukasi, dan pemberdayaan perempuan muslimah
                                    yang terus menjadi bagian dari identitas Azzahra.
                                </p>
                            </div>
                        </article>

                        {{-- 09 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">09</span>
                            <span class="sjx-chip">2009 – 2010</span>
                            <h3 class="sjx-slide-title">Franchise &amp; Doa</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Sejak sekitar tahun 2009, Azzahra juga mulai
                                    mengembangkan kerja sama melalui sistem
                                    franchise untuk salon dan spa muslimah.
                                    Bagi pendiri, memperluas Azzahra bukan semata-mata
                                    tentang membuka cabang baru, tetapi juga menjadi
                                    bagian dari upaya syiar dan memudahkan perempuan
                                    muslimah mendapatkan pelayanan perawatan.
                                </p>
                                <p>
                                    Pada tahun 2010, Azzahra mengembangkan kerja sama
                                    franchise dengan mendirikan unit salon di
                                    kawasan Seturan. Wilayah ini memiliki tantangan
                                    tersendiri karena persaingan bisnis yang cukup
                                    ketat. Namun, pendiri tetap berpegang pada
                                    keyakinan bahwa setiap usaha membutuhkan
                                    kerja keras, doa, dan kesabaran.
                                </p>
                                <p>
                                    Ada pengalaman yang tidak terlupakan selama
                                    perjalanan di Seturan. Pendiri menceritakan
                                    bahwa tempat usaha beberapa kali mengalami
                                    kejadian yang tidak biasa, termasuk nasi kuning
                                    yang dilemparkan ke area salon pada malam hari
                                    dan papan nama Azzahra yang kerap tertutup
                                    sehingga keberadaan salon sulit terlihat.
                                    Meski menghadapi berbagai tantangan tersebut,
                                    Azzahra memilih untuk tetap bersikap optimis.
                                </p>
                                <p>
                                    Bagi pendiri, menjalankan bisnis tidak cukup
                                    hanya dengan strategi dan manajemen. Ikhtiar
                                    juga harus disertai doa. Azzahra pernah
                                    mengadakan pengajian, mengundang anak-anak
                                    panti asuhan dan anak yatim untuk turut
                                    mendoakan kelancaran usaha. Bahkan, pada suatu
                                    malam Jumat, pendiri bersama tim melaksanakan
                                    salat tahajud di tempat usaha untuk memohon
                                    keberkahan dan kelancaran perjalanan bisnis Azzahra.
                                </p>
                            </div>
                        </article>

                        {{-- 10 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">10</span>
                            <span class="sjx-chip">2010 – 2011</span>
                            <h3 class="sjx-slide-title">Jaringan yang Meluas</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pengembangan jaringan usaha juga dilakukan
                                    melalui kerja sama dengan mitra di berbagai
                                    wilayah. Azzahra hadir di kawasan Sleman,
                                    termasuk di sekitar Pasar Sleman, tepatnya
                                    di samping Masjid Agung Sleman.
                                </p>
                                <p>
                                    Selain itu, Azzahra juga mengembangkan kerja
                                    sama dengan mitra di kawasan utara Monjali.
                                    Di wilayah Blok O, Azzahra dipercaya membantu
                                    melakukan rebranding salon yang telah ada
                                    melalui pembaruan identitas, konsep,
                                    dan manajemen usaha.
                                </p>
                                <p>
                                    Pada tahun 2011, pengembangan Azzahra berlanjut
                                    melalui kerja sama dengan mitra di wilayah
                                    Sleman. Salah satu unit franchise yang berada
                                    di sekitar Pasar Sleman, dekat Masjid Agung
                                    Sleman, bahkan masih bertahan hingga saat ini
                                    berdasarkan cerita pendiri.
                                </p>
                            </div>
                        </article>

                        {{-- 11 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">11</span>
                            <span class="sjx-chip">2011</span>
                            <h3 class="sjx-slide-title">Azzahra Ambassador</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada tahun yang sama, Azzahra juga menghadirkan
                                    program pengembangan perempuan melalui
                                    pemilihan <strong>Azzahra Ambassador</strong>.
                                    Program ini menarik perhatian banyak muslimah
                                    muda yang memiliki prestasi, kecerdasan,
                                    dan potensi untuk menjadi representasi
                                    perempuan muslimah yang percaya diri
                                    dan inspiratif.
                                </p>
                                <p>
                                    Salah satu peserta yang terpilih sebagai
                                    Azzahra Ambassador adalah Gita, seorang dokter
                                    yang menempuh pendidikan di Universitas Indonesia.
                                    Kehadiran para peserta dari kalangan mahasiswa
                                    dan perempuan berprestasi menunjukkan bahwa
                                    Azzahra ingin memberikan ruang bagi generasi
                                    muda muslimah untuk berkembang dan mengambil
                                    peran positif di masyarakat.
                                </p>
                                <p>
                                    Berbagai perjalanan tersebut menjadi bukti
                                    bahwa Azzahra tumbuh bukan hanya melalui
                                    pengembangan unit usaha, tetapi juga melalui
                                    kontribusi dalam edukasi, komunitas,
                                    kreativitas, dan pemberdayaan perempuan.
                                </p>
                            </div>
                        </article>

                        {{-- 12 --}}
                        <article class="sjx-slide">
                            <span class="sjx-ghost">12</span>
                            <span class="sjx-chip">Hingga Kini</span>
                            <h3 class="sjx-slide-title">Terus Bertumbuh</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Dari sebuah gagasan sederhana pada tahun 2004,
                                    Azzahra terus bertumbuh dengan membawa semangat
                                    yang sama: menghadirkan pelayanan kecantikan
                                    dan perawatan diri yang mengutamakan kenyamanan,
                                    privasi, kualitas, serta nilai-nilai muslimah.
                                </p>
                                <p>
                                    Bagi Azzahra, setiap tempat usaha yang tumbuh
                                    bukan hanya menjadi pencapaian bisnis, tetapi
                                    juga kesempatan untuk membuka lapangan pekerjaan,
                                    memberikan manfaat, dan memperluas pelayanan
                                    bagi perempuan muslimah.
                                </p>
                            </div>
                        </article>

                    </div>

                    <div class="sjx-controls">
                        <span class="sjx-hint">Geser, klik bab, atau gunakan tombol ← →</span>
                        <div class="sjx-btns">
                            <button type="button" class="sjx-btn" data-sjx-prev aria-label="Bab sebelumnya">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> Sebelumnya
                            </button>
                            <button type="button" class="sjx-btn is-primary" data-sjx-next aria-label="Bab berikutnya">
                                Lanjut <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- ILUSTRASI & FAKTA --}}
                <aside class="sjx-side">

                    <div class="sjx-art">
                        <img src="{{ asset('template/img/sejarah-azzahra.jpg') }}" alt="Perjalanan Azzahra Salon & Spa Muslimah">
                    </div>

                    <div class="sjx-facts">
                        <div class="sjx-fact">
                            <strong>2004</strong>
                            <span>Tahun berdiri</span>
                        </div>
                        <div class="sjx-fact">
                            <strong>5</strong>
                            <span>Terapis pertama</span>
                        </div>
                        <div class="sjx-fact">
                            <strong>Jogokariyan</strong>
                            <span>Lokasi pertama</span>
                        </div>
                    </div>

                </aside>

            </div>

        </section>


        {{-- ==========================================
             PERJALANAN KAMI — TIMELINE SLIDER
        =========================================== --}}
        <section class="sj-section">

            <div class="sj-head bkl-reveal">
                <span class="bkl-kicker">TIMELINE</span>
                <h2 class="sj-title">Perjalanan Kami</h2>

                <p class="sj-sub">
                    Setiap langkah memiliki cerita dan menjadi bagian
                    dari perjalanan Azzahra.
                </p>
            </div>

            <div class="sjx-panel sjx-tl bkl-reveal" data-sjx aria-roledescription="carousel" aria-label="Perjalanan Kami">

                <div class="sjx-top">
                    <div class="sjx-count">
                        <span>Tonggak</span>
                        <span><b data-sjx-cur>1</b> / <span data-sjx-total>11</span></span>
                    </div>
                    <div class="sjx-bar"><i data-sjx-bar></i></div>
                </div>

                <div class="sjx-rail" role="tablist" aria-label="Pilih tahun">
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2004</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2005</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2006</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2007</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2009</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2010</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>2011</button>
                    <button type="button" class="sjx-tab" role="tab" data-sjx-tab>Kini</button>
                </div>

                <div class="sjx-stage" tabindex="0" aria-live="polite">

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2004</div>
                        <div>
                            <h3 class="sjx-slide-title">Awal Berdirinya Azzahra</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Pada November 2004, Azzahra Salon &amp; Spa
                                    Muslimah resmi berdiri di kawasan Jogokariyan,
                                    Yogyakarta. Berawal dari gagasan menghadirkan
                                    tempat perawatan khusus perempuan muslimah
                                    dengan mengutamakan privasi dan kenyamanan.
                                    Perjalanan dimulai bersama lima orang terapis.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2005</div>
                        <div>
                            <h3 class="sjx-slide-title">Membuka Unit Kedua di Kauman</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Sambutan positif dari pelanggan mendorong
                                    Azzahra untuk memperluas pelayanan.
                                    Unit kedua resmi dibuka di kawasan Kauman
                                    melalui kerja sama permodalan.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2006</div>
                        <div>
                            <h3 class="sjx-slide-title">Reborn Azzahra dan Semangat Kebangkitan</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra menghadapi dampak gempa Bantul
                                    dan terus berupaya bangkit. Melalui kegiatan
                                    Reborn Azzahra, diselenggarakan talk show
                                    kecantikan dan fashion show. Pada periode
                                    ini, pendiri juga aktif berbagi pengalaman
                                    kewirausahaan perempuan.
                                </p>
                            </div>
                        </div>
                        <div class="sjx-tl-year"> </div>
                        <div>
                            <h3 class="sjx-slide-title">Kerja Sama di Imogiri Timur</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra mengembangkan kerja sama bisnis
                                    dengan menghadirkan salon dan spa di
                                    lingkungan kampus penghafal Al-Qur'an
                                    di Jalan Imogiri Timur.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2007</div>
                        <div>
                            <h3 class="sjx-slide-title">Pengembangan di Wilayah Bantul</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra mendapatkan kepercayaan untuk
                                    mengembangkan salon di lingkungan
                                    pusat perbelanjaan atau swalayan
                                    besar di wilayah Bantul.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2009</div>
                        <div>
                            <h3 class="sjx-slide-title">Hadir di Asri Medical Center</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra membuka unit di Asri Medical Center
                                    (AMC). Tidak hanya salon dan spa, Azzahra
                                    juga mengembangkan Azzahra Modeling dan
                                    Klub Muslimah dengan kegiatan senam khusus
                                    perempuan. Grand opening dimeriahkan
                                    talk show dan lomba nasyid.
                                </p>
                            </div>
                        </div>
                        <div class="sjx-tl-year"></div>
                        <div>
                            <h3 class="sjx-slide-title">Memulai Pengembangan Franchise</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra mulai mengembangkan sistem
                                    franchise salon dan spa muslimah.
                                    Langkah ini membuka kesempatan kerja
                                    sama dengan mitra untuk memperluas
                                    pelayanan dan misi syiar Azzahra.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2010</div>
                        <div>
                            <h3 class="sjx-slide-title">Ekspansi Franchise ke Seturan</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra mengembangkan unit kerja sama
                                    di kawasan Seturan. Menghadapi persaingan
                                    bisnis yang ketat, Azzahra tetap
                                    mengedepankan ikhtiar, doa, pengajian,
                                    dan kepedulian sosial sebagai bagian
                                    dari perjalanan usahanya.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">2011</div>
                        <div>
                            <h3 class="sjx-slide-title">Ekspansi Franchise ke Sleman</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra memperluas jaringan melalui
                                    kerja sama franchise di kawasan Sleman,
                                    termasuk di sekitar Pasar Sleman,
                                    dekat Masjid Agung Sleman.
                                    Pengembangan ini menjadi bagian dari
                                    upaya memperluas akses pelayanan
                                    bagi perempuan muslimah.
                                </p>
                            </div>
                        </div>
                        <div class="sjx-tl-year"></div>
                        <div>
                            <h3 class="sjx-slide-title">Azzahra Ambassador</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Azzahra menyelenggarakan pemilihan
                                    Azzahra Ambassador sebagai wadah
                                    bagi muslimah muda berprestasi
                                    untuk mengembangkan potensi,
                                    kepercayaan diri, dan kemampuan mereka.
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="sjx-slide sjx-tl-slide">
                        <div class="sjx-tl-year">Kini</div>
                        <div>
                            <h3 class="sjx-slide-title">Terus Bertumbuh dan Berinovasi</h3>
                            <div class="sjx-slide-body">
                                <p>
                                    Dengan pengalaman panjang sejak 2004,
                                    Azzahra terus berupaya mengembangkan
                                    pelayanan, meningkatkan kualitas
                                    perawatan, serta mempertahankan
                                    komitmen sebagai tempat perawatan
                                    khusus perempuan muslimah.
                                </p>
                            </div>
                        </div>
                    </article>

                </div>

                <div class="sjx-controls">
                    <span class="sjx-hint">Pilih tahun di atas atau geser ke kiri / kanan</span>
                    <div class="sjx-btns">
                        <button type="button" class="sjx-btn" data-sjx-prev aria-label="Tonggak sebelumnya">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> Sebelumnya
                        </button>
                        <button type="button" class="sjx-btn is-primary" data-sjx-next aria-label="Tonggak berikutnya">
                            Lanjut <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

            </div>

        </section>


        {{-- ==========================================
             NILAI KAMI
        =========================================== --}}
        <section class="sj-section">

            <div class="sj-head bkl-reveal">
                <span class="bkl-kicker">OUR VALUES</span>
                <h2 class="sj-title">Nilai Kami</h2>

                <p class="sj-sub">
                    Prinsip yang menjadi fondasi setiap pelayanan Azzahra.
                </p>
            </div>

                <div class="sj-values">

                    {{-- PRIVASI --}}
                    <div class="sj-value bkl-reveal">
                        <i class="fas fa-heart"></i>

                        <h3>Privasi &amp; Kenyamanan</h3>

                        <p>
                            Menghadirkan ruang perawatan khusus perempuan
                            yang menjaga privasi serta memberikan
                            rasa nyaman dan tenang bagi setiap pelanggan.
                        </p>
                    </div>

                    {{-- KEAMANAN --}}
                    <div class="sj-value bkl-reveal">
                        <i class="fas fa-shield-alt"></i>

                        <h3>Aman &amp; Terpercaya</h3>

                        <p>
                            Memperhatikan kebersihan, kualitas pelayanan,
                            serta pemilihan produk perawatan yang aman
                            dan halal untuk memberikan rasa percaya
                            kepada setiap pelanggan.
                        </p>
                    </div>

                    {{-- PROFESIONAL --}}
                    <div class="sj-value bkl-reveal">
                        <i class="fas fa-star"></i>

                        <h3>Dedikasi &amp; Profesional</h3>

                        <p>
                            Memberikan pelayanan terbaik melalui tenaga
                            terapis yang terlatih, penuh perhatian,
                            dan berkomitmen terhadap kualitas perawatan.
                        </p>
                    </div>

                </div>

        </section>


        {{-- ==========================================
             CALL TO ACTION
        =========================================== --}}
        <section class="sj-cta bkl-reveal">

            <span class="sj-cta-flower" aria-hidden="true">✿</span>

            <span class="sj-cta-kicker">BERSAMA AZZAHRA</span>

            <h2>Jadilah Bagian dari Perjalanan Kami</h2>

            <p>
                Rasakan pengalaman perawatan yang nyaman,
                privat, dan penuh perhatian bersama Azzahra.
            </p>

            <a href="{{ route('booking.create') }}" class="bkl-btn bkl-btn-light">
                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                Buat Booking
                <i class="fas fa-arrow-right bkl-btn-arrow" aria-hidden="true"></i>
            </a>

        </section>

    </div>

</main>

{{-- animasi halaman (progress bar, muncul bertahap, ripple tombol). Aman dilepas kapan saja. --}}
<script src="{{ asset('template/js/booking-list-fx.js') }}?v={{ file_exists(public_path('template/js/booking-list-fx.js')) ? filemtime(public_path('template/js/booking-list-fx.js')) : time() }}" defer></script>

{{-- slider sejarah: dipakai untuk "Cerita Kami" dan "Perjalanan Kami" --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('[data-sjx]').forEach(function (root) {

        var slides = Array.prototype.slice.call(root.querySelectorAll('.sjx-slide'));
        var tabs   = Array.prototype.slice.call(root.querySelectorAll('[data-sjx-tab]'));
        var prev   = root.querySelector('[data-sjx-prev]');
        var next   = root.querySelector('[data-sjx-next]');
        var bar    = root.querySelector('[data-sjx-bar]');
        var cur    = root.querySelector('[data-sjx-cur]');
        var total  = root.querySelector('[data-sjx-total]');
        var rail   = root.querySelector('.sjx-rail');
        var stage  = root.querySelector('.sjx-stage');
        var index  = 0;

        if (!slides.length) { return; }
        if (total) { total.textContent = slides.length; }

        function go(n, scrollRail) {

            index = Math.max(0, Math.min(slides.length - 1, n));

            slides.forEach(function (slide, k) {
                slide.classList.toggle('is-active', k === index);
                slide.classList.toggle('is-before', k < index);
                slide.setAttribute('aria-hidden', k === index ? 'false' : 'true');
            });

            tabs.forEach(function (tab, k) {
                tab.classList.toggle('is-active', k === index);
                tab.classList.toggle('is-passed', k < index);
                tab.setAttribute('aria-selected', k === index ? 'true' : 'false');
            });

            if (cur) { cur.textContent = index + 1; }
            if (bar) { bar.style.width = ((index + 1) / slides.length * 100) + '%'; }
            if (prev) { prev.disabled = index === 0; }
            if (next) { next.disabled = index === slides.length - 1; }

            // geser rail secara horizontal saja (tanpa menggulir halaman)
            if (scrollRail !== false && rail && tabs[index]) {
                rail.scrollTo({
                    left: tabs[index].offsetLeft - (rail.clientWidth / 2) + (tabs[index].offsetWidth / 2),
                    behavior: 'smooth'
                });
            }

        }

        tabs.forEach(function (tab, k) {
            tab.addEventListener('click', function () { go(k); });
        });

        if (prev) { prev.addEventListener('click', function () { go(index - 1); }); }
        if (next) { next.addEventListener('click', function () { go(index + 1); }); }

        // tombol panah keyboard (saat area slide atau tombolnya difokuskan)
        root.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { go(index + 1); }
            if (e.key === 'ArrowLeft')  { go(index - 1); }
        });

        // geser (swipe) di layar sentuh
        var startX = null, startY = null;

        stage.addEventListener('touchstart', function (e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        stage.addEventListener('touchend', function (e) {
            if (startX === null) { return; }
            var dx = e.changedTouches[0].clientX - startX;
            var dy = e.changedTouches[0].clientY - startY;
            startX = startY = null;
            if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy) * 1.5) {
                go(dx < 0 ? index + 1 : index - 1);
            }
        }, { passive: true });

        go(0, false);

    });

});
</script>

@endsection