/*
 * booking-list-fx.js
 * Animasi untuk halaman Daftar Booking. Hanya menambah efek di atas markup,
 * tidak mengubah data. Bila file ini gagal dimuat, halaman tetap tampil normal.
 */
(function () {
    'use strict';

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
    function rand(min, max) { return min + Math.random() * (max - min); }
    function easeOut(t) { return 1 - Math.pow(1 - t, 3); }


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


    /* ---------- muncul bertahap saat scroll ---------- */
    function initReveal() {
        var els = $$('.bkl-reveal');
        if (!els.length || !('IntersectionObserver' in window)) { return; }

        document.documentElement.classList.add('fx-ready');

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        els.forEach(function (el) { io.observe(el); });
    }


    /* ---------- angka menghitung naik ---------- */
    function animateNumber(el, to, format, duration) {
        var t0 = null;

        function step(t) {
            if (t0 === null) { t0 = t; }
            var p = Math.min(1, (t - t0) / duration);
            el.textContent = format(to * easeOut(p));
            if (p < 1) { requestAnimationFrame(step); }
            else { el.textContent = format(to); el.classList.add('is-bump'); }
        }

        requestAnimationFrame(step);
    }

    function initCounters() {
        var targets = [];

        $$('[data-count]').forEach(function (el) {
            var to = parseInt(el.getAttribute('data-count'), 10) || 0;
            if (to > 0) {
                targets.push({ el: el, to: to, fmt: function (n) { return String(Math.round(n)); }, dur: 1100 });
            }
        });

        $$('[data-rp]').forEach(function (el) {
            var to = parseInt(el.getAttribute('data-rp'), 10) || 0;
            if (to > 0) {
                targets.push({
                    el: el, to: to, dur: 1300,
                    fmt: function (n) { return 'Rp' + Math.round(n).toLocaleString('id-ID'); }
                });
            }
        });

        if (!targets.length) { return; }

        if (!('IntersectionObserver' in window)) { return; }

        targets.forEach(function (t) { t.el.textContent = t.fmt(0); });

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                var t = targets.filter(function (x) { return x.el === entry.target; })[0];
                if (t) { animateNumber(t.el, t.to, t.fmt, t.dur); }
                io.unobserve(entry.target);
            });
        }, { threshold: 0.5 });

        targets.forEach(function (t) { io.observe(t.el); });
    }


    /* ---------- tilt 3D dan cahaya mengikuti kursor ---------- */
    function initTilt() {
        if (!finePointer) { return; }

        var strength = reduce ? 0 : 4;

        $$('.bkl-stat, .bkl-item').forEach(function (card) {
            var item = card.classList.contains('bkl-item');
            var amp = item ? strength * 0.3 : strength;

            card.addEventListener('pointermove', function (e) {
                var r = card.getBoundingClientRect();
                var x = e.clientX - r.left;
                var y = e.clientY - r.top;
                card.style.setProperty('--mx', x + 'px');
                card.style.setProperty('--my', y + 'px');
                card.style.setProperty('--rx', ((x / r.width - 0.5) * amp).toFixed(2) + 'deg');
                card.style.setProperty('--ry', ((0.5 - y / r.height) * amp).toFixed(2) + 'deg');
            });

            card.addEventListener('pointerleave', function () {
                card.style.setProperty('--rx', '0deg');
                card.style.setProperty('--ry', '0deg');
            });
        });
    }


    /* ---------- hero: cahaya kursor, parallax, dan jejak sparkle ---------- */
    function initHero() {
        var hero = $('.bkl-hero');
        if (!hero || !finePointer) { return; }

        var art = $('.bkl-hero-art', hero);
        var glow = document.createElement('div');
        glow.className = 'bkl-cursor-glow';
        hero.appendChild(glow);

        var target = { x: -999, y: -999 };
        var cur = { x: -999, y: -999 };
        var running = false;

        function loop() {
            cur.x += (target.x - cur.x) * 0.12;
            cur.y += (target.y - cur.y) * 0.12;
            glow.style.setProperty('--cx', cur.x.toFixed(1) + 'px');
            glow.style.setProperty('--cy', cur.y.toFixed(1) + 'px');

            if (Math.abs(target.x - cur.x) > 0.5 || Math.abs(target.y - cur.y) > 0.5) {
                requestAnimationFrame(loop);
            } else {
                running = false;
            }
        }

        var glyphs = ['✦', '✧'];
        var colors = ['#F0CBD6', '#E9C9D1', '#FFFFFF', '#C98CA0'];
        var lastSpawn = 0;

        function spawn(x, y) {
            var g = document.createElement('span');
            g.className = 'fx-glyph';
            g.textContent = glyphs[Math.floor(Math.random() * glyphs.length)];
            g.style.left = (x + rand(-8, 8)) + 'px';
            g.style.top = (y + rand(-8, 8)) + 'px';
            g.style.fontSize = rand(10, 16) + 'px';
            g.style.color = colors[Math.floor(Math.random() * colors.length)];
            document.body.appendChild(g);
            setTimeout(function () { g.remove(); }, 1200);
        }

        hero.addEventListener('pointermove', function (e) {
            var r = hero.getBoundingClientRect();
            target.x = e.clientX - r.left;
            target.y = e.clientY - r.top;

            if (!running) { running = true; requestAnimationFrame(loop); }

            if (art && !reduce) {
                var nx = (e.clientX - r.left) / r.width - 0.5;
                var ny = (e.clientY - r.top) / r.height - 0.5;
                art.style.setProperty('--px', (nx * -26).toFixed(1) + 'px');
                art.style.setProperty('--py', (ny * -26).toFixed(1) + 'px');
            }

            var now = performance.now();
            if (!reduce && now - lastSpawn > 140) {
                lastSpawn = now;
                spawn(e.clientX, e.clientY);
            }
        });

        hero.addEventListener('pointerleave', function () {
            if (art) { art.style.setProperty('--px', '0px'); art.style.setProperty('--py', '0px'); }
        });
    }


    /* ---------- filter: pil gradien yang meluncur ---------- */
    function initFilterPill() {
        var box = $('.bkl-filters');
        if (!box) { return; }

        var items = $$('.bkl-filter', box);
        if (!items.length) { return; }

        var active = $('.bkl-filter.is-active', box) || items[0];
        var pill = document.createElement('span');
        pill.className = 'bkl-filter-pill';
        box.insertBefore(pill, box.firstChild);

        function place(el) {
            items.forEach(function (i) { i.classList.toggle('is-on', i === el); });
            pill.style.width = el.offsetWidth + 'px';
            pill.style.height = el.offsetHeight + 'px';
            pill.style.transform = 'translate(' + el.offsetLeft + 'px,' + el.offsetTop + 'px)';
        }

        // mulai dari item pertama tanpa transisi, lalu meluncur ke item aktif
        pill.style.transition = 'none';
        place(items[0]);
        void pill.offsetWidth;
        box.classList.add('has-pill');

        requestAnimationFrame(function () {
            pill.style.transition = '';
            place(active);
        });

        items.forEach(function (el) {
            el.addEventListener('pointerenter', function () { place(el); });
            el.addEventListener('focus', function () { place(el); });
        });

        box.addEventListener('pointerleave', function () { place(active); });
        box.addEventListener('focusout', function () { place(active); });

        window.addEventListener('resize', function () {
            pill.style.transition = 'none';
            place(active);
            void pill.offsetWidth;
            pill.style.transition = '';
        });

        // pastikan item aktif terlihat pada layar sempit
        if (active.scrollIntoView && box.scrollWidth > box.clientWidth) {
            box.scrollLeft = Math.max(0, active.offsetLeft - 20);
        }
    }


    /* ---------- ripple dan magnet pada tombol ---------- */
    function initButtons() {
        var SEL = '.bkl-btn, .bkl-action';

        document.addEventListener('click', function (e) {
            var btn = e.target.closest ? e.target.closest(SEL) : null;
            if (!btn) { return; }

            var r = btn.getBoundingClientRect();
            var size = Math.max(r.width, r.height);
            var ripple = document.createElement('span');
            ripple.className = 'fx-ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - r.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - r.top - size / 2) + 'px';
            btn.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 750);
        });

        if (reduce || !finePointer) { return; }

        $$('.bkl-btn, .bkl-action-primary').forEach(function (btn) {
            btn.addEventListener('pointermove', function (e) {
                var r = btn.getBoundingClientRect();
                var x = (e.clientX - r.left - r.width / 2) * 0.14;
                var y = (e.clientY - r.top - r.height / 2) * 0.22;
                btn.style.translate = x.toFixed(1) + 'px ' + y.toFixed(1) + 'px';
            });
            btn.addEventListener('pointerleave', function () { btn.style.translate = ''; });
        });
    }


    function init() {
        initProgress();
        initReveal();
        initCounters();
        initTilt();
        initHero();
        initFilterPill();
        initButtons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();