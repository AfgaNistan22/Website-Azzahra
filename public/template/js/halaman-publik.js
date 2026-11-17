(function () {

    document.addEventListener('DOMContentLoaded', function () {

        /* ---------- animasi muncul saat di-scroll ---------- */
        var page = document.querySelector('.pg-page');
        var items = document.querySelectorAll('.pg-reveal');

        if (page && items.length && 'IntersectionObserver' in window) {

            page.classList.add('pg-ready');

            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });

            items.forEach(function (el) { io.observe(el); });
        }


        /* ---------- filter & lightbox galeri ---------- */
        var grid = document.getElementById('galeriGrid');
        if (!grid) return;

        var filterBar = document.getElementById('galeriFilter');
        var shots = Array.prototype.slice.call(grid.querySelectorAll('.pg-shot'));

        if (filterBar) {
            filterBar.addEventListener('click', function (e) {
                var chip = e.target.closest('.pg-chip');
                if (!chip) return;

                filterBar.querySelectorAll('.pg-chip').forEach(function (c) { c.classList.remove('is-active'); });
                chip.classList.add('is-active');

                var f = chip.dataset.filter;
                shots.forEach(function (s) {
                    s.style.display = (f === 'semua' || s.dataset.category === f) ? '' : 'none';
                });
            });
        }

        var lb = document.getElementById('pgLightbox');
        var lbImg = document.getElementById('pgLbImg');
        var lbCap = document.getElementById('pgLbCap');
        var current = 0;

        function visibleShots() {
            return shots.filter(function (s) { return s.style.display !== 'none'; });
        }

        function show(index) {
            var list = visibleShots();
            if (!list.length) return;

            current = (index + list.length) % list.length;
            lbImg.src = list[current].dataset.src;
            lbImg.alt = list[current].dataset.caption || '';
            lbCap.textContent = list[current].dataset.caption || '';
        }

        function open(shot) {
            var list = visibleShots();
            show(list.indexOf(shot));
            lb.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            lb.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        shots.forEach(function (s) {
            s.addEventListener('click', function () { open(s); });
        });

        document.getElementById('pgLbClose').addEventListener('click', close);
        document.getElementById('pgLbPrev').addEventListener('click', function () { show(current - 1); });
        document.getElementById('pgLbNext').addEventListener('click', function () { show(current + 1); });

        lb.addEventListener('click', function (e) { if (e.target === lb) close(); });

        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('is-open')) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') show(current - 1);
            if (e.key === 'ArrowRight') show(current + 1);
        });

    });

})();