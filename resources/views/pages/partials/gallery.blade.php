{{-- =========================================================
    GALERI — bento grid + lightbox (swipe, panah, keyboard)
    Memakai helper dari home.blade.php: .hz-section, .hz-head,
    .hz-eyebrow, .hz-title, .hz-desc, .rv, .spot
    Gambar: public/images/gallery1.jpg ... gallery6.jpg
========================================================= --}}
@php
    $galleryTotal = 6;
@endphp

<section class="hz-section gl-section" id="galeri">

    <span class="gl-blob gl-blob--a" aria-hidden="true"></span>
    <span class="gl-blob gl-blob--b" aria-hidden="true"></span>

    <div class="container position-relative">

        <div class="hz-head rv">
            <span class="hz-eyebrow">Galeri</span>
            <h2 class="hz-title">Intip suasana <em>salon kami</em></h2>
            <p class="hz-desc">
                Ruang yang bersih, hangat, dan nyaman untuk kamu bersantai.
                Klik foto untuk melihat lebih besar.
            </p>
        </div>

        <div class="gl-grid">
            @for ($i = 1; $i <= $galleryTotal; $i++)
                <div class="gl-cell rv" style="--d: {{ ($i - 1) * 0.08 }}s">
                    <button type="button"
                            class="gl-item spot"
                            data-gl-index="{{ $i - 1 }}"
                            aria-label="Perbesar foto suasana salon {{ $i }}">

                        <img src="{{ asset('images/gallery' . $i . '.jpg') }}"
                             alt="Suasana Azzahra Salon &amp; Spa {{ $i }}"
                             loading="lazy"
                             decoding="async"
                             onerror="this.closest('.gl-item').classList.add('is-empty'); this.remove();">

                        <span class="gl-num">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>

                        <span class="gl-cap">
                            <span>
                                <small>Azzahra Salon &amp; Spa</small>
                                Suasana {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <i class="fa-solid fa-expand" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            @endfor
        </div>

    </div>
</section>

{{-- Lightbox --}}
<div class="gl-box" id="glBox" role="dialog" aria-modal="true" aria-label="Pratinjau foto galeri" hidden>
    <button type="button" class="gl-box-btn gl-box-close" data-gl-close aria-label="Tutup">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <button type="button" class="gl-box-btn gl-box-prev" data-gl-prev aria-label="Foto sebelumnya">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <figure class="gl-box-fig">
        <img src="" alt="Pratinjau foto galeri" id="glBoxImg" draggable="false">
        <figcaption>
            <span id="glBoxTitle">Suasana Azzahra</span>
            <b id="glBoxCount">1 / {{ $galleryTotal }}</b>
        </figcaption>
    </figure>

    <button type="button" class="gl-box-btn gl-box-next" data-gl-next aria-label="Foto berikutnya">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
</div>

@push('styles')
<style>
    /* ---------- Section ---------- */
    .gl-section {
        overflow: hidden;
        background: linear-gradient(180deg, #fff 0%, #fff7f9 100%);
    }

    .gl-blob {
        position: absolute;
        z-index: 0;
        border-radius: 50%;
        filter: blur(80px);
        opacity: .5;
        pointer-events: none;
        animation: gl-drift 16s ease-in-out infinite alternate;
    }

    .gl-blob--a { top: 8%;    left: -140px;  width: 380px; height: 380px; background: #ffc2d8; }
    .gl-blob--b { bottom: 4%; right: -120px; width: 340px; height: 340px; background: #f6dca0; animation-delay: -6s; }

    @keyframes gl-drift { to { transform: translate(50px, 36px) scale(1.12); } }

    /* ---------- Bento grid ---------- */
    .gl-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: 220px;
        gap: 16px;
    }

    .gl-cell { min-width: 0; min-height: 0; }

    .gl-cell:nth-child(1) { grid-column: span 2; grid-row: span 2; }
    .gl-cell:nth-child(4) { grid-column: span 2; }
    .gl-cell:nth-child(6) { grid-column: span 3; }

    /* ---------- Item ---------- */
    .gl-item {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        border-radius: 28px;
        background: linear-gradient(135deg, #fbeaf0, #f6dca0);
        box-shadow: 0 10px 30px rgba(80, 19, 34, .08);
        cursor: zoom-in;
        isolation: isolate;
        transition: transform .55s cubic-bezier(.22, 1, .36, 1),
                    box-shadow .55s cubic-bezier(.22, 1, .36, 1);
    }

    .gl-item img {
        position: absolute;
        inset: 0;
        z-index: -1;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.1s cubic-bezier(.22, 1, .36, 1), filter .5s;
    }

    /* gradasi gelap untuk caption */
    .gl-item::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        border-radius: inherit;
        background: linear-gradient(180deg, transparent 45%, rgba(58, 13, 18, .85) 100%);
        opacity: 0;
        transition: opacity .45s;
        pointer-events: none;
    }

    /* ring gradasi tipis saat hover */
    .gl-item::before {
        z-index: 1;
    }

    .gl-item:hover,
    .gl-item:focus-visible {
        transform: translateY(-8px) rotate(-.6deg);
        box-shadow: 0 34px 70px rgba(80, 19, 34, .26);
    }

    .gl-item:focus-visible { outline: 3px solid var(--hz-pink, #d4145a); outline-offset: 4px; }

    .gl-item:hover img { transform: scale(1.12); filter: saturate(1.1); }
    .gl-item:hover::after,
    .gl-item:focus-visible::after { opacity: 1; }

    /* nomor kecil ala sticker */
    .gl-num {
        position: absolute;
        z-index: 2;
        top: 14px;
        left: 14px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .88);
        backdrop-filter: blur(8px);
        color: var(--hz-maroon, #501322);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        transition: transform .45s cubic-bezier(.22, 1, .36, 1), background .3s, color .3s;
    }

    .gl-item:hover .gl-num {
        transform: rotate(-6deg) scale(1.08);
        background: var(--hz-gold, #d4ad5a);
        color: var(--hz-wine, #3a0d12);
    }

    .gl-cap {
        position: absolute;
        z-index: 2;
        right: 18px;
        bottom: 16px;
        left: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.25;
        text-align: left;
        opacity: 0;
        transform: translateY(16px);
        transition: opacity .45s cubic-bezier(.22, 1, .36, 1), transform .45s cubic-bezier(.22, 1, .36, 1);
    }

    .gl-cap small {
        display: block;
        color: var(--hz-gold-light, #ecc982);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .gl-cap i {
        display: grid;
        flex-shrink: 0;
        place-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .22);
        backdrop-filter: blur(8px);
        font-size: 14px;
    }

    .gl-item:hover .gl-cap,
    .gl-item:focus-visible .gl-cap { opacity: 1; transform: none; }

    /* kalau file gambar tidak ada */
    .gl-item.is-empty { cursor: default; }
    .gl-item.is-empty::before {
        content: "\f03e";
        position: absolute;
        inset: 0;
        z-index: 0;
        display: grid;
        place-items: center;
        font-family: "Font Awesome 6 Free";
        font-size: 40px;
        font-weight: 900;
        color: var(--hz-berry, #8b102e);
        opacity: .35;
    }

    /* ---------- Lightbox ---------- */
    .gl-box {
        position: fixed;
        inset: 0;
        z-index: 3000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(30, 8, 12, .88);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .gl-box.is-open {
        display: flex;
        animation: gl-fade .25s ease-out;
    }

    .gl-box-fig {
        position: relative;
        margin: 0;
        max-width: min(1000px, 88vw);
        touch-action: pan-y;
    }

    .gl-box-fig img {
        display: block;
        max-width: 100%;
        max-height: 78vh;
        margin: 0 auto;
        border-radius: 24px;
        box-shadow: 0 40px 100px rgba(0, 0, 0, .55);
        user-select: none;
    }

    .gl-box-fig img.is-swap { animation: gl-zoom .4s cubic-bezier(.22, 1, .36, 1); }

    .gl-box-fig figcaption {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 16px;
        color: #f7ebe6;
        font-size: 14px;
        font-weight: 600;
    }

    .gl-box-fig figcaption b {
        padding: 5px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .14);
        font-size: 12px;
        letter-spacing: .08em;
    }

    .gl-box-btn {
        position: absolute;
        z-index: 2;
        display: grid;
        place-items: center;
        width: 50px;
        height: 50px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .16);
        color: #fff;
        font-size: 17px;
        cursor: pointer;
        transition: background .25s, transform .35s cubic-bezier(.22, 1, .36, 1);
    }

    .gl-box-btn:hover { background: var(--hz-pink, #d4145a); }
    .gl-box-close { top: 20px; right: 22px; }
    .gl-box-close:hover { transform: rotate(90deg); }
    .gl-box-prev  { left: 22px; top: 50%; margin-top: -25px; }
    .gl-box-next  { right: 22px; top: 50%; margin-top: -25px; }
    .gl-box-prev:hover { transform: translateX(-4px); }
    .gl-box-next:hover { transform: translateX(4px); }

    @keyframes gl-fade { from { opacity: 0; } }
    @keyframes gl-zoom { from { opacity: 0; transform: scale(.94); } }

    /* ---------- Responsive ---------- */
    @media (max-width: 991.98px) {
        .gl-grid { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 190px; }
        .gl-cell:nth-child(n)  { grid-column: auto; grid-row: auto; }
        .gl-cell:nth-child(1)  { grid-column: span 2; grid-row: span 2; }
        .gl-cell:nth-child(6)  { grid-column: span 2; }
    }

    @media (max-width: 575.98px) {
        .gl-grid { gap: 12px; grid-auto-rows: 150px; }
        .gl-item { border-radius: 22px; }
        .gl-cap { opacity: 1; transform: none; font-size: 13px; }
        .gl-cap i { width: 34px; height: 34px; }
        .gl-item::after { opacity: .9; }
        .gl-box { padding: 12px; }
        .gl-box-prev, .gl-box-next { top: auto; bottom: 22px; margin-top: 0; }
        .gl-box-prev { left: calc(50% - 62px); }
        .gl-box-next { right: calc(50% - 62px); }
        .gl-box-fig img { max-height: 66vh; border-radius: 18px; }
    }

    @media (hover: none) {
        .gl-item:hover { transform: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .gl-blob, .gl-box.is-open, .gl-box-fig img.is-swap { animation: none; }
        .gl-item, .gl-item img, .gl-cap, .gl-num { transition: none; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var box     = document.getElementById('glBox');
    if (!box) return;

    var img     = document.getElementById('glBoxImg');
    var title   = document.getElementById('glBoxTitle');
    var count   = document.getElementById('glBoxCount');
    var figure  = box.querySelector('.gl-box-fig');
    var items   = [];
    var current = 0;
    var lastFocus = null;

    function collect() {
        items = Array.prototype.slice
            .call(document.querySelectorAll('.gl-item'))
            .filter(function (el) { return !el.classList.contains('is-empty') && el.querySelector('img'); });
    }

    function show(i, animate) {
        if (!items.length) return;
        current = (i + items.length) % items.length;

        var src = items[current].querySelector('img');
        img.src = src.currentSrc || src.src;
        img.alt = src.alt;
        title.textContent = items[current].querySelector('.gl-cap span').lastChild.textContent.trim();
        count.textContent = (current + 1) + ' / ' + items.length;

        if (animate) {
            img.classList.remove('is-swap');
            void img.offsetWidth;
            img.classList.add('is-swap');
        }
    }

    function open(index) {
        collect();
        var pos = items.indexOf(document.querySelector('.gl-item[data-gl-index="' + index + '"]'));
        if (pos < 0) return;

        lastFocus = document.activeElement;
        box.hidden = false;
        box.classList.add('is-open');
        document.documentElement.style.overflow = 'hidden';
        show(pos, true);
        box.querySelector('[data-gl-close]').focus();
    }

    function close() {
        box.classList.remove('is-open');
        box.hidden = true;
        document.documentElement.style.overflow = '';
        if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    document.querySelectorAll('.gl-item').forEach(function (el) {
        el.addEventListener('click', function () {
            if (el.classList.contains('is-empty')) return;
            open(parseInt(el.getAttribute('data-gl-index'), 10));
        });
    });

    box.addEventListener('click', function (e) {
        if (e.target.closest('[data-gl-close]') || e.target === box) return close();
        if (e.target.closest('[data-gl-prev]')) return show(current - 1, true);
        if (e.target.closest('[data-gl-next]')) return show(current + 1, true);
    });

    document.addEventListener('keydown', function (e) {
        if (!box.classList.contains('is-open')) return;
        if (e.key === 'Escape')     close();
        if (e.key === 'ArrowLeft')  show(current - 1, true);
        if (e.key === 'ArrowRight') show(current + 1, true);
    });

    /* Geser jari di HP */
    var startX = null;
    figure.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
    figure.addEventListener('touchend', function (e) {
        if (startX === null) return;
        var dx = e.changedTouches[0].clientX - startX;
        startX = null;
        if (Math.abs(dx) > 50) show(current + (dx < 0 ? 1 : -1), true);
    });
})();
</script>
@endpush