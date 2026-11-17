/*
 * booking-fx.js (versi elegan)
 * Lapisan animasi halus di atas halaman booking. Tidak menyentuh logika form,
 * hanya mengamati DOM. Kalau file ini gagal dimuat, halaman tetap normal.
 */
(function () {
    'use strict';

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
    function rand(min, max) { return min + Math.random() * (max - min); }


    /* ---------- garis progres scroll ---------- */
    function initProgress() {
        var bar = document.createElement('div');
        bar.className = 'fx-progress';
        document.body.appendChild(bar);

        function update() {
            var h = document.documentElement;
            var max = h.scrollHeight - h.clientHeight;
            bar.style.transform = 'scaleX(' + (max > 0 ? h.scrollTop / max : 0) + ')';
        }

        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    }


    /* ---------- hero: debu emas melayang + parallax halus ---------- */
    function initHero() {
        var hero = $('.bk-hero');
        if (!hero || reduce) { return; }

        for (var i = 0; i < 14; i++) {
            var d = document.createElement('span');
            var size = rand(3, 9);
            d.className = 'fx-dust';
            d.setAttribute('aria-hidden', 'true');
            d.style.width = d.style.height = size + 'px';
            d.style.left = rand(2, 98) + '%';
            d.style.top = rand(8, 92) + '%';
            d.style.opacity = rand(.25, .75).toFixed(2);
            d.style.animationDuration = rand(9, 18).toFixed(1) + 's';
            d.style.animationDelay = (-rand(0, 14)).toFixed(1) + 's';
            hero.appendChild(d);
        }

        var inner = $('.bk-wrap', hero);
        if (!inner) { return; }

        var ticking = false;
        window.addEventListener('scroll', function () {
            if (ticking) { return; }
            ticking = true;
            requestAnimationFrame(function () {
                var y = Math.min(window.scrollY, hero.offsetHeight);
                inner.style.transform = 'translate3d(0,' + (y * 0.18).toFixed(1) + 'px,0)';
                inner.style.opacity = Math.max(0, 1 - y / (hero.offsetHeight * 0.9)).toFixed(2);
                ticking = false;
            });
        }, { passive: true });
    }


    /* ---------- muncul bertahap saat scroll ---------- */
    function initReveal() {
        if (!('IntersectionObserver' in window)) { return; }

        var sel = '.bk-card,.bk-stat,.bk-pay-item,.bk-empty,.bk-flash,.bk-info-item,' +
                  '.card-elite,.reservation-detail-card-elite,.treatment-filter-wrapper,.alert-box-elite';
        var els = $$(sel);
        if (!els.length) { return; }

        document.documentElement.classList.add('fx-ready');

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        els.forEach(function (el, i) {
            el.classList.add('fx-reveal');
            el.style.setProperty('--fx-i', i % 4);
            io.observe(el);
        });
    }


    /* ---------- cahaya emas mengikuti kursor di kartu perawatan ---------- */
    function initSpotlight() {
        if (!finePointer) { return; }

        $$('.treatment-card').forEach(function (card) {
            card.addEventListener('pointermove', function (e) {
                var r = card.getBoundingClientRect();
                card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                card.style.setProperty('--my', (e.clientY - r.top) + 'px');
            });
        });
    }


    /* ---------- cincin emas saat perawatan dipilih ---------- */
    function ringAt(el) {
        if (!el) { return; }
        var r = el.getBoundingClientRect();
        var ring = document.createElement('div');
        ring.className = 'fx-ring';
        ring.style.left = (r.left + r.width / 2) + 'px';
        ring.style.top = (r.top + r.height / 2) + 'px';
        document.body.appendChild(ring);
        setTimeout(function () { ring.remove(); }, 900);
    }

    function initSelectRing() {
        document.addEventListener('change', function (e) {
            var cb = e.target;
            if (cb && cb.classList && cb.classList.contains('treatment-checkbox') && cb.checked) {
                var card = cb.nextElementSibling;
                ringAt(card ? $('.treatment-check', card) : null);
            }
        });

        // tombol plus dari nol (fase capture, karena handler lama memakai stopPropagation)
        document.addEventListener('click', function (e) {
            var btn = e.target.closest ? e.target.closest('.btn-plus') : null;
            if (!btn) { return; }
            var q = document.getElementById('qty_' + btn.dataset.id);
            var prev = q ? (parseInt(q.textContent, 10) || 0) : 0;
            if (prev === 0) {
                setTimeout(function () {
                    var card = btn.closest('.treatment-card');
                    ringAt(card ? $('.treatment-check', card) : null);
                }, 0);
            }
        }, true);
    }


    /* ---------- angka berdenyut halus ---------- */
    function initBump() {
        $$('.quantity-value, #jumlahPilihan, #detailJumlah').forEach(function (el) {
            var last = el.textContent;
            new MutationObserver(function () {
                if (el.textContent === last) { return; }
                last = el.textContent;
                el.classList.remove('is-bump');
                void el.offsetWidth;
                el.classList.add('is-bump');
            }).observe(el, { childList: true, characterData: true, subtree: true });
        });
    }


    /* ---------- harga dan durasi menghitung naik ---------- */
    function toNumber(text) { return parseInt(String(text).replace(/\D/g, ''), 10) || 0; }

    function initCountUp() {

        [
            { id: 'detailHarga', fmt: function (n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); } },
            { id: 'detailDurasi', fmt: function (n) { return Math.round(n) + ' menit'; } }
        ].forEach(function (cfg) {
            var el = document.getElementById(cfg.id);
            if (!el) { return; }

            var last = toNumber(el.textContent);
            var raf = null;

            var obs = new MutationObserver(function () {
                var finalText = el.textContent;
                var to = toNumber(finalText);
                if (to === last) { return; }

                cancelAnimationFrame(raf);
                var from = last;
                var t0 = performance.now();
                last = to;

                (function step(t) {
                    var p = Math.min(1, (t - t0) / 800);
                    var e = 1 - Math.pow(1 - p, 4);

                    if (p < 1) {
                        el.textContent = cfg.fmt(from + (to - from) * e);
                        obs.takeRecords();
                        raf = requestAnimationFrame(step);
                    } else {
                        el.textContent = finalText;
                        obs.takeRecords();
                        el.classList.remove('is-bump');
                        void el.offsetWidth;
                        el.classList.add('is-bump');
                    }
                })(t0);
            });

            obs.observe(el, { childList: true, characterData: true, subtree: true });
        });
    }


    /* ---------- ripple lembut + magnet tipis di tombol ---------- */
    function initButtons() {
        var BTN = '.btn-gradient-elite, .btn-outline-elite, .bk-btn';

        document.addEventListener('click', function (e) {
            var btn = e.target.closest ? e.target.closest(BTN) : null;
            if (!btn) { return; }

            var r = btn.getBoundingClientRect();
            var size = Math.max(r.width, r.height);
            var ripple = document.createElement('span');
            ripple.className = 'fx-ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - r.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - r.top - size / 2) + 'px';
            btn.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 850);
        });

        if (reduce || !finePointer) { return; }

        $$('.btn-save-reservation-elite, .bk-btn-primary').forEach(function (btn) {
            btn.addEventListener('pointermove', function (e) {
                var r = btn.getBoundingClientRect();
                var x = (e.clientX - r.left - r.width / 2) * 0.08;
                var y = (e.clientY - r.top - r.height / 2) * 0.18;
                btn.style.translate = x.toFixed(1) + 'px ' + y.toFixed(1) + 'px';
            });
            btn.addEventListener('pointerleave', function () { btn.style.translate = ''; });
        });
    }


    function init() {
        document.documentElement.classList.add('fx-on');
        initProgress();
        initHero();
        initReveal();
        initSpotlight();
        initSelectRing();
        initBump();
        initCountUp();
        initButtons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();