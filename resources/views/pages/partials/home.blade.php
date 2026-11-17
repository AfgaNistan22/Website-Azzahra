@extends('pages.auth.guest')

{{-- CSS & JS bersama untuk seluruh section home.
     Tidak lagi butuh style.css / service.css / Swiper. --}}
@push('styles')
<style>
    :root {
        --hz-wine: #3a0d12;
        --hz-maroon: #501322;
        --hz-berry: #8b102e;
        --hz-pink: #d4145a;
        --hz-gold: #d4ad5a;
        --hz-gold-light: #ecc982;
        --hz-cream: #fffaf7;
        --hz-blush: #fbeaf0;
    }

    /* ---------- Reveal saat scroll ---------- */
    .rv {
        opacity: 0;
        transform: translateY(32px) scale(.98);
        filter: blur(6px);
        transition: opacity .8s cubic-bezier(.2, .8, .2, 1),
                    transform .8s cubic-bezier(.2, .8, .2, 1),
                    filter .8s ease;
        transition-delay: var(--d, 0s);
    }

    .rv.in {
        opacity: 1;
        transform: none;
        filter: none;
    }

    /* ---------- Judul section (dipakai bersama) ---------- */
    .hz-section { position: relative; padding: 110px 0; }

    .hz-head { max-width: 680px; margin: 0 auto 56px; text-align: center; }

    .hz-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border: 1px solid #f1d3dd;
        border-radius: 999px;
        background: #fff;
        color: var(--hz-berry);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        box-shadow: 0 6px 18px rgba(139, 16, 46, .08);
    }

    .hz-eyebrow::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--hz-pink);
        animation: hz-pulse 1.8s infinite;
    }

    .hz-title {
        margin: 18px 0 14px;
        color: var(--hz-wine);
        font-size: clamp(30px, 4.6vw, 48px);
        font-weight: 800;
        line-height: 1.12;
        letter-spacing: -.03em;
    }

    .hz-title em {
        font-style: normal;
        background: linear-gradient(110deg, var(--hz-berry), var(--hz-pink) 60%, var(--hz-gold));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hz-desc { margin: 0; color: #7b6068; font-size: 16px; line-height: 1.8; }

    @keyframes hz-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(212, 20, 90, .5); }
        70%  { box-shadow: 0 0 0 9px rgba(212, 20, 90, 0); }
        100% { box-shadow: 0 0 0 0 rgba(212, 20, 90, 0); }
    }

    /* ---------- Spotlight kartu ---------- */
    .spot { position: relative; overflow: hidden; }

    .spot::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        background: radial-gradient(380px circle at var(--mx, 50%) var(--my, 0%), rgba(212, 20, 90, .12), transparent 60%);
        opacity: 0;
        transition: opacity .3s;
        pointer-events: none;
    }

    .spot:hover::before { opacity: 1; }

    @media (max-width: 767px) {
        .hz-section { padding: 70px 0; }
        .hz-head { margin-bottom: 38px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .rv { opacity: 1; transform: none; filter: none; transition: none; }
        * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
    }
</style>
@endpush

@section('content')

@include('pages.partials.hero')

@include('pages.partials.service')

@endsection

@push('scripts')
<script>
(function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Reveal saat masuk viewport */
    var rvEls = document.querySelectorAll('.rv');
    if ('IntersectionObserver' in window && !reduce) {
        var rvObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('in'); rvObs.unobserve(e.target); }
            });
        }, { threshold: .15, rootMargin: '0px 0px -40px 0px' });
        rvEls.forEach(function (el) { rvObs.observe(el); });
    } else {
        rvEls.forEach(function (el) { el.classList.add('in'); });
    }

    /* Counter angka */
    function runCount(el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        var suffix = el.getAttribute('data-suffix') || '';
        if (reduce) { el.textContent = target.toLocaleString('id-ID') + suffix; return; }
        var dur = 1600, start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 4);
            el.textContent = Math.round(target * eased).toLocaleString('id-ID') + suffix;
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var counters = document.querySelectorAll('[data-count]');
    if ('IntersectionObserver' in window) {
        var cObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { runCount(e.target); cObs.unobserve(e.target); }
            });
        }, { threshold: .6 });
        counters.forEach(function (el) { cObs.observe(el); });
    } else {
        counters.forEach(runCount);
    }

    /* Spotlight mengikuti kursor */
    document.querySelectorAll('.spot').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
            card.style.setProperty('--my', (e.clientY - r.top) + 'px');
        });
    });

    /* Tilt 3D untuk visual hero */
    var tiltWrap = document.querySelector('[data-tilt]');
    if (tiltWrap && !reduce && window.matchMedia('(hover: hover)').matches) {
        tiltWrap.addEventListener('mousemove', function (e) {
            var r = tiltWrap.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width - .5;
            var y = (e.clientY - r.top) / r.height - .5;
            tiltWrap.style.setProperty('--ry', (x * 12) + 'deg');
            tiltWrap.style.setProperty('--rx', (-y * 12) + 'deg');
        });
        tiltWrap.addEventListener('mouseleave', function () {
            tiltWrap.style.setProperty('--ry', '0deg');
            tiltWrap.style.setProperty('--rx', '0deg');
        });
    }
})();
</script>
@endpush